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
	if(!isset($_SESSION['user']) || !isset($_POST["download_repo_id"])) {
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
		include("../query.php");
		include("../_functions.php");
		$rx=$_POST['download_repo_id'];
		$px=$_POST['download_parent_id'];
		$filename=$_POST['download_name'];
		if($_POST['is_dir']=="false") {
			if(!file_exists("downloads/files")) {
				mkdir("downloads\\files",0777);
			}
			$filename=fillFolder("downloads\\files",$rx,$px);
			$name="downloads\\files\\".$filename;
			if(file_exists($name)) {
				header('Content-type: '.getMimetype(($pos = strrpos($filename,'.')) !== false ? substr($filename,$pos+1) : null));
				header('Content-disposition: attachment; filename="'.$filename.'"');
				$f=fopen($name, "rb");
				fpassthru($f);
				fclose($f);
				rrmdir("downloads\\files");
			}
			else {
				echo "Failed to find the file.<br>";
			}
		}
		else {
			if(!file_exists("downloads/$filename")) {
				mkdir("downloads\\$filename",0777);
			}
			fillFolder("downloads\\$filename",$rx,$px);
			chdir("downloads");
			createdirzip("$filename","$filename.zip");
			chdir("..\\");
			rrmdir("downloads\\$filename");
			//download the zip
			$name="downloads\\$filename.zip";
			if(file_exists($name)) {
				header('Content-type: '.getMimetype(($pos = strrpos($filename.'.zip','.')) !== false ? substr($filename.'.zip',$pos+1) : null));
				header('Content-disposition: attachment; filename="'.$filename.'.zip"');
				readfile($filename.".zip");
				echo file_get_contents($name);
				unlink($name);
			}
			else {
				echo "Failed to find the zip file.<br>";
			}
		}
	}
	
	function fillFolder($path,$repo_id,$parent_id) {	
		$par=$parent_id;
		if($_POST['is_dir']=="true") {
			$arr=query("SELECT * FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions WHERE vers_repo_id=$repo_id AND vers_parent=".$parent_id." ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a WHERE a.vers_type='file' OR a.vers_type='dir'");
		}
		else {
			$arr=query("SELECT * from versions WHERE vers_repo_id=$repo_id AND vers_id=$parent_id");
		}
		$i=0;
		$len=sizeof($arr);
		for($i=0;$i<$len;$i++) {
			//echo $arr[$i]['vers_name']." ".$arr[$i]['vers_id']." ".$arr[$i]['vers_type'];
			if($arr[$i]['vers_type']=="dir") {
				if(!file_exists($path."/".$arr[$i]['vers_name'])) {
					mkdir($path."/".$arr[$i]['vers_name'],0777);
				}
				fillFolder($path."/".$arr[$i]['vers_name'],$repo_id,$arr[$i]['vers_id']);
			}
			else {
				copy("../upload/uploads/repositories/$repo_id/".$arr[$i]['vers_id'],$path."/".$arr[$i]['vers_name']);
				chmod($path."/".$arr[$i]['vers_name'],0777);
				if($_POST['is_dir']=="false") {
					return $arr[$i]['vers_name'];
				}
			}
			n();
		}
	}

	function getMimetype($ext) {
		$mimetypes = array(
			"3ds"=>"application/x-3ds",
			"ac3"=>"audio/ac3",
			"ace"=>"application/x-ace",
			"afm"=>"application/octet-stream",
			"aiff"=>"audio/x-aiff",
			"arj"=>"application/arj",
			"asf"=>"video/x-ms-asf",
			"au"=>"audio/basic",
			"avi"=>"video/avi",
			"bmp"=>"image/bmp",
			"bz2"=>"application/x-bzip2",
			"c"=>"text/plain",
			"cab"=>"vnd.ms-cab-compressed",
			"cc"=>"text/plain",
			"css"=>"text/css",
			"csv"=>"text/csv",
			"deb"=>"application/x-deb",
			"doc"=>"application/msword", 
			"docx"=>"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
			"dot"=>"application/msword",
			"dwg"=>"model/vnd.dwg",
			"dxf"=>"image/vnd.dwg",
			"eps"=>"application/postscript",
			"exe"=>"application/octet-stream",
			"gif"=>"image/gif",
			"gz"=>"application/x-gzip",
			"h"=>"text/plain",
			"htm"=>"text/html",
			"html"=>"text/html",
			"ics"=>"text/calendar",
			"jar"=>"application/java-archive",
			"java"=>"text/plain",
			"jpeg"=>"image/jpeg", 
			"jpg"=>"image/jpeg",
			"kpr"=>"application/vnd.kde.kpresenter",
			"ksp"=>"application/vnd.kde.kspread",
			"kwd"=>"application/vnd.kde.kword",
			"js"=>"text/javascript",
			"lha"=>"application/lha",
			"log"=>"text/plain",
			"mid"=>"audio/midi",
			"midi"=>"audio/midi",
			"mov"=>"video/quicktime",
			"mp3"=>"audio/mpeg3",
			"mpeg"=>"video/mpeg", 
			"mpg"=>"video/mpeg",
			"odp"=>"application/vnd.oasis.opendocument.presentation",
			"ods"=>"application/vnd.oasis.opendocument.spreadsheet",
			"odt"=>"application/vnd.oasis.opendocument.text",
			"ogg"=>"audio/ogg",
			"pcf"=>"application/x-font-pcf",
			"pdb"=>"application/vnd.palm",
			"pdf"=>"application/pdf",
			"php"=>"text/php",
			"png"=>"image/png",
			"ppm"=>"image/x-portable-pixmap",
			"ppt"=>"application/mspowerpoint",
			"pptx"=>"application/vnd.openxmlformats-officedocument.presentationml.presentation",
			"prc"=>"application/vnd.palm",
			"ps"=>"application/postscript",
			"psd"=>"image/vnd.adobe.photoshop",
			"rar"=>"application/x-rar-compressed",
			"rm"=>"audio/x-pn-realaudio",
			"rpm"=>"audio/x-pn-realaudio-plugin",
			"rtf"=>"text/rtf",
			"sdc"=>"application/vnd.stardivision.calc",
			"sdd"=>"application/vnd.stardivision.impress",
			"sdw"=>"application/vnd.stardivision.writer",
			"sql"=>"text/plain",
			"svg"=>"image/svg+xml",
			"tar"=>"application/x-tar",
			"tex"=>"application/x-tex",
			"tif"=>"image/tiff",
			"tiff"=>"image/tiff",
			"ttf"=>"application/octet-stream",
			"txt"=>"text/plain",
			"wav"=>"audio/wav",
			"wmv"=>"video/x-ms-wmv",
			"xcf"=>"image/xcf",
			"xls"=>"application/msexcel",
			"xlsx"=>"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
			"xml"=>"text/xml",
			"xpm"=>"image/x-xbitmap",
			"zip"=>"application/zip"
		);
		return $mimetypes[$ext];
	}
?>