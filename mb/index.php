<!DOCTYPE html>
<html>
	<?php
		session_start();
		if(!isset($_SESSION["user"])) {
			header("Location: login");
		}
		else {
			include("modules/query.php");
		}
	?>
	<head>
		<title>Magic Box</title>
		<!--<link rel="stylesheet" type="text/css" href="js/jquery-ui-1.10.0/themes/base/jquery-ui.css">-->
		<link rel="stylesheet" type="text/css" href="css/mb.css">
		<link rel="stylesheet" type="text/css" href="css/prettify.css">
		<script src="js/jquery-1.9.1.min.js"></script>
		<!--<script src="js/jquery-ui-1.10.0/ui/jquery-ui.js"></script>-->
		<script src="js/jquery.form.js"></script>
		<script src="js/mb.js"></script>
		<script src="js/jqueryFunc.js"></script>
		<script src="js/prettify.js"></script>
		<!--<script src="js/disable.js"></script>-->
		<script type="text/javascript">
			$(document).ready(function() {
				jqueryFunc();
			});
		</script>
	</head>
	<!--<body onkeypress="return disableKeyCombination(event);" onkeydown="return disableKeyCombination(event);" ondragstart="return false" onselectstart="return false">-->
	<body>
		<div class="container">
			<div class="mainDivs repo">
				<p>repositories</p>
				<div class="repositories">
					<div class="rep">

					</div>
					<form name="addrepo_form" id="addrepo_form" action="modules/addRepo.php" method="post">
						<input type="text" placeholder=" Repository Name" id="repoName" name="repoName" required="required" onblur="checkField('repoName')">
						<input type="submit" value="ADD" class="submit_btn addrepo_btn">
					</form>
				</div>
			</div>
			<div class="mainDivs comp">
				<p>components</p>
				<div class="components">

				</div>
			</div>
			<!--<div class="mainDivs ver">
				<p>versions</p>
				<div class="versions">

				</div>
			</div>-->
			<div id="pop-up"></div>
			<div id="chat-box">
				<div class="chat"></div>
				<div class="chat-link"><a><div id="chat_btn">Chat</div></a></div>
			</div>
			<a href="logout"><div id="logout_btn">LOGOUT(<?php echo $_SESSION["user"]; ?>)</div></a>
		</div>
		<div class="wall">
			<div id="upload_div">
				<form name="upload_form" action="modules/upload/upload.php" id="upload_form" method="post" enctype="multipart/form-data">
					<input type="file" class="proj" name="proj" id="proj" required="required">
					<br><br>
					<textarea class="message" name="message" id="message" rows="4" cols="50" placeholder="insert comment here..." maxlength="256"></textarea>
					<input type="hidden" name="hidden_parent_id" id="upload_parent_id">
					<input type="hidden" name="hidden_repo_id" id="upload_repo_id">
					<br><br>
					<input type="submit" class="submit_btn upload_btn" value="COMMIT">
				</form>
				<br>
				<div class="progress">
					<div class="bar"></div>
					<div class="percent">0%</div>
				</div>
				<a class="a_close_btn"><img src="images/close.png" width="30px" class="close_btn"></a>
			</div>
		</div>
		<div class="wall2">
			<div class="vers_wrap">
				<div class="versions">

				</div>
				<a class="vers_close_btn"><img src="images/close.png" width="30px" class="v_close_btn"></a>
			</div>
		</div>
		<div class="wall3">
			<div id="download_div">
				<form name="download_form" action="modules/download/download.php" id="download_form" method="post" enctype="multipart/form-data">
					<input type="hidden" name="download_parent_id" id="download_parent_id">
					<input type="hidden" name="download_repo_id" id="download_repo_id">
					<input type="hidden" name="download_name" id="download_name">
				</form>
			</div>
		</div>
		<div id="diff_div">
			<form name="diff_form" action="modules/diff.php" id="diff_form" method="post" target="diff_iframe">
				<input type="hidden" name="diff_repo_id" id="diff_repo_id">
				<input type="hidden" name="diff_from" id="diff_from">
				<input type="hidden" name="diff_to" id="diff_to">
			</form>
		</div>
		<?php
			$arr=query("SELECT * FROM repositories");
		?>
		<script type="text/javascript">
			addRow(<?php echo json_encode($arr); ?>,"rep");
		</script>
	</body>
</html>