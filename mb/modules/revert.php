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
	if(!isset($_SESSION['user']) || !isset($_POST['version_id'])) {
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
		revert_revert($_SESSION['id']);
	}

	function getPath($vers_id) {
		$path="";
		$parent=$vers_id;
		do {
			$query="SELECT `vers_parent`, `vers_name`
			FROM `versions` 
			WHERE vers_id =$parent";
			$result=mysql_query($query) or die(mysql_error());
			$arr=revert_convert($result);
			$name=$arr[0]['vers_name'];
			$parent=$arr[0]['vers_parent'];
			$path="/$name$path";
		} while($parent!=0);
		return $path;
	}

	function revert_revert($acct_id) {
		revert_connect();
		$versid=$_POST['version_id'];
		$query="SELECT * FROM versions WHERE vers_id=".$_POST['version_id'];
		$result=mysql_query($query) or die(mysql_error());
		$arr=revert_convert($result);
		if($arr[0]["vers_lock_acct_id"]==0) {
			$comp_id=$arr[0]['vers_comp_id'];
			$repo_vers=$arr[0]['vers_repo_vers'];
			$message=$_POST['revert_message'];
			$repo_id=$arr[0]['vers_repo_id'];
			$query="INSERT INTO `versions`(`vers_acct_id`, `vers_repo_id`, `vers_repo_vers`, `vers_comp_id`, `vers_file_id`, `vers_parent`, `vers_name`, `vers_type`, `vers_date`, `vers_message`) SELECT `vers_acct_id`, `vers_repo_id`, `vers_repo_vers`, `vers_comp_id`, `vers_file_id`, `vers_parent`, `vers_name`, `vers_type`, `vers_date`, `vers_message` FROM `versions` WHERE `vers_comp_id`=$comp_id AND `vers_repo_vers`=$repo_vers";
			//echo $query."<br><br>";
			$result=mysql_query($query) or die(mysql_error());
			
			//$query = "INSERT INTO `logs`(`log_date`, `log_acct_id`, `log_repo_id`, `log_vers_id`,`log_action`) VALUES (now(),  $acct_id, $repo_id, $versid,'reverted')";
			//mysql_query($query);
			
			//add to logs
			$query="SELECT repo_name FROM repositories WHERE repo_id=$repo_id";
			$result=mysql_query($query);
			$arr=revert_convert($result);
			$rname=$arr[0]["repo_name"];
			$query="SELECT acct_name FROM accounts WHERE acct_id=$acct_id";
			$result=mysql_query($query);
			$arr=revert_convert($result);
			$name=$arr[0]["acct_name"];
			$date=date("M\ d\, Y \(D\)-h\:i\:s a");
			$path=getPath($versid);
			$file=file_get_contents('logs.txt');
			$fp = fopen('logs.txt', 'w');
			fwrite($fp,$file);
			fwrite($fp,"$name reverted $path on repository '$rname' [$date]\n");
			fclose($fp);
			
			//get latest id
			$query="SELECT max(`vers_id`) FROM `versions`";
			$result=mysql_query($query) or die(mysql_error());
			$arr=revert_convert($result);
			//echo $query."<br/><br/>";
			$latest_ins_id=$arr[0]['max(`vers_id`)'];
			//get get next version
			$query="SELECT max(`vers_repo_vers`) FROM `versions` WHERE `vers_comp_id`=$comp_id";
			$result=mysql_query($query) or die(mysql_error());
			$arr=revert_convert($result);
			//echo $query."<br/><br/>";
			$next_repo_vers=$arr[0]['max(`vers_repo_vers`)']+1;
			
			$query="UPDATE `versions` SET `vers_acct_id`=$acct_id, `vers_repo_vers`=$next_repo_vers,`vers_date`=now(),`vers_message`='(revert)$message' WHERE vers_id=$latest_ins_id";
			//echo $query."<br/><br/>";
			$result=mysql_query($query) or die(mysql_error());
			copy("upload/uploads/repositories/$repo_id/$versid","upload/uploads/repositories/$repo_id/$latest_ins_id");
		}
		else {
			echo "cannot revert!";
		}
	}
	
	function revert_convert($result) {
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
	
	function revert_connect() {
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