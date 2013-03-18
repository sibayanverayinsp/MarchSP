<?php
	session_start();
	if(!isset($_SESSION['user'])) {
		header('Location: ../login');
	}
	else {
		if(isset($_POST['repo_id'])) {
			include("query.php");
			$arr=query("SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions WHERE vers_parent=0 AND vers_repo_id=".$_POST['repo_id']." ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name");
		}
		else {
			header('Location: ../');
		}
	}
?>
<div class="cmp"></div>
<script type="text/javascript">
	addRow(<?php echo json_encode($arr); ?>,"cmp");
</script>