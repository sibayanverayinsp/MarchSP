<?php	/*if(!isset($_SESSION["user"])) {		header("Location: ../login");	}*/	include("connect.php");	include("sqltoarray.php");	function query($query_str) {		dbconnect();		$returned=mysql_query("set names 'utf8'");		$result=mysql_query($query_str) or die(mysql_error());		$temp=explode(" ",$query_str);		if($temp[0]=="SELECT") {			$arr_table_result=sqltoarray($result);			return $arr_table_result;		}	}?>