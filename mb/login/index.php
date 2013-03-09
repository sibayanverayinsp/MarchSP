<!DOCTYPE html>
<html>
	<?php
		session_start();
		if(isset($_SESSION['user'])) {
			header('Location: ../');
		}
	?>
	<head>
		<title>Magic Box</title>
		<!--<link rel="stylesheet" type="text/css" href="../js/jquery-ui-1.10.0/themes/base/jquery-ui.css">-->
		<link rel="stylesheet" type="text/css" href="../css/mb.css">
		<script src="../js/jquery-1.9.1.min.js"></script>
		<!--<script src="../js/jquery-ui-1.10.0/ui/jquery-ui.js"></script>-->
		<script src="../js/mb.js"></script>
		<script src="../js/jqueryFunc.js"></script>
		<!--<script src="../js/disable.js"></script>-->
	</head>
	<body>
		<div class="container">
			<form name="login_form" id="login_form" action="login.php" method="post">
				<input type="text" name="username" id="username" placeholder=" Username" required="required" onblur="checkField('username')">
				<br><br>
				<input type="password" name="password" id="password" placeholder=" Password" required="required" onblur="checkField('password')">
				<br><br>
				<input type="submit" class="submit_btn" value="Login">
			</form>
			<div class="err_msg">
				<?php
					if(!isset($_SESSION['user'])) {
						if(isset($_GET['err'])) {
							if($_GET['err']==1) {
								echo "Username and Password do not match!";
							}
						}
					}
				?>
			</div>
		</div>
	</body>
</html>