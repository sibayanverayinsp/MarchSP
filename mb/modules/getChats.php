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
	if(!isset($_SESSION["user"]) || !isset($_POST["valid"])) {
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
		$filename="chat.txt";
		if(!file_exists($filename)) {
			$fp = fopen($filename, 'w');
			fclose($fp);
		}
		$fp = fopen($filename, 'r');
		if(filesize($filename) > 0) {
			$array = explode("\n", fread($fp, filesize($filename)));
			$len=sizeof($array);
			for($i=0; $i<$len; $i++) {
				if($array[$i]!="") {
					if($i%2==0) {
						?>
						<div class="ui-chatbox-msg" style=""><b><?php echo $array[$i]; ?>: </b>
						<?php
					}
					else {
						?>
						<span><?php echo $array[$i]; ?></span></div>
						<?php
					}
					?>
					<?php
				}
			}
		}
		fclose($fp);
	}
?>