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
	if(!isset($_SESSION["user"]) || !isset($_POST["ver_class"])) {
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
		$arr=query("SELECT * FROM versions, accounts WHERE vers_comp_id=".$_POST["ver_class"]." AND vers_repo_id=".$_POST["ver_repo_id"]." AND acct_id=vers_acct_id ORDER BY vers_repo_vers DESC");
		$len=sizeof($arr);
		for($i=0; $i<$len; $i++) {
			$arr[$i]["vers_date"]=date("M\ d\, Y \(D\)-h\:i\:s a",strtotime($arr[$i]["vers_date"]));
		}
		?>
		<p style="font-weight:bolder;">History of "<?php echo $_POST["ver_name"];?>" <span class="isLocked"><?php if($arr[0]["vers_lock_acct_id"]!=0) echo "(Locked)"; ?></span></p>
		<div class="table_vers">
			<table class="vers">
				<tr class="header">
					<td colspan="2" class="diff_td"><a class="diff_a">Diff</a></td>
					<td>Version</td>
					<td>Date</td>
					<td>User</td>
					<td>Comment</td>
					<td colspan="2">Actions</td>
				</tr>
			</table>
		</div>
		<div class="iframe_vers">
			<iframe id="diff_iframe" name="diff_iframe"></iframe>
		</div>
		<div id="try_diff">
			<div id="try_diff_msg">Try this button!</div>
		</div>
		<script type="text/javascript">prettyPrint();</script>
		<script type="text/javascript">
			$("#try_diff").effect("bounce",{times:3},1000,function() {
				$("#try_diff").fadeOut(2000);
			});
			addRow(<?php echo json_encode($arr); ?>,"vers");
		</script>
		<?php
	}
?>