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

	function getTexts($repo_id, $file1id) {
		$data=file_get_contents("upload/uploads/repositories/".$repo_id."/".$file1id);
		$line=explode(" ",$data);
		$text="";
		for($i=0; $i<count($line); $i++) {
			$text.=htmlspecialchars($line[$i])." ";
		}
		$text=trim($text);
		return $text;
	}
	session_start();
	if(!isset($_SESSION["user"]) || !isset($_POST["qv_file"])) {
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
		$str=getTexts($_POST["qv_repo_id"],$_POST["qv_file"]);
		$string='<link rel="stylesheet" type="text/css" href="../css/prettify.css"><script src="../js/prettify.js"></script><body onload="prettyPrint()"><pre style="word-wrap:break-word;" class="prettyprint linenums">'.$str.'</pre></body>';
		file_put_contents("iframe.html",$string);
	}
?>