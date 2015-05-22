<?php

$db = new PDO("mysql:host=home.dev;dbname=canalcan", "root", "tango255");

function user_exists($userName){
	global $db;

	$count = 0;
	$xe = $db->prepare("SELECT * FROM users WHERE userName='$userName'");
	$result = $xe->execute();
	while(($row = $result->fetch()) != false){
		$count ++;
	}

	return ($count > 0);
}

class User{
	var $data;

	function __construct($id){
		$this->userID = $id;		
	}

	function make($args = array()){
		$this->data = (Object) $args;
	}

	function fetch_build(){
		global $db;

		$xe = $db->prepare("SELECT * FROM users WHERE userID=$id");
		$result = $xe->execute();
		while(($row = $result->fetch()) != false){
			$this->data->name = $row['userName'];
			$this->data->password = $row['userPassword'];
			$this->email = $row['userEmail'];
		}	
	}

	function register(){
		global $db;
		if(!user_exists($this->id)){
			$xe = $db->prepare("INSERT INTO users (userName, userEmail, userPassword) VALUES('$this->userName', '$this->userEmail', '$this->userPassword')");
			$xe->execute();
		}
	}
}