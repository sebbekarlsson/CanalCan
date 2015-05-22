<div id="content-head">
	<div class="text">
		<h2>Register</h2>
		<p>Create an account</p>
	</div>
</div>
<div class="text">
	<form method="post">
		<input type="text" class="intext" name="register_username" placeholder="Username">
		<input type="password" class="intext" name="register_password" placeholder="Password">
		<input type="password" class="intext" name="register_password_1" placeholder="Confirm Password"><br>
		<input type="submit" class="btn" name="register_register" value="Register">
	</form>
	<?php

		$user = new User(0);
		$user->make(array("asp" => "bad", "php" => "good"));

	?>
</div>