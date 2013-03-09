<?php
	session_start();
	if(!isset($_SESSION['user'])) {
		header('Location: ../');
	}
	else {
		if(isset($_POST['download_repo_id'])) {
			include("../query.php");
			include("../_functions.php");
			$rx=$_POST['download_repo_id'];
			$px=$_POST['download_parent_id'];
			$filename=$_POST['download_name'];
			if(!file_exists("downloads/files")) {
				mkdir("downloads\\files",0777);
			}
			fillFolder("downloads\\files",$rx,$px);
			chdir("downloads\\files");
			createdirzip("..\\files","..\\files.zip");
			chdir("..\\..\\");
			rrmdir("downloads\\files");
			//download the zip
			$name = "downloads\\files.zip";
			if(file_exists($name)) {
				header('Content-type: force-download');
				header('Content-Disposition: attachment; filename="files.zip"');
				echo file_get_contents($name);
				unlink($name);
			}
			else {
				echo "Failed to find the zip file.<br/>";
			}
		}
	}
	
	function fillFolder($path,$repo_id,$parent_id) {	
		$par=$parent_id;
		$arr=query("SELECT * from versions WHERE vers_repo_id=$repo_id AND vers_parent=$parent_id");
		$i=0;
		for($i=0;$i<sizeof($arr);$i++) {
			echo $arr[$i]['vers_name']." ".$arr[$i]['vers_id']." ".$arr[$i]['vers_type'];
			if($arr[$i]['vers_type']=="dir") {
				if(!file_exists($path."/".$arr[$i]['vers_name'])) {
					mkdir($path."/".$arr[$i]['vers_name'],0777);
				}
				fillFolder($path."/".$arr[$i]['vers_name'],$repo_id,$arr[$i]['vers_id']);
			}
			else {
				copy("../upload/uploads/repositories/$repo_id/".$arr[$i]['vers_id'],$path."/".$arr[$i]['vers_name']);
				chmod($path."/".$arr[$i]['vers_name'],0777);
			}
			n();
		}
	}
?>