<?php
	include("query.php");
	query("INSERT INTO repositories(repo_name,repo_creator) VALUES('".$_POST['repoName']."',1)");
	$arr=query("SELECT * FROM repositories WHERE repo_id IN (SELECT MAX( repo_id ) FROM repositories)");
?>
<script type="text/javascript">
	addRow(<?php echo json_encode($arr); ?>,"rep");
</script>
<?php
	header('Location: ../');
?>