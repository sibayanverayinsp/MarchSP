<?php
	include("sqltoarray.php");
	include("connect.php");
	dbconnect();
	set_time_limit(100000);

	$returned=mysql_query("set names 'utf8'"); 
	$path="C:\\wamp\\www\\csweek";
	dir_list($path,0);
	function dir_list($dir,$param_parent) {
		$result=1;
		if($dir[strlen($dir)-1]!='/')
			$dir.='/';
		if(!is_dir($dir))
			return array();
		$dir_handle=opendir($dir);
		$dir_objects=array();
		while($object=readdir($dir_handle)) {
			if(!in_array($object,array('.','..'))) {
				$filename=$dir.$object;
				$acct_id=1;//session
				$parent=$param_parent;
				$repo_id=2;
				$repo_vers=1;
				$query="select max(vers_comp_id) from versions";
				$result=mysql_query($query) or die(mysql_error());
				$arr_table_result=mysql_fetch_full_result_array($result);
				$vers_comp_id=$arr_table_result[0]['max(vers_comp_id)']+1;
				$name=$object;
				$type=(filetype($filename)!='file')?"dir":"file";
				$message="message";
				$query="INSERT INTO `versions`(`vers_acct_id`,`vers_repo_id`,`vers_repo_vers`,`vers_comp_id`,`vers_parent`,`vers_name`,`vers_type`,`vers_date`,`vers_message`) VALUES($acct_id,$repo_id,$repo_vers,$vers_comp_id,$parent,'$name','$type',now(),'$message')";
				echo $query."<br/>";
				$result=mysql_query($query) or die(mysql_error());
				//params
				$query="select max(vers_id) from versions";
				$result=mysql_query($query) or die(mysql_error());
				$arr_table_result=mysql_fetch_full_result_array($result);
				if(filetype($filename)!='file') {
					dir_list("$filename",$arr_table_result[0]['max(vers_id)']);
				}
			}
		}
	}
	function mysql_fetch_full_result_array($result) {
		$table_result=array();
		$r=0;
		while($row=mysql_fetch_assoc($result)) {
			$arr_row=array();
			$c=0;
			while($c < mysql_num_fields($result)) {
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
?>