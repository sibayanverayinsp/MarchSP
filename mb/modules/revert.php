<?php
	//revert_revert(2,168,1,"reverting files");
	session_start();
	if(!isset($_SESSION['user'])) {
		header('Location: ../');
	}
	else {
		if(isset($_POST['version_id'])) {
			revert_revert($_SESSION['id']);
		}
	}
	function revert_revert($acct_id) {
		revert_connect();
		$versid=$_POST['version_id'];
		$query="SELECT * FROM versions WHERE vers_id=".$_POST['version_id'];
		$result=mysql_query($query) or die(mysql_error());
		$arr=revert_convert($result);
		$comp_id=$arr[0]['vers_comp_id'];
		$repo_vers=$arr[0]['vers_repo_vers'];
		$message=$_POST['revert_message'];
		$repo_id=$arr[0]['vers_repo_id'];
		$query="INSERT INTO `versions`(`vers_acct_id`, `vers_repo_id`, `vers_repo_vers`, `vers_comp_id`, `vers_file_id`, `vers_parent`, `vers_name`, `vers_type`, `vers_date`, `vers_message`) SELECT `vers_acct_id`, `vers_repo_id`, `vers_repo_vers`, `vers_comp_id`, `vers_file_id`, `vers_parent`, `vers_name`, `vers_type`, `vers_date`, `vers_message` FROM `versions` WHERE `vers_comp_id`=$comp_id AND `vers_repo_vers`=$repo_vers";
		echo $query."<br><br>";
		$result=mysql_query($query) or die(mysql_error());
		
		//get latest id
		$query="SELECT max(`vers_id`) FROM `versions`";
		$result=mysql_query($query) or die(mysql_error());
		$arr=revert_convert($result);
		echo $query."<br/><br/>";
		$latest_ins_id=$arr[0]['max(`vers_id`)'];
		//get get next version
		$query="SELECT max(`vers_repo_vers`) FROM `versions` WHERE `vers_comp_id`=$comp_id";
		$result=mysql_query($query) or die(mysql_error());
		$arr=revert_convert($result);
		echo $query."<br/><br/>";
		$next_repo_vers=$arr[0]['max(`vers_repo_vers`)']+1;
		
		$query="UPDATE `versions` SET `vers_acct_id`=$acct_id, `vers_repo_vers`=$next_repo_vers,`vers_date`=now(),`vers_message`='(revert)$message' WHERE vers_id=$latest_ins_id";
		echo $query."<br/><br/>";
		$result=mysql_query($query) or die(mysql_error());
		copy("upload/uploads/repositories/$repo_id/$versid","upload/uploads/repositories/$repo_id/$latest_ins_id");
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