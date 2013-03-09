<?php
	session_start();
	if(!isset($_SESSION['user'])) {
		header('Location: ../login');
	}
	else {
		if(isset($_POST['ver_class'])) {
			include("query.php");
			$arr=query("SELECT * FROM versions, accounts WHERE vers_comp_id=".$_POST['ver_class']." AND vers_repo_id=".$_POST['ver_repo_id']." AND acct_id=vers_acct_id ORDER BY vers_repo_vers DESC");
			for($i=0;$i<sizeof($arr);$i++) {
				$arr[$i]["vers_date"]=date("M\ d\, Y \(D\) - h\:i\:s a",strtotime($arr[$i]["vers_date"]));
			}
		}
		else {
			header('Location: ../');
		}
	}
?>
<p style="font-weight:bolder;">History of "<?php echo $_POST['ver_name'];?>"</p>
<div class="table_vers">
	<table class="vers">
		<tr class="header">
			<td colspan="2"><a class="diff_a">Diff</a></td>
			<td>Version</td>
			<td>Name</td>
			<td>Date</td>
			<td>User</td>
			<td>Comment</td>
			<td colspan="2">Actions</td>
		</tr>
	</table>
</div>
<div class="iframe_vers">
	<iframe id="diff_iframe" name="diff_iframe" style="width:99%;height:99%;display:none;"></iframe>
</div>
<script type="text/javascript">prettyPrint();</script>
<script type="text/javascript">
	addRow(<?php echo json_encode($arr); ?>,"vers");
</script>