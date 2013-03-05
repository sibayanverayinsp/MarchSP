<?php
	session_start();
	if(!isset($_SESSION['user'])) {
		header('Location: ../login');
	}
	else {
		if(isset($_POST['addrepo_class'])) {
			include("query.php");
			$arr=query("SELECT * FROM repositories");
		}
		else {
			header('Location: ../');
		}
	}
?>
<table class="rep"></table>
<form name="addrepo_form" action="" method="post">
	<input type="text" placeholder=" Repository Name" id="repoName" name="repoName" onblur="checkField('repoName')" onkeypress="$('.addrepo_error_msg').fadeOut()">
	<input type="submit" value="ADD" class="submit_btn addrepo_btn">
</form>
<div class="addrepo_error_msg" style="opacity:0">
	<p class="err">This field is required!</p>
</div>
<script type="text/javascript">
	addRow(<?php echo json_encode($arr); ?>,"rep");
</script>