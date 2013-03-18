<?php
	session_start();
	if(!isset($_SESSION["user"])) {
		header("Location: ../login");
	}
	else {
		if(isset($_POST["repoName"])) {
			include("query.php");
			query("INSERT INTO repositories(repo_name,repo_creator) VALUES('".$_POST["repoName"]."',".$_SESSION["id"].")");
			$arr=query("SELECT * FROM repositories WHERE repo_id=(SELECT MAX(repo_id) FROM repositories)");
		}
	}
	echo json_encode($arr);
	//header("Location: ../login");
?>