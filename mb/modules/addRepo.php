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
	if(!isset($_SESSION["user"]) || !isset($_POST["repoName"])) {
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
		include("query.php");
		query("INSERT INTO repositories(repo_name,repo_creator) VALUES('".$_POST["repoName"]."',".$_SESSION["id"].")");
		$arr=query("SELECT * FROM repositories WHERE repo_id=(SELECT MAX(repo_id) FROM repositories)");			
		$query="SELECT acct_name FROM accounts WHERE acct_id=".$_SESSION["id"];
		$result=mysql_query($query);
		$arr2=sqltoarray($result);
		$name=$arr2[0]["acct_name"];
		$date=date("M\ d\, Y \(D\)-h\:i\:s a");
		$file=file_get_contents('logs.txt');
		$fp = fopen('logs.txt', 'w');
		fwrite($fp,$file);
		fwrite($fp,"$name created repository '".$_POST["repoName"]."' [$date]\n");
		fclose($fp);
		echo json_encode($arr);
	}
?>