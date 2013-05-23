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
	if(isset($_POST['username'])) {
		include("../modules/query.php");
		$arr=query("SELECT * FROM accounts");
		$len=sizeof($arr);
		$hostname = $_SERVER['HTTP_HOST'];
		$cannotAdmin=0;
		for($i=0; $i<$len; $i++) {
			if($_POST['username']==$arr[$i]['acct_name'] && $_POST['password']==$arr[$i]['acct_pass']) {
				if($arr[$i]['acct_type']=="Admin") {
					if($hostname==$_SERVER['REMOTE_ADDR'] || $hostname=="localhost" || $hostname=="127.0.0.1") {
						$_SESSION['id']=$arr[$i]['acct_id'];
						$_SESSION['user']=$_POST['username'];
						$_SESSION['pass']=$_POST['password'];
						$_SESSION['type']=$arr[$i]['acct_type'];
					}
					else {
						$cannotAdmin=1;
					}
				}
				else {
					$_SESSION['id']=$arr[$i]['acct_id'];
					$_SESSION['user']=$_POST['username'];
					$_SESSION['pass']=$_POST['password'];
					$_SESSION['type']=$arr[$i]['acct_type'];
				}
				break;
			}
		}
		if(!isset($_SESSION['user'])) {
			if($cannotAdmin==1) {
				header('Location: ../login?err=2');
			}
			else {
				header('Location: ../login?err=1');
			}
		}
		else {
			header('Location: ../');
		}
	}
	else {
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
?>