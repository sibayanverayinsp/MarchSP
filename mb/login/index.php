<?php

/**
 * @title
 * MagicBox: A Simple Version Control System
 *
 * @description
 * A Special Problem Presented to the Faculty of
 * The Institute of Computer Science
 * University of the Philippines Los Banos
 *
 * In Partial Fulfillment of the Requirements of the Degree of
 * Bachelor of Science in Computer Science
 *
 * @authors
 * Jasper A. Sibayan 
 * 2009-46112
 * and 
 * Wilbert G. Verayin
 * 2009-60315
 * @date
 * April 2013
 */

	session_start();
	if(isset($_SESSION['user'])) {
		header("Location: ../");
	}
	else {
		?>
		<html>
			<head>
				<title>Magic Box</title>
				<link rel="stylesheet" type="text/css" href="../css/mb.css">
				<link rel="icon" href="../favicon.ico" type="image/x-icon" />
				<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
				<script src="../js/jquery-1.9.1.min.js"></script>
				<script src="../js/jquery-ui-1.10.0/ui/minified/jquery-ui.min.js"></script>
				<script src="../js/jquery.form.js"></script>
				<script src="../js/mb.js"></script>
				<script src="../js/jqueryFunc.js"></script>
				<!--<script src="../js/disable.js"></script>-->
				<script type="text/javascript">
					$(document).ready(function() {
						jqueryFunc();
						$(".ip").load("../modules/getIP.php",{valid:1});
					});
				</script>
			</head>
			<!--<body onkeypress="return disableKeyCombination(event);" onkeydown="return disableKeyCombination(event);" ondragstart="return false" onselectstart="return false">-->
			<body>
				<div class="container">
					<div class="login_signup">
						<form name="login_form" id="login_form" action="login.php" method="post">
							<input type="text" name="username" id="username" placeholder=" Username" required="required" onblur="checkField('username')">
							<br><br>
							<input type="password" name="password" id="password" placeholder=" Password" required="required" onblur="checkField('password')">
							<br><br>
							<input type="submit" class="submit_btn" value="Log In">
							&nbsp;&nbsp;&nbsp;
							<a class="signup_btn">Sign Up</a>
						</form>
						<div class="err_msg">
							<?php
								if(!isset($_SESSION['user'])) {
									if(isset($_GET['err'])) {
										if($_GET['err']==1) {
											echo "Username and Password do not match!";
										}
										else if($_GET['err']==2) {
											echo "Members cannot be Admin!";
										}
									}
								}
							?>
						</div>
					</div>
				</div>
				<div class="ip" style="opacity:0;"></div>
			</body>
		</html>
		<?php
	}
?>