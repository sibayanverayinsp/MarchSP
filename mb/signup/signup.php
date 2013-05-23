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

	if(isset($_POST['username'])) {
		include("../modules/query.php");
		$arr=query("SELECT acct_name FROM accounts");
		$len=sizeof($arr);
		$isExist=false;
		for($i=0; $i<$len; $i++) {
			if($_POST['username']==$arr[$i]['acct_name']) {
				$isExist=true;
			}
		}
		if($isExist) {
			echo "err";
		}
		else {
			echo "noerr";
			query("INSERT INTO accounts(acct_name,acct_pass,acct_type) VALUES('".$_POST['username']."','".$_POST['password']."','".$_POST['type']."')");
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