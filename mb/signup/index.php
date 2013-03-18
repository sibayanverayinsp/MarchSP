<form name="signup_form" id="signup_form" action="../signup/signup.php" method="post">
	<input type="text" name="username" id="username" placeholder=" Username" required="required" onblur="checkField('username')">
	<br><br>
	<input type="password" name="password" id="password" placeholder=" Password" required="required" onblur="checkField('password')">
	<br><br>
	<input type="password" name="conf_password" id="conf_password" placeholder=" Confirm password" required="required" onblur="checkField('password')">
	<br><br>
</form>
<?php
	if(!isset($_POST['loaded'])) {
		header('Location: ../');
	}
?>