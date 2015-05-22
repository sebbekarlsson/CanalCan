<div id="content-head">
	<div class="text">
		<h2>Login</h2>
		<p>Login to your account</p>
	</div>
</div>
<div class="text">
	<form method="post">
		<input type="text" class="intext" name="login_username" placeholder="Username">
		<input type="password" class="intext" name="login_password" placeholder="Password"><br>
		<input type="submit" class="btn" name="login_login" value="Login">
	</form>
	<?php

		if(isset($_POST['login_login'])){
			$user = new User(-1);
			$user->make(array(
				"name" => $_POST['login_username'],
				"password" => $_POST['login_password']
			));

			if($user->validate() == "wrong"){
				echo "<p>Wrong password!</p>";
			}else{
				$_SESSION['userID'] = $user->validate();
				?>
					<script>
						window.location.href="index.php";
					</script>
				<?php
			}
		}

	?>
</div>