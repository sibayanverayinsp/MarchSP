<?php
	session_start();
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
			//header('Location: ../');
		}
	}
	else {
		header('Location: ../login');
	}
?>