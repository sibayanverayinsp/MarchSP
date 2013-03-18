<?php
	session_start();
	if(!isset($_SESSION["user"])) {
		header("Location: ../login");
	}
	else {
		if(isset($_POST["hidden_id"])) {
			include("query.php");
			$arr=query("SELECT vers_repo_id FROM versions WHERE vers_id=".$_POST["hidden_id"]."");
			$arr2=query("SELECT MAX(vers_comp_id)+1 FROM versions");
			query("INSERT INTO versions(vers_acct_id,vers_repo_id,vers_repo_vers,vers_comp_id,vers_parent,vers_name,vers_type,vers_date,vers_message) VALUES(".$_SESSION["id"].",".$arr[0]["vers_repo_id"].",1,".$arr2[0]["MAX(vers_comp_id)+1"].",".$_POST["hidden_id"].",'".$_POST["folder"]."','dir',now(),'message')");
			$arr=query("SELECT * FROM versions WHERE vers_id IN(SELECT MAX(vers_id) FROM versions)");
		}
	}
?>
<script type="text/javascript">
	addRow(<?php echo json_encode($arr); ?>,"dir");
</script>
<?php
	header("Location: ../login");
?>