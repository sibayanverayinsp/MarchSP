<?php
	session_start();
	if(!isset($_SESSION['user'])) {
		header('Location: ../');
	}
	else {
		if(isset($_FILES['proj'])) {
			include("../sqltoarray.php");
			include("../_functions.php");
			include("../connect.php");
			include("../log/log.php");
		
			dbconnect();
			
			set_time_limit(1000000);
			if($_FILES["proj"]["error"] > 0)
				echo "An error has occured!".$_FILES["proj"]["error"]."<br/ >";
			else {
				echo '<script language = "javascript">
				alert("The file has been successfully uploaded!");
				</script>';
			}
			if (file_exists("uploads/" . $_FILES["proj"]["name"])){ //checks if the file already exists in the directory
				echo $_FILES["proj"]["name"] . " already exists. ";
			}
			else {
				move_uploaded_file($_FILES["proj"]["tmp_name"], "uploads/".$_FILES["proj"]["name"]);
				//echo "Stored in: " . "upload/" . $_FILES["proj"]["name"];
				$path="uploads\\repositories\\$_POST[hidden_repo_id]";
				$i=0;
				$endname="";
				if($_POST['hidden_parent_id']!=0) {
					$parent=$_POST['hidden_parent_id'];
					echo $parent;
					while($parent!=0) {
						$query="select vers_name, vers_parent from versions where vers_id = $parent";
						echo $query;
						$result=mysql_query($query) or die(mysql_error());
						$arr_table_result=mysql_fetch_full_result_array($result);
						print_r($arr_table_result);
						$parent = $arr_table_result[0]['vers_parent'];
						if($i==0)
							$endname = $arr_table_result[0]['vers_name'];
						else
							$path = $path."\\".$arr_table_result[0]['vers_name'];
					$i++;
					if($i==10)break;
					}
					$path .= "\\".$endname;
					echo $path;
				}
				
				$parent=$_POST['hidden_parent_id'];
				echo "<br/><br/>$path<br/><br/>$parent<br/><br/>";
				if(!file_exists("uploads\\$_POST[hidden_repo_id]")) {
					mkdir("uploads\\$_POST[hidden_repo_id]",0777);
				}
				if(!file_exists("uploads\\repositories\\$_POST[hidden_repo_id]"))
					mkdir("uploads\\repositories\\$_POST[hidden_repo_id]",0777);
				$zip=zip_open("uploads\\".$_FILES["proj"]["name"]);
				if(is_resource($zip) && $_FILES['proj']['type']=="application/octet-stream") {
					zip_close($zip);
					extractzip("uploads\\".$_FILES["proj"]["name"], "uploads\\$_POST[hidden_repo_id]");
				}
				else {
					zip_close($zip);
					$zip = new ZipArchive(); // Load zip library 
					$zip_name = "uploads\\temp.zip"; // Zip name
					if($zip->open($zip_name, ZIPARCHIVE::CREATE)===TRUE) {
						$zip->addFile("uploads\\".$_FILES["proj"]["name"],$_FILES["proj"]["name"]);
						$zip->close();
						extractzip($zip_name, "uploads\\$_POST[hidden_repo_id]");
						unlink($zip_name);//delete the zip
					}
				}
				unlink("uploads\\".$_FILES["proj"]["name"]);//delete the zip
				dir_list("uploads\\$_POST[hidden_repo_id]",$parent,$parent);
				rrmdir("uploads\\$_POST[hidden_repo_id]");
				//mysql_close($link);
				addLog($_SESSION['id'],$_POST['hidden_repo_id'],$parent, "Commit");
			}
		}
		else {
			header('Location: ../');
		}
	}
	
	//functions
	function dir_list($dir,$param_parent,$past_parent) {
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
				$acct_id=$_SESSION['id'];//session
				$parent=$param_parent;
				$repo_id=$_POST['hidden_repo_id'];
				$repo_vers=1;
				$query="select max(vers_comp_id) from versions";
				$result=mysql_query($query) or die(mysql_error());
				$arr_table_result=mysql_fetch_full_result_array($result);
				$vers_comp_id=$arr_table_result[0]['max(vers_comp_id)']+1;
				$name=$object;
				$type=(filetype($filename)!='file')?"dir":"file";
				$message=$_POST['message'];
								
				$query = "SELECT * FROM versions WHERE vers_parent='$past_parent' AND vers_name='$name' AND vers_type='$type' AND vers_repo_id='$repo_id' ORDER BY vers_repo_vers DESC";
				echo $query."<br/><br/>";
				$result=mysql_query($query) or die(mysql_error());
				$arr_table_result=mysql_fetch_full_result_array($result);
				print_r($arr_table_result);
				echo "<br/>";
				$dup=0;
				if(sizeof($arr_table_result)>0) {	
					$dup=1;
					echo "duplicate found!";
					$vers_comp_id=$arr_table_result[0]['vers_comp_id'];
					$repo_vers=$arr_table_result[0]['vers_repo_vers']+1;
					$vers_par_id=$arr_table_result[0]['vers_id'];
				}		
				//params
				$query="select max(vers_id) from versions";
				$result=mysql_query($query) or die(mysql_error());
				$arr_table_result=mysql_fetch_full_result_array($result);
				if(sizeof($arr_table_result)>0)
					$vers_file_id=$arr_table_result[0]['max(vers_id)']+1;
				else
					$vers_file_id=1;
				$query="INSERT INTO versions(vers_acct_id,vers_repo_id,vers_repo_vers,vers_comp_id,vers_file_id,vers_parent,vers_name,vers_type,vers_date,vers_message) VALUES($acct_id,$repo_id,$repo_vers,$vers_comp_id,$vers_file_id,$parent,'$name','$type',now(),'$message')";
				echo $query."<br/>";
				$result=mysql_query($query) or die(mysql_error());				
				//params
				$query="select max(vers_id) from versions";
				$result=mysql_query($query) or die(mysql_error());
				$arr_table_result=mysql_fetch_full_result_array($result);
				$versid=$arr_table_result[0]['max(vers_id)'];
				if(filetype($filename)!='file') {
					if($dup==0)
						dir_list("$filename",$versid,$versid);
					else
						dir_list("$filename",$versid,$vers_par_id);				
				}
				else {
					echo "from: uploads\\$repo_id\\$name <br/>to uploads\\repositories\\$repo_id\\$vers_file_id";
					echo "<br/>dir: $dir <br/>";
					copy("$dir\\$name","uploads\\repositories\\$repo_id\\$vers_file_id");
					chmod("uploads\\repositories\\$repo_id\\$vers_file_id",0444);
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