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
	if(!isset($_SESSION['user']) || !isset($_POST["id"])) {
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
		flock_connect();	
		$query="SELECT vers_type FROM versions WHERE vers_id=".$_POST["id"];
		$result=mysql_query($query) or die(mysql_error());
		$arr=flock_convert($result);
		if($arr[0]["vers_type"]=="dir") {
			$hasLock=findLock($_POST["id"],false);
			if($hasLock) {
				echo "has locked!";
			}
			else {
				echo "no locked";
			}
		}
	}

	function findLock($id,$hasLock) {
		STATIC $status;
		$query="SELECT vers_id, vers_name, vers_type, vers_lock_acct_id FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions WHERE vers_parent=".$id." ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a WHERE a.vers_type='file' OR a.vers_type='dir'";
		$result=mysql_query($query) or die(mysql_error());
		$arr=flock_convert($result);
		$len=sizeof($arr);
		$i=0;
		for($i=0; $i<$len; $i++) {
			if(!$hasLock) {
				if($arr[$i]["vers_lock_acct_id"]!=0) {
					break;
				}
				else {
					if($arr[$i]["vers_type"]=="dir") {
						$hasLock=findLock($arr[$i]["vers_id"],$hasLock);
					}
				}
			}
			else {
				break;
			}
		}
		if($status == true){
			return true;
		}
		if($i==$len) {
			$status = false;
			return false;
		}
		else {
			$status = true;
			return true;
		}
	}
	
	function flock_convert($result) {
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
	
	function flock_connect() {
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