<?php

session_start();

$db = new PDO("mysql:host=localhost;dbname=canalcan", "root", "tango255");

if(isset($_SESSION['userID'])){
	$USER = new User($_SESSION['userID']);
	$USER->fetch_build();
}

function is_loggedin(){
	return isset($_SESSION['userID']);
}

function get_user_ip(){
	return $_SERVER['REMOTE_ADDR'];
}

function user_exists($userName){
	global $db;

	$count = 0;
	$xe = $db->prepare("SELECT * FROM users WHERE userName='$userName'");
	$xe->execute();
	while(($row = $xe->fetch()) != false){
		$count++;
	}

	return ($count > 0);
}

function redirect(){
	if(!is_loggedin()){
		?>
		<script>window.location.href="index.php";</script>
		<?php
	}
}

function getRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

class User{
	var $data;

	function __construct($id){
		$this->data->id = $id;		
	}

	function make($args = array()){
		$this->data = (Object) $args;
	}

	function fetch_build(){
		global $db;

		$xe = $db->prepare("SELECT * FROM users WHERE userID=".$this->data->id);
		$xe->execute();
		while(($row = $xe->fetch()) != false){
			$this->data->name = $row['userName'];
			$this->data->password = $row['userPassword'];
			$this->data->email = $row['userEmail'];
		}	
	}

	function register(){
		global $db;

		if(user_exists($this->data->name)){
			return false;
		}

		$sql = "INSERT INTO users (userName, userEmail, userPassword) VALUES('".$this->data->name."', '".$this->data->email."', '".$this->data->password."')";
		$xe = $db->prepare($sql);
		$xe->execute();
		
		return true;
	}

	function validate(){
		global $db;

		$xe = $db->prepare("SELECT userPassword, userID FROM users WHERE userName='".$this->data->name."'");
		$xe->execute();
		while(($row = $xe->fetch()) != false){
			$realpassword = $row['userPassword'];
			$id = $row['userID'];
		}

		if($this->data->password != $realpassword){
			return "wrong";
		}

		return $id;
	}
}

class Video{
	var $data;

	function __construct($id){
		$this->data->id = $id;
	}
	
	function make($args = array()){
		$this->data = (Object) $args;
	}

	function fetch_build(){
		global $db;
		$xe = $db->prepare("SELECT * FROM videos WHERE videoID=".$this->data->id);
		$xe->execute();
		while(($row = $xe->fetch()) != false){
			$this->data->title = $row['videoTitle'];
			$this->data->description = $row['videoDescription'];
			$this->data->filename = $row['videoFile'];
			$this->data->userID = $row['userID'];
			$this->data->date = $row['videoDate'];
		}
	}

	function hasViewed($ip){
		global $db;

		$count = 0;

		$xe = $db->prepare("SELECT * FROM videoViews WHERE userIP='".$ip."' AND videoID=".$this->data->id);
		$xe->execute();

		while(($row = $xe->fetch()) != false){
			$count++;
		} 

		return ($count > 0);

	}

	function addViewer($ip){
		global $db;
		$videoID = $this->data->id;

		if(!$this->hasViewed($ip)){

			if(!is_loggedin()){
				$userID = -1;
			}else{
				global $USER;
				$userID = $USER->data->id;
			}
			$xe = $db->prepare("INSERT INTO videoViews (userIP, userID, videoID) VALUES('$ip', $userID, ".$this->data->id.")");
			$xe->execute();
			var_dump($xe->errorInfo());
		}
	}

	function getViewers(){
		global $db;
		$viewers = array();

		$xe = $db->prepare("SELECT * FROM videoViews WHERE videoID=".$this->data->id);
		$xe->execute();
		while(($row = $xe->fetch()) != false){
			$user = new User($row['userID']);
			$user->fetch_build();
			array_push($viewers, $user);
		}

		return $viewers;
	}

	function getShortDescription(){
		return substr($this->data->description, 0, 140);
	}

	function delete(){
		global $db;
		$fname = $this->data->filename;
		$thumb = str_replace(".mp4", ".png", $fname);

		$xe = $db->prepare("DELETE FROM videos WHERE videoID=".$this->data->id);
		$xe->execute();

		$xe = $db->prepare("DELETE FROM tags WHERE videoID=".$this->data->id);
		$xe->execute();

		unlink("uploads/videos/thumbs/$thumb");
		unlink("uploads/videos/$fname");
	}

	function upload(){
		global $db;
		$data = $this->data;

		if($data->file['type'] != "video/mp4"){
			$this->data->error = "The file needs to be video/mp4";
			return false;
		}

		if(strlen($data->title) < 3){
			$this->data->error = "The title needs to be at least 3 characters";
			return false;
		}

		if(strlen($data->description) < 16){
			$this->data->error = "The description must be at least 16 characters";
			return false;
		}

		if(is_array($data->tags)){
			if(count($data->tags) < 1){
				$this->data->error = "You must include at least 1 tag";
				return false;
			}
		}else{
			if($data->tags == "" || strlen($data->tags) < 3){
				$this->data->error = "Video was not uploaded, check your tag";
				return false;
			}
		}

		$filename = getRandomString(10);
		if(!move_uploaded_file($data->file['tmp_name'], "uploads/videos/".$filename.".mp4")){
			$this->data->error = "Could not upload file";
			return false;
		}else{
			$xe = $db->prepare("INSERT INTO videos (videoTitle, videoDescription, userID, videoViews, videoFile) VALUES('".$data->title."', '".$data->description."', ".$data->userID.", 0, '".$filename.".mp4')");
			$xe->execute();

			$videoID = $db->lastInsertId();

			$tags = $data->tags;
			if(is_array($tags)){
				foreach ($tags as $tag) {
					$xe = $db->prepare("REPLACE INTO tags (videoID, tagName) VALUES($videoID, '".$tag."')");
					$xe->execute();
				}
			}else{
				$xe = $db->prepare("REPLACE INTO tags (videoID, tagName) VALUES($videoID, $tags)");
				$xe->execute();
			}

			$output = array();
			exec("ffmpeg -i uploads/videos/".$filename.".mp4 2>&1 | grep 'Duration'", $output);

			$duration = str_replace("Duration: ", "", explode(",", $output[0])[0]);

			$h = explode(":", $duration)[0];
			$m = explode(":", $duration)[1];
			$s = explode(":", $duration)[2];

			$half_h = floor($h/2);
			$half_m = floor($m/2);
			$half_s = floor($s/2);
				
			exec("ffmpeg -i uploads/videos/".$filename.".mp4 -ss $half_h:$half_m:$half_s -vframes 1 uploads/videos/thumbs/$filename.png");
			return true;
		}
	}
}