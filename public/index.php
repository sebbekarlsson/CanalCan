<?php

	$doc = $_GET['doc'];
	$doc = "video-flow.php";

?>
<DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="styles/style.css">
		<link href='http://fonts.googleapis.com/css?family=Roboto:300' rel='stylesheet' type='text/css'>
		<meta charset="utf-8">
	</head>

	<body>
		<div id="content">
			<div id="left-content" class="content-part">
				<?php include("includes/left-menu.php"); ?>
			</div>
			<div id="right-content" class="content-part">
				<?php include("includes/docs/$doc"); ?>
			</div>
		</div>
	</body>

	<footer>
	</footer>
</html>