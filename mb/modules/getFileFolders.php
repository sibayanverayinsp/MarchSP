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
	if(!isset($_SESSION['user']) || !isset($_POST['repo_id'])) {
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
		include("query.php");
		$arr=query("SELECT * FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions WHERE vers_parent=0 AND vers_repo_id=".$_POST['repo_id']." ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a WHERE a.vers_type='file' OR a.vers_type='dir'");
	}
?>
<div class="cmp"></div>
<script type="text/javascript">
	addRow(<?php echo json_encode($arr); ?>,"cmp");
</script>