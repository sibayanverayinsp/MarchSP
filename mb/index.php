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
		<link rel="stylesheet" type="text/css" href="js/jquery-ui-1.10.0/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="css/mb.css">
		<script src="js/jquery-1.9.1.min.js"></script>
		<script src="js/jquery-ui-1.10.0/ui/jquery-ui.js"></script>
		<script src="js/mb.js"></script>
		<script src="js/jqueryFunc.js"></script>
		<!--<script src="js/disable.js"></script>-->
		<script type="text/javascript">
			$(document).ready(function() {
				jqueryFunc();
				$("#upload_div").css("position","absolute");
				$("#upload_div").css("top",Math.max(0,(($(window).height()-$("#upload_div").outerHeight())/2)+$(window).scrollTop())+"px");
				$("#upload_div").css("left",Math.max(0,(($(window).width()-$("#upload_div").outerWidth())/2)+$(window).scrollLeft())+"px");
				$("#upload_div").hide();
			});
		</script>
	</head>
	<!--<body onkeypress="return disableKeyCombination(event);" onkeydown="return disableKeyCombination(event);" ondragstart="return false" onselectstart="return false">-->
	<body>
		<div class="container">
			<div class="mainDivs repo">
				<p>repositories</p>
				<div class="repositories">
					<table class="rep">

					</table>
					<form name="addrepo_form" action="" method="post">
						<input type="text" placeholder=" Repository Name" id="repoName" name="repoName" onblur="checkField('repoName')" onkeypress="$('.addrepo_error_msg').fadeOut()">
						<input type="submit" value="ADD" class="submit_btn addrepo_btn">
					</form>
					<div class="addrepo_error_msg" style="opacity:0">
						<p class="err">This field is required!</p>
					</div>
				</div>
			</div>
			<div class="mainDivs comp">
				<p>components</p>
				<div class="components">

				</div>
			</div>
			<div class="mainDivs ver">
				<p>versions</p>
				<div class="versions">

				</div>
			</div>
			<div id="pop-up"></div>
			<div id="chat-box">
				<div class="chat"></div>
				<div class="chat-link"><a><div id="chat_btn">Chat</div></a></div>
			</div>
			<a href="logout"><div id="logout_btn">LOGOUT(<?php echo $_SESSION["user"]; ?>)</div></a>
		</div>
		<div id="upload_div">
			<form name="upload_form" action="modules/upload.php" method="post" enctype="multipart/form-data">
				<input type="file" class="proj" name="proj" id="proj" required="required">
				<input type="hidden" name="hidden_parent_id">
				<input type="hidden" name="hidden_repo_id">
				<br><br>
				<input type="submit" class="submit_btn upload_btn" value="ADD">
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