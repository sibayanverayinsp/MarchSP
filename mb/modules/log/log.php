<?php

	function addLog($acct_id, $repo_id, $vers_id, $action) {
		log_connect();
		$query = "INSERT INTO `logs`(`log_date`, `log_acct_id`, `log_repo_id`, `log_vers_id`, `log_action`) VALUES (now(),  $acct_id, $repo_id, $vers_id, '".$action."')";
		$result=mysql_query($query) or die(mysql_error());
	}

	function getLogs() {
		log_connect();
		$query = "SELECT `log_date`, `log_action`,`acct_name`, `repo_name` FROM `logs`, `accounts`, `repositories` ORDER BY `log_date`";
		$result=mysql_query($query) or die(mysql_error());
		$arr = convert($result);
		return $arr;
	}
	
	function convert($result) {
		$table_result=array();
		$r=0;
		while($row=mysql_fetch_assoc($result)) {
			$arr_row=array();
			$c=0;
			while($c<mysql_num_fields($result)) {
				$col=mysql_fetch_field($result,$c);
				$arr_row[$col->name]=$row[$col->name];
				$c++;
			}
			$table_result[$r]=$arr_row;
			$r++;
		}
		mysql_free_result($result);
		return $table_result;
	}
	
	function log_connect(){
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