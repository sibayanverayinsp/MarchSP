<?php
	session_start();
	if(!isset($_SESSION["user"])) {
		header("Location: ../login");
	}
	else {
		if(isset($_POST["vers_id"])) {
			include("query.php");
			$arr=query("SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions WHERE vers_parent=".$_POST["vers_id"]." ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name");
		}
		else {
			header("Location: ../login");
		}
	}
?>
<div class="vers<?php echo $_POST["vers_id"]?>" style="padding-left:10px;"></div>
<script type="text/javascript">
	addRow(<?php echo json_encode($arr); ?>,"dir");
</script>