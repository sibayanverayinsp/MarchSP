<?php
	include("query.php");
	$arr=query("SELECT * FROM versions WHERE vers_comp_id=$_POST[ver_class] AND vers_repo_vers NOT IN (SELECT MAX(vers_repo_vers) FROM versions WHERE vers_comp_id=$_POST[ver_class])");
?>
<table class="vers"></table>
<script type="text/javascript">
	addRow(<?php echo json_encode($arr); ?>,"vers");
</script>