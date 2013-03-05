<?php
	include("../query.php");
	include("../_functions.php");
	
	$rx = $_POST['repoid'];
	$px = $_POST['parent'];	
	if(!file_exists("download/files"))	
		mkdir("download\\files",0777);
	fillFolder("download\\files",$rx,$px);
	chdir("download\\files");
	createdirzip("..\\files","..\\files.zip");
	chdir("..\\..\\");
	rrmdir("download\\files");
	//downloadthe zip
	$name = "download\\files.zip";
	if(file_exists($name)){
		header('Content-type: force-download');
		header('Content-Disposition: attachment; filename="files.zip"');
		echo file_get_contents($name);		
	}else{echo "Failed to find the zip file.<br/>";}
	
	
	
	function fillFolder($path,$repo_id,$parent_id) {	
		$par=$parent_id;
		$arr = query("SELECT * from `versions` WHERE `vers_repo_id`=$repo_id AND `vers_parent`=$parent_id");
		$i=0;
		for($i=0;$i<sizeof($arr);$i++) {
			echo $arr[$i]['vers_name']." ".$arr[$i]['vers_id']." ".$arr[$i]['vers_type'];
			if($arr[$i]['vers_type']=="dir"){
				if(!file_exists($path."/".$arr[$i]['vers_name']))
					mkdir($path."/".$arr[$i]['vers_name'],0777);
				fillFolder($path."/".$arr[$i]['vers_name'],$repo_id,$arr[$i]['vers_id']);
			}
			else
			copy("upload/repositories/$repo_id/".$arr[$i]['vers_id'],$path."/".$arr[$i]['vers_name']);
			n();
		}
	}
?>