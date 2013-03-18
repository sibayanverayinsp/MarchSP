<?php
	session_start();
	if(isset($_POST['username'])) {
		include("../modules/query.php");
		$arr=query("SELECT * FROM accounts");
		$len=sizeof($arr);
		for($i=0; $i<$len; $i++) {
			if($_POST['username']==$arr[$i]['acct_name'] && $_POST['password']==$arr[$i]['acct_pass']) {
				$_SESSION['id']=$arr[$i]['acct_id'];
				$_SESSION['user']=$_POST['username'];
				$_SESSION['pass']=$_POST['password'];
			}
		}
		if(!isset($_SESSION['user'])) {
			header('Location: ../login?err=1');
		}
		else {
			header('Location: ../');
		}
	}
	else {
		header('Location: ../login');
	}
?>