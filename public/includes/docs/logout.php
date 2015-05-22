<?php
	session_start();
	
	unset($_SESSION['userID']);

?>

<script>
	window.location.href="index.php";
</script>