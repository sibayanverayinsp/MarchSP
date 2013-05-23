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
	if(!isset($_SESSION["user"]) || !isset($_FILES["proj"])) {
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
		include("../sqltoarray.php");
		include("../connect.php");
		include("../_functions.php");
		dbconnect();
		set_time_limit(1000000);
		if($_FILES["proj"]["error"]>0) {
			echo "An error has occured!".$_FILES["proj"]["error"]."<br>";
		}
		else {
			echo '<script type="text/javascript">alert("The file has been successfully uploaded!");</script>';
		}
		if(file_exists("uploads/".$_FILES["proj"]["name"])) {
			echo $_FILES["proj"]["name"]." already exists.";
		}
		else {
			move_uploaded_file($_FILES["proj"]["tmp_name"],"uploads/".$_FILES["proj"]["name"]);
			$path="uploads/repositories/".$_POST["hidden_repo_id"];
			$i=0;
			$endname="";
			if($_POST["hidden_parent_id"]!=0) {
				$parent=$_POST["hidden_parent_id"];
				while($parent!=0) {
					$query="SELECT vers_name, vers_parent FROM versions WHERE vers_id=".$parent;
					$result=mysql_query($query) or die(mysql_error());
					$arr_table_result=mysql_fetch_full_result_array($result);
					$parent=$arr_table_result[0]["vers_parent"];
					if($i==0) {
						$endname=$arr_table_result[0]["vers_name"];
					}
					else {
						$path=$path."/".$arr_table_result[0]["vers_name"];
					}
					$i++;
					if($i==10) {
						break;
					}
				}
				$path.="/".$endname;
			}
			$parent=$_POST["hidden_parent_id"];
			if(!file_exists("uploads/".$_POST["hidden_repo_id"])) {
				mkdir("uploads/".$_POST["hidden_repo_id"],0777);
			}
			if(!file_exists("uploads/repositories/".$_POST["hidden_repo_id"]))
				mkdir("uploads/repositories/".$_POST["hidden_repo_id"],0777);
			$zip=zip_open("uploads/".$_FILES["proj"]["name"]);
			if(is_resource($zip) && $_FILES["proj"]["type"]=="application/octet-stream") {
				extractzip("uploads/".$_FILES["proj"]["name"],"uploads/".$_POST["hidden_repo_id"]);
				zip_close($zip);
			}
			else {
				if(is_resource($zip)) zip_close($zip);
				$zip=new ZipArchive();
				$zip_name="uploads/temp.zip";
				if($zip->open($zip_name, ZIPARCHIVE::CREATE)===TRUE) {
					$zip->addFile("uploads/".$_FILES["proj"]["name"],$_FILES["proj"]["name"]);
					$zip->close();
					extractzip($zip_name,"uploads/".$_POST["hidden_repo_id"]);
					unlink($zip_name);
				}
			}
			unlink("uploads/".$_FILES["proj"]["name"]);
			dir_list("uploads/".$_POST["hidden_repo_id"],$parent,$parent);
			rrmdir("uploads/".$_POST["hidden_repo_id"]);
		}
	}
	
	function getPath($vers_id) {
		$path="";
		$parent=$vers_id;
		do {
			$query="SELECT `vers_parent`, `vers_name` FROM `versions` WHERE vers_id =$parent";
			$result=mysql_query($query) or die(mysql_error());
			$arr=mysql_fetch_full_result_array($result);
			$name=$arr[0]['vers_name'];
			$parent=$arr[0]['vers_parent'];
			$path="/$name$path";
		} while($parent!=0);
		return $path;
	}

	function dir_list($dir, $param_parent, $past_parent) {
		$result=1;
		if($dir[strlen($dir)-1]!="/") {
			$dir.="/";
		}
		if(!is_dir($dir)) {
			return array();
		}
		$dir_handle=opendir($dir);
		$dir_objects=array();
		while($object=readdir($dir_handle)) {
			if(!in_array($object,array(".",".."))) {
				$filename=$dir.$object;
				$acct_id=$_SESSION["id"];
				$parent=$param_parent;
				$repo_id=$_POST["hidden_repo_id"];
				$repo_vers=1;
				$query="SELECT MAX(vers_comp_id) FROM versions";
				$result=mysql_query($query) or die(mysql_error());
				$arr_table_result=mysql_fetch_full_result_array($result);
				$vers_comp_id=$arr_table_result[0]["MAX(vers_comp_id)"]+1;
				$name=$object;
				$type=(filetype($filename)!="file")?"dir":"file";
				$message=$_POST["message"];
				$query="SELECT * FROM versions WHERE vers_parent=".$past_parent." AND vers_name='".$name."' AND (vers_type='".$type."' OR vers_type='".$type."(del)') AND vers_repo_id=".$repo_id." ORDER BY vers_repo_vers DESC LIMIT 1";
				$result=mysql_query($query) or die(mysql_error());
				$arr_table_result=mysql_fetch_full_result_array($result);
				$dup=0;
				$same=0;
				$vers_par_id=$past_parent;
				$locked=0;
				if(sizeof($arr_table_result)>0) {
					$dup=1;
					$vers_comp_id=$arr_table_result[0]["vers_comp_id"];
					$repo_vers=$arr_table_result[0]["vers_repo_vers"]+1;
					$vers_par_id=$arr_table_result[0]["vers_parent"];
					$vers=$arr_table_result[0]["vers_id"];
					$locked=$arr_table_result[0]["vers_lock_acct_id"];
					echo "name=$name<br/>";
					if(filetype($filename)=="file") {
						if($arr_table_result[0]["vers_type"]=="file") {
							$uploadstr = file_get_contents("$dir/$name");
							$dbfilestr = file_get_contents("uploads/repositories/$repo_id/$vers");
							if(strcmp($uploadstr,$dbfilestr)==0) {
								$same=1;
							}
							else{
								$same=0;
							}
						}
						else {
							$same=0;
						}
					}
					else if(filetype($filename)=="dir") {
					$vers_par_id=$arr_table_result[0]["vers_id"];
						$same=1;
						$query="UPDATE versions SET vers_type='dir' WHERE vers_id=".$vers_par_id;
						mysql_query($query);
					}
					else {
						$same=0;
					}
				}
				
				$query="INSERT INTO versions(vers_acct_id,vers_repo_id,vers_repo_vers,vers_comp_id,vers_parent,vers_name,vers_type,vers_date,vers_message) VALUES($acct_id,$repo_id,$repo_vers,$vers_comp_id,$vers_par_id,'$name','$type',now(),'(commit)$message')";
				if($same!=1&&$locked==0){				
					$result=mysql_query($query) or die(mysql_error());					
				}
				
				$query="SELECT MAX(vers_id) FROM versions";
				$result=mysql_query($query) or die(mysql_error());
				$arr_table_result=mysql_fetch_full_result_array($result);
				$versid=$arr_table_result[0]["MAX(vers_id)"];
				
				if($same!=1&&$locked==0) {
					$query="SELECT repo_name FROM repositories WHERE repo_id=$repo_id";
					$result=mysql_query($query);
					$arr2=mysql_fetch_full_result_array($result);
					$rname=$arr2[0]["repo_name"];
					$query="SELECT acct_name FROM accounts WHERE acct_id=$acct_id";
					$result=mysql_query($query);
					$arr2=mysql_fetch_full_result_array($result);
					$fname=$arr2[0]["acct_name"];
					$date=date("M\ d\, Y \(D\)-h\:i\:s a");
					$path=getPath($versid);
					$file=file_get_contents('../logs.txt');
					$fp = fopen('../logs.txt', 'w');
					fwrite($fp,$file);
					fwrite($fp,"$fname committed $path on repository '$rname' [$date]\n");
					fclose($fp);
				}
				else if($same==1&&$locked==0) {
					$query="SELECT repo_name FROM repositories WHERE repo_id=$repo_id";
					$result=mysql_query($query);
					$arr2=mysql_fetch_full_result_array($result);
					$rname=$arr2[0]["repo_name"];
					$query="SELECT acct_name FROM accounts WHERE acct_id=$acct_id";
					$result=mysql_query($query);
					$arr2=mysql_fetch_full_result_array($result);
					$fname=$arr2[0]["acct_name"];
					$date=date("M\ d\, Y \(D\)-h\:i\:s a");
					$path=getPath($versid);
					$file=file_get_contents('../logs.txt');
					$fp = fopen('../logs.txt', 'w');
					fwrite($fp,$file);
					fwrite($fp,"$fname failed to commit $path on repository '$rname' due to similarity of content [$date]\n");
					fclose($fp);
				}
				else if($locked!=0) {
					$query="SELECT repo_name FROM repositories WHERE repo_id=$repo_id";
					$result=mysql_query($query);
					$arr2=mysql_fetch_full_result_array($result);
					$rname=$arr2[0]["repo_name"];
					$query="SELECT acct_name FROM accounts WHERE acct_id=$acct_id";
					$result=mysql_query($query);
					$arr2=mysql_fetch_full_result_array($result);
					$fname=$arr2[0]["acct_name"];
					$date=date("M\ d\, Y \(D\)-h\:i\:s a");
					$path=getPath($versid);
					$file=file_get_contents('../logs.txt');
					$fp = fopen('../logs.txt', 'w');
					fwrite($fp,$file);
					fwrite($fp,"$fname failed to commit $path on repository '$rname' due to locked file [$date]\n");
					fclose($fp);
				}
				
				if(filetype($filename)!="file") {
				echo "$filename $versid $vers_par_id $dup $same<br/>";
					if($dup==0) {
						dir_list("$filename",$versid,$versid);
					}
					else {
						dir_list("$filename",$versid,$vers_par_id);
					}
				}
				else {
					if($same!=1&&$locked==0){
						copy("$dir/$name","uploads/repositories/$repo_id/$versid");
						chmod("uploads/repositories/$repo_id/$versid",0444);
					}
				}
			}
		}
		closedir($dir_handle);
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