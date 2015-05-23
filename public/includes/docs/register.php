<div id="content-head">
	<div class="text">
		<h2>Register</h2>
		<p>Create an account</p>
	</div>
</div>
<div class="text">
	<form method="post">
		<input type="text" class="intext" name="register_username" placeholder="Username">
		<input type="text" class="intext" name="register_email" placeholder="Email">
		<input type="password" class="intext" name="register_password" placeholder="Password">
		<input type="password" class="intext" name="register_password_1" placeholder="Confirm Password"><br>
		<input type="submit" class="btn" name="register_register" value="Register">
	</form>
	<?php
		if(isset($_POST['register_register'])){
			$user = new User(-1);
			$args = array(
				"name" => $_POST['register_username'],
				"password" => $_POST['register_password'],
				"email" => $_POST['register_email']
			);
			$user->make($args);
			if(!$user->register()){
				echo "<p>This user is already registered!</p>";
			}else{
				echo "<p>Registration complete!</p>";
			}


		}

	?>
</div>