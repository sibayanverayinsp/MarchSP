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
	if(!isset($_SESSION["user"]) || !isset($_POST["lock_id"])) {
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
		lock_connect();
		$query="SELECT vers_type, vers_lock_acct_id FROM versions WHERE vers_id=".$_POST["lock_id"];
		$result=mysql_query($query) or die(mysql_error());
		$arr=lock_convert($result);
		$action="";
		if($arr[0]["vers_type"]=="dir") {
			if($arr[0]["vers_lock_acct_id"]==0) {
				$action="lock";
				echo "folder_locked!";
				lockUnlockDir($_POST["lock_id"],$action);
				recursiveLockUnlock($_POST["lock_id"],$action);
			}
			else {
				if($arr[0]["vers_lock_acct_id"]==$_SESSION["id"] || $_SESSION["type"]=="Admin") {
					$action="unlock";
					echo "folder_unlocked!";
					lockUnlockDir($_POST["lock_id"],$action);
					recursiveLockUnlock($_POST["lock_id"],$action);
				}
				else {
					echo "cannot unlock!";
				}
			}
		}
		else {
			if($arr[0]["vers_lock_acct_id"]==0) {
				$action="lock";
				echo "locked!";
				$query="SELECT vers_id, vers_repo_id FROM versions WHERE vers_id=".$_POST["lock_id"];
				$result=mysql_query($query) or die(mysql_error());
				$arr=lock_convert($result);
				$repo_id=$arr[0]['vers_repo_id'];
				$versid=$arr[0]['vers_id'];
				$acct_id=$_SESSION["id"];
				//$query = "INSERT INTO `logs`(`log_date`, `log_acct_id`, `log_repo_id`, `log_vers_id`,`log_action`) VALUES (now(),  $acct_id, $repo_id, $versid,'locked')";
				//mysql_query($query);

				$query="SELECT vers_id FROM versions WHERE vers_comp_id=(SELECT vers_comp_id FROM versions WHERE vers_id=".$_POST["lock_id"].") AND vers_type='file'";
				$result=mysql_query($query) or die(mysql_error());
				$arr=lock_convert($result);
				$len=sizeof($arr);
				for($i=0; $i<$len; $i++) {
					if(!$i) {
						lockUnlock($arr[$i]["vers_id"],$action,true);
					}
					else {
						lockUnlock($arr[$i]["vers_id"],$action,false);
					}
				}
			}
			else {
				if($arr[0]["vers_lock_acct_id"]==$_SESSION["id"] || $_SESSION["type"]=="Admin") {
					$action="unlock";
					echo "unlocked!";
					$query="SELECT vers_id, vers_repo_id FROM versions WHERE vers_id=".$_POST["lock_id"];
					$result=mysql_query($query) or die(mysql_error());
					$arr=lock_convert($result);
					$repo_id=$arr[0]['vers_repo_id'];
					$versid=$arr[0]['vers_id'];
					$acct_id=$_SESSION["id"];
					//$query = "INSERT INTO `logs`(`log_date`, `log_acct_id`, `log_repo_id`, `log_vers_id`,`log_action`) VALUES (now(),  $acct_id, $repo_id, $versid,'unlocked')";
					//mysql_query($query);

					$query="SELECT vers_id FROM versions WHERE vers_comp_id=(SELECT vers_comp_id FROM versions WHERE vers_id=".$_POST["lock_id"].") AND vers_type='file'";
					$result=mysql_query($query) or die(mysql_error());
					$arr=lock_convert($result);
					$len=sizeof($arr);
					for($i=0; $i<$len; $i++) {
						if(!$i) {
							lockUnlock($arr[$i]["vers_id"],$action,true);
						}
						else {
							lockUnlock($arr[$i]["vers_id"],$action,false);
						}
					}
				}
				else {
					echo "cannot unlock!";
				}
			}
		}
	}

	function getPath($vers_id) {
		$path="";
		$parent=$vers_id;
		do {
			$query="SELECT `vers_parent`, `vers_name`
			FROM `versions` 
			WHERE vers_id =$parent";
			$result=mysql_query($query) or die(mysql_error());
			$arr=lock_convert($result);
			$name=$arr[0]['vers_name'];
			$parent=$arr[0]['vers_parent'];
			$path="/$name$path";
		} while($parent!=0);
		return $path;
	}

	function recursiveLockUnlock($lock_id,$action) {
		//$query="SELECT * FROM versions WHERE vers_parent=".$lock_id." AND (vers_type='file' OR vers_type='dir')";
		$query="SELECT vers_id, vers_comp_id, vers_type FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions WHERE vers_parent=".$lock_id." ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a WHERE a.vers_type='file' OR a.vers_type='dir'";
		$result=mysql_query($query) or die(mysql_error());
		$temp=lock_convert($result);
		$len=sizeof($temp);
		for($i=0; $i<$len; $i++) {
			if($temp[$i]["vers_type"]=="file") {
				$query="SELECT vers_id FROM versions WHERE vers_comp_id=".$temp[$i]["vers_comp_id"]." AND vers_type='file'";
				$result=mysql_query($query) or die(mysql_error());
				$arr=lock_convert($result);
				$len2=sizeof($arr);
				for($j=0; $j<$len2; $j++) {
					if(!$j) {
						lockUnlock($arr[$j]["vers_id"],$action,true);
					}
					else {
						lockUnlock($arr[$j]["vers_id"],$action,false);
					}
				}
			}
			else {
				lockUnlockDir($temp[$i]["vers_id"],$action);
				recursiveLockUnlock($temp[$i]["vers_id"],$action);
			}
		}
	}

	function lockUnlock($lock_id,$action,$isFirst) {
		$acct_id=$_SESSION["id"];
		$query="SELECT vers_repo_id, vers_lock_acct_id, vers_repo_id FROM versions WHERE vers_id=".$lock_id;
		$result=mysql_query($query) or die(mysql_error());
		$arr=lock_convert($result);
		$repo_id=$arr[0]["vers_repo_id"];
		if($action=="lock") {
			if($arr[0]["vers_lock_acct_id"]==0) {
				$query="UPDATE versions SET vers_lock_acct_id=".$_SESSION["id"]." WHERE vers_id=".$lock_id;
				mysql_query($query) or die(mysql_error());
				if($isFirst) {
					//add to logs
					$query="SELECT repo_name FROM repositories WHERE repo_id=$repo_id";
					$result=mysql_query($query);
					$arr=lock_convert($result);
					$rname=$arr[0]["repo_name"];
					$query="SELECT acct_name FROM accounts WHERE acct_id=$acct_id";
					$result=mysql_query($query);
					$arr=lock_convert($result);
					$name=$arr[0]["acct_name"];
					$date=date("M\ d\, Y \(D\)-h\:i\:s a");
					$path=getPath($lock_id);
					$file=file_get_contents('logs.txt');
					$fp = fopen('logs.txt', 'w');
					fwrite($fp,$file);
					fwrite($fp,"$name locked $path on repository '$rname' [$date]\n");
					fclose($fp);
				}
			}
		}
		else {
			if($arr[0]["vers_lock_acct_id"]==$_SESSION["id"] || $_SESSION["type"]=="Admin") {
				$query="UPDATE versions SET vers_lock_acct_id=0 WHERE vers_id=".$lock_id;
				mysql_query($query) or die(mysql_error());
				if($isFirst) {
					//add to logs
					$query="SELECT repo_name FROM repositories WHERE repo_id=$repo_id";
					$result=mysql_query($query);
					$arr=lock_convert($result);
					$rname=$arr[0]["repo_name"];
					$query="SELECT acct_name FROM accounts WHERE acct_id=$acct_id";
					$result=mysql_query($query);
					$arr=lock_convert($result);
					$name=$arr[0]["acct_name"];
					$date=date("M\ d\, Y \(D\)-h\:i\:s a");
					$path=getPath($lock_id);
					$file=file_get_contents('logs.txt');
					$fp = fopen('logs.txt', 'w');
					fwrite($fp,$file);
					fwrite($fp,"$name unlocked $path on repository '$rname' [$date]\n");
					fclose($fp);
				}
			}
		}
	}

	function lockUnlockDir($lock_id,$action) {
		if($action=="lock") {
			$query="UPDATE versions SET vers_lock_acct_id=".$_SESSION["id"]." WHERE vers_id=".$lock_id;
		}
		else {
			$query="UPDATE versions SET vers_lock_acct_id=0 WHERE vers_id=".$lock_id;
		}
		mysql_query($query) or die(mysql_error());
	}

	function lock_convert($result) {
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
	
	function lock_connect() {
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