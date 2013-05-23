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

	if(!isset($_POST['loaded'])) {
		?>
		<html>
			<head>
				<title>404 Not Found</title>
			</head>
			<body>
				<h1>Not Found</h1>
				<?php
				echo "<p>The requested URL ".$_SERVER["REQUEST_URI"]." was not found on this server.</p>";
				?>
			</body>
		</html>
		<?php
	}
	else {
		?>
		<form name="signup_form" id="signup_form" action="../signup/signup.php" method="post">
			<input type="text" name="username" id="username" placeholder=" Username" required="required" onblur="checkField('username')">
			<br><br>
			<input type="password" name="password" id="password" placeholder=" Password" required="required" onblur="checkField('password')">
			<br><br>
			<input type="password" name="conf_password" id="conf_password" placeholder=" Confirm password" required="required" onblur="checkField('password')">
			<br><br>
		</form>
		<?php
	}
?>