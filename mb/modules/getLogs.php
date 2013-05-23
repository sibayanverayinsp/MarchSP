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
	if(!isset($_POST["isCleared"]) || !isset($_SESSION["user"])) {
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
		if($_POST["isCleared"]==0) {
			$filename="logs.txt";
			if(!file_exists($filename)) {
				$fp = fopen($filename, 'w');
				fclose($fp);
			}
			$fp = fopen($filename, 'r');
			if(filesize($filename) > 0) {
				$array = explode("\n", fread($fp, filesize($filename)));
				$len=sizeof($array);
				for($i=$len-1; $i>=0; $i--) {
					if($array[$i]!="") {
					$getparts=explode(" ",$array[$i]);		
					$len2=sizeof($getparts);							
						echo "<div class='div_logs current ";
						if($getparts[1]=="committed") echo "logcommitted";
						else if($getparts[1]=="deleted") echo "logdeleted";
						else if($getparts[1]=="undeleted") echo "logundeleted";
						else if($getparts[1]=="reverted") echo "logreverted";
						else if($getparts[1]=="created") echo "logcreated";
						else if($getparts[1]=="locked") echo "loglocked";
						else if($getparts[1]=="unlocked") echo "logunlocked";
						else if($getparts[1]=="failed") echo "logfailed";
						echo"'><p class='p_logs'>".$getparts[0]." <span id='log".$getparts[1]."'><strong><em><u>".strtoupper($getparts[1])."</u></em></strong></span> ";
						for ($j=2; $j<$len2 ; $j++) {
							echo $getparts[$j]." ";
						}
						echo "</p></div>";
					}
				}
			}
			fclose($fp);
		}
		else {
			$fp = fopen('logs.txt', 'w');
			fwrite($fp,"");
			fclose($fp);
		}
	}
	
	function getPath($vers_id) {
		log_connect();
		$path="";
		$parent=$vers_id;
		do {
			$query="SELECT `vers_parent`, `vers_name`
			FROM `versions` 
			WHERE vers_id =$parent";
			$result=mysql_query($query) or die(mysql_error());
			$arr=convert($result);
			$name=$arr[0]['vers_name'];
			$parent=$arr[0]['vers_parent'];
			$path="/$name$path";
		} while($parent!=0);
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