<?php
	/*if(!isset($_SESSION['user'])) {
		header('Location: ../');
	}*/
	function dbconnect() {
		$link=mysql_connect("localhost","root","") or die('Database connection error.');	
		if(!empty($link->connect_error)) {
			$result['error']=$link->connect_error;
			print_r($result);
		}
		else {
			mysql_select_db("magicboxdb");
		}
	}
?>