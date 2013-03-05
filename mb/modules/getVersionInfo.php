<?php
	session_start();
	if(!isset($_SESSION['user'])) {
		header('Location: ../login');
	}
	else {
		if(isset($_POST['ver_id'])) {
			include("query.php");
			$arr=query("SELECT * FROM versions WHERE vers_id=".$_POST['ver_id']."");
		}
		else {
			header('Location: ../');
		}
	}
?>
<p class="info"></p>
<script type="text/javascript">
	getInfo(<?php echo json_encode($arr); ?>);
</script>