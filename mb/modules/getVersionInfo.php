<?php
	include("query.php");
	$arr=query("SELECT * FROM versions WHERE vers_id=$_POST[ver_id]");
?>
<p class="info"></p>
<script type="text/javascript">
	getInfo(<?php echo json_encode($arr); ?>);
</script>