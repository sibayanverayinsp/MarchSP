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
	if(!isset($_SESSION['user']) || (!isset($_POST['del_version_id']) && !isset($_POST['undel_version_id']))) {
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
		if(isset($_POST['del_version_id'])) {
			delete_delete($_SESSION['id']);
		}
		else if(isset($_POST['undel_version_id'])) {
			undelete($_POST['undel_version_id']);
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
			$arr=delete_convert($result);
			$name=$arr[0]['vers_name'];
			$parent=$arr[0]['vers_parent'];
			$path="/$name$path";
		} while($parent!=0);
		return $path;
	}

	function undelete($versid) {
		delete_connect();
		$query="SELECT vers_repo_id, vers_id, vers_comp_id, vers_repo_vers
			FROM versions
			WHERE vers_comp_id = ( 
			SELECT vers_comp_id
			FROM versions
			WHERE vers_id =$versid ) 
			ORDER BY vers_repo_vers DESC 
			LIMIT 2";
		$result=mysql_query($query) or die(mysql_error());
		$arr=delete_convert($result);
		$to=$arr[1]['vers_id'];
		$comp_id=$arr[1]['vers_comp_id'];
		$repo_id=$arr[1]['vers_repo_id'];
		$repo_vers=$arr[1]['vers_repo_vers'];
		$message="";

		//add to logs
		$query="SELECT repo_name FROM repositories WHERE repo_id=$repo_id";
		$result=mysql_query($query);
		$arr=delete_convert($result);
		$rname=$arr[0]["repo_name"];
		$query="SELECT acct_name FROM accounts WHERE acct_id=".$_SESSION["id"];
		$result=mysql_query($query);
		$arr=delete_convert($result);
		$name=$arr[0]["acct_name"];
		$date=date("M\ d\, Y \(D\)-h\:i\:s a");
		$path=getPath($to);
		$file=file_get_contents('logs.txt');
		$fp = fopen('logs.txt', 'w');
		fwrite($fp,$file);
		fwrite($fp,"$name undeleted $path on repository '$rname' [$date]\n");
		fclose($fp);

		$query="INSERT INTO `versions`(`vers_acct_id`, `vers_repo_id`, `vers_repo_vers`, `vers_comp_id`, `vers_file_id`, `vers_parent`, `vers_name`, `vers_type`, `vers_date`, `vers_message`) SELECT `vers_acct_id`, `vers_repo_id`, `vers_repo_vers`, `vers_comp_id`, `vers_file_id`, `vers_parent`, `vers_name`, `vers_type`, `vers_date`, `vers_message` FROM `versions` WHERE `vers_comp_id`=$comp_id AND `vers_repo_vers`=$repo_vers";
		//echo $query."<br><br>";
		$result=mysql_query($query) or die(mysql_error());
		
		//get latest id
		$query="SELECT max(`vers_id`) FROM `versions`";
		$result=mysql_query($query) or die(mysql_error());
		$arr=delete_convert($result);
		//echo $query."<br/><br/>";
		$latest_ins_id=$arr[0]['max(`vers_id`)'];
		//get get next version
		$query="SELECT max(`vers_repo_vers`) FROM `versions` WHERE `vers_comp_id`=$comp_id";
		$result=mysql_query($query) or die(mysql_error());
		$arr=delete_convert($result);
		//echo $query."<br/><br/>";
		$next_repo_vers=$arr[0]['max(`vers_repo_vers`)']+1;
		
		$query="UPDATE `versions` SET `vers_acct_id`=".$_SESSION['id'].", `vers_repo_vers`=$next_repo_vers,`vers_date`=now(),`vers_message`='(undelete)$message' WHERE vers_id=$latest_ins_id";
		//echo $query."<br/><br/>";
		$result=mysql_query($query) or die(mysql_error());
		copy("upload/uploads/repositories/$repo_id/$to","upload/uploads/repositories/$repo_id/$latest_ins_id");
		$query="SELECT `vers_parent`
			FROM `versions` 
			WHERE vers_id =$latest_ins_id";
		$result=mysql_query($query) or die(mysql_error());
		$arr=delete_convert($result);
		$parent=$arr[0]['vers_parent'];
		//echo $parent;
		undeletepath($to);
	}
	
	function undeletepath($vers_id){
		delete_connect();
		$path="";
		$parent=$vers_id;
		do{
		//echo $parent."<br\>";
			$query="SELECT `vers_parent`, `vers_name`
			FROM `versions` 
			WHERE vers_id =$parent";
			$result=mysql_query($query) or die(mysql_error());
			$arr=delete_convert($result);
			$name=$arr[0]['vers_name'];
			$parent=$arr[0]['vers_parent'];
			$path="/$name$path";
			$query="UPDATE versions SET vers_type='dir' WHERE vers_id =$parent";
			mysql_query($query);
		}while($parent!=0);
		return $path;
	}

	function delete_delete($acct_id) {
		delete_connect();
		//get comp_id
		$query="SELECT vers_type, vers_repo_id, vers_id FROM versions WHERE vers_id=".$_POST['del_version_id'];
		$result=mysql_query($query) or die(mysql_error());
		$arr=delete_convert($result);
		if($arr[0]['vers_type']=="dir") {
			deleteDir($_POST['del_version_id']);
			recursiveDelete($_POST['del_version_id'],$acct_id);
		}
		else {
			deleteFile($_POST["del_version_id"],$acct_id);
		}
	}

	function recursiveDelete($id,$acct_id) {
		$query="SELECT * FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions WHERE vers_parent=".$id." ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a WHERE a.vers_type='file' OR a.vers_type='dir'";
		$result=mysql_query($query) or die(mysql_error());
		$arr=delete_convert($result);
		$len=sizeof($arr);
		for($i=0; $i<$len; $i++) {
			if($arr[$i]["vers_type"]=="file") {
				deleteFile($arr[$i]["vers_id"],$acct_id);
			}
			else {
				deleteDir($arr[$i]["vers_id"]);
				recursiveDelete($arr[$i]["vers_id"],$acct_id);
			}
		}
	}

	function deleteDir($dirid) {
		$query = "UPDATE  `versions` SET `vers_type` =  'dir(del)' WHERE vers_id =$dirid";
		mysql_query($query);
	}

	function deleteFile($fileid,$acct_id) {
		$query="SELECT * FROM versions WHERE vers_comp_id = (SELECT vers_comp_id FROM  `versions` WHERE vers_id = ".$fileid.") ORDER BY vers_id DESC LIMIT 1";
		$result=mysql_query($query) or die(mysql_error());
		$arr=delete_convert($result);
		echo $query."<br/><br/>";
		$repo_vers=$arr[0]['vers_repo_vers']+1;
		echo $repo_vers;
		$repo_id=$arr[0]['vers_repo_id'];
		$versid=$arr[0]['vers_id'];
		//$query = "INSERT INTO `logs`(`log_date`, `log_acct_id`, `log_repo_id`, `log_vers_id`,`log_action`) VALUES (now(),  $acct_id, $repo_id, $versid,'deleted')";
		//mysql_query($query);

		//add to logs
		$query="SELECT repo_name FROM repositories WHERE repo_id=$repo_id";
		$result=mysql_query($query);
		$arr=delete_convert($result);
		$rname=$arr[0]["repo_name"];
		$query="SELECT acct_name FROM accounts WHERE acct_id=$acct_id";
		$result=mysql_query($query);
		$arr=delete_convert($result);
		$name=$arr[0]["acct_name"];
		$date=date("M\ d\, Y \(D\)-h\:i\:s a");
		$path=getPath($versid);
		$file=file_get_contents('logs.txt');
		$fp = fopen('logs.txt', 'w');
		fwrite($fp,$file);
		fwrite($fp,"$name deleted $path on repository '$rname' [$date]\n");
		fclose($fp);

		$query = "INSERT INTO `versions`(`vers_acct_id`, `vers_repo_id`, `vers_repo_vers`, `vers_comp_id`, `vers_file_id`, `vers_parent`, `vers_name`, `vers_type`) SELECT `vers_acct_id`, `vers_repo_id`, `vers_repo_vers`, `vers_comp_id`, `vers_file_id`, `vers_parent`, `vers_name`, `vers_type` FROM `versions` WHERE `vers_id`=".$fileid;
		echo $query;
		mysql_query($query);
		$id = mysql_insert_id();
		echo $id;
		$query = "UPDATE  `versions` SET `vers_acct_id` =$acct_id,
		`vers_repo_vers` =$repo_vers,
		`vers_type` =  'file(del)',
		`vers_date` = NOW( ) ,
		`vers_message` = '(delete)$_POST[delete_message]' WHERE vers_id =$id";
		echo $query;
		mysql_query($query);
	}
	
	function delete_convert($result) {
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
	
	function delete_connect() {
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