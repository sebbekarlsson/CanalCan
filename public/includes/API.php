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
			$this->data->views = $row['videoViews'];
			$this->data->userID = $row['userID'];
			$this->data->date = $row['videoDate'];
		}
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

			exec("ffmpeg -i uploads/videos/".$filename.".mp4 -ss 00:00:14.435 -vframes 1 uploads/videos/thumbs/$filename.png");
			return true;
		}
	}
}