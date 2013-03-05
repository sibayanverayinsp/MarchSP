<?php
	session_start();
	if(!isset($_SESSION['user'])) {
		header('Location: ../login');
	}
	else {
		if(isset($_POST['vers_id'])) {
			include("query.php");
			$arr=query("SELECT *, MAX(vers_repo_vers) FROM (SELECT * FROM versions WHERE vers_parent=".$_POST['vers_id']." ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name");
		}
		else {
			header('Location: ../');
		}
	}
?>
<table class="vers<?php echo $_POST['vers_id']?>" style="width:97%;float:right;"></table>
<script type="text/javascript">
	addRow(<?php echo json_encode($arr); ?>,"dir");
</script>