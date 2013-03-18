<?php
//getLogs();
$mes=getLogs();
$len=sizeof($mes);
for($i=0; $i<$len; $i++) {
	echo "<div class='div_logs current'><p class='p_logs'>".$mes[$i]."</p></div>";
}

//echo "<pre>".htmlspecialchars(print_r($mes,true))."</pre>\r\n";	
	function getLogs() {
		log_connect();
		$query = "SELECT * 
			FROM  `logs` ,  `accounts` ,  `repositories`, `versions` 
			WHERE log_repo_id = repo_id
			AND log_acct_id = acct_id
			AND log_vers_id = vers_id
			ORDER BY log_date DESC";
		$result=mysql_query($query) or die(mysql_error());
		$arr = convert($result);
		$s=sizeof($arr);
		$logs=array();
		for($i=0;$i<$s;$i++){
			$name=$arr[$i]['acct_name'];
			$act=$arr[$i]['log_action'];
			$rname=$arr[$i]['repo_name'];
			$path=getPath($arr[$i]['vers_id']);
			$date=date("M\ d\, Y \(D\)-h\:i\:s a",strtotime($arr[$i]["vers_date"]));
			if($act=="added repository")
				$logs[$i]="$name $act '$rname' [$date]";
			else
				$logs[$i]="$name $act $path on repository '$rname' [$date]";
		}
		return $logs;
	}
	
	function getPath($vers_id){
		log_connect();
		$path="";
		$parent=$vers_id;
		do{
			$query="SELECT `vers_parent`, `vers_name`
			FROM  `versions` 
			WHERE vers_id =$parent";
			$result=mysql_query($query) or die(mysql_error());
			$arr = convert($result);
			$name=$arr[0]['vers_name'];
			$parent=$arr[0]['vers_parent'];
			$path="/$name$path";
		}while($parent!=0);
		return $path;
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