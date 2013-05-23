<!--

/**
 * @title
 * MagicBox: A Simple Version Control System
 *
 * @description
 * A Special Problem Presented to the Faculty of
 * The Institute of Computer Science
 * University of the Philippines Los Banos
 *
 * In Partial Fulfillment of the Requirements of the Degree of
 * Bachelor of Science in Computer Science
 *
 * @authors
 * Jasper A. Sibayan 
 * 2009-46112
 * and 
 * Wilbert G. Verayin
 * 2009-60315
 * @date
 * April 2013
 */

-->
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
		<link rel="stylesheet" type="text/css" href="css/mb.css">
		<link rel="stylesheet" type="text/css" href="css/prettify.css">
		<link rel="stylesheet" type="text/css" href="css/themes/base/minified/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="css/jquery.ui.chatbox.css">
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<script src="js/jquery-1.9.1.min.js"></script>
		<script src="js/jquery-ui-1.10.0/ui/minified/jquery-ui.min.js"></script>
		<script src="js/jquery.form.js"></script>
		<script src="js/jquery.ui.chatbox.js"></script>
		<script src="js/mb.js"></script>
		<script src="js/jqueryFunc.js"></script>
		<script src="js/prettify.js"></script>
		<!--<script src="js/disable.js"></script>-->
		<script type="text/javascript">
			$(document).ready(function() {
				jqueryFunc();
				//chat
				var box = null;
				box = $("#chat_div").chatbox({
					id: document.getElementById("user").value,
					user: {
						key: "value"
					},
					offset: 20,
					title: "Chat",
					messageSent: function(id, user, msg) {
						$.ajax({
							url: "modules/addChat.php",
							data: {
								message: msg,
							},
							type: "POST",
							success: function(data) {
								$("#chat_div").chatbox("option","boxManager").addMsg(id,msg);
							}
						});
					}
				});
				setInterval(function() {
					$("#chat_div").load("modules/getChats.php",{valid:1},function() {
						$("#chat_div").chatbox("option","boxManager")._scrollToBottom();
					});
				},1000);
			});
		</script>
	</head>
	<!--<body onkeypress="return disableKeyCombination(event);" onkeydown="return disableKeyCombination(event);" ondragstart="return false" onselectstart="return false">-->
	<body>
		<div class="container">
			<div class="mainDivs repo">
				<p class="title">repositories</p>
				<div class="repositories">
					<div class="rep">

					</div>
					<?php
						if($_SESSION["type"]=="Admin") {
					?>
						<form name="addrepo_form" id="addrepo_form" action="modules/addRepo.php" method="post">
							<input type="text" placeholder=" Repository Name" id="repoName" name="repoName" required="required" onblur="checkField('repoName')">
							<input type="submit" value="ADD" class="submit_btn addrepo_btn">
						</form>
					<?php
						}
					?>
				</div>
			</div>
			<div class="mainDivs comp">
				<p class="title">files and folders</p>
				<div class="fileFolders">

				</div>
			</div>
			<div class="mainDivs logs">
				<p class="title">activities</p>
				<div class="log">

				</div>
				<?php
					if($_SESSION["type"]=="Admin") {
						echo "<a class='clear_log_btn'>Clear Logs</a>";
					}
				?>
			</div>
			<a href="" class="logout"><div id="logout_btn">LOGOUT(<?php echo $_SESSION["user"]; ?>)</div></a>
			<a class="show_deleted">Show deleted files</a>
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
					<input type="hidden" name="is_dir" id="is_dir">
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
		<div id="qv_div">
			<form name="qv_form" action="modules/quickview.php" id="qv_form" method="post" target="diff_iframe">
				<input type="hidden" name="qv_repo_id" id="qv_repo_id">
				<input type="hidden" name="qv_file" id="qv_file">
			</form>
		</div>
		<div class="wall4">
			<div id="revert_div">
				<form name="revert_form" action="modules/revert.php" id="revert_form" method="post">
					<input type="hidden" name="version_id" id="version_id">
					<textarea class="message" name="revert_message" id="revert_message" rows="4" cols="50" placeholder="insert comment here..." maxlength="256"></textarea>
					<br><br>
					<input type="submit" class="submit_btn revert_btn" value="REVERT">
				</form>
				<a class="a_close_btn"><img src="images/close.png" width="30px" class="close_btn"></a>
			</div>
		</div>
		<div class="wall5">
			<div id="del_div">
				<form name="delete_form" action="modules/delete.php" id="delete_form" method="post">
					<input type="hidden" name="del_version_id" id="del_version_id">
					<textarea class="message" name="delete_message" id="delete_message" rows="4" cols="50" placeholder="insert comment here..." maxlength="256"></textarea>
					<br><br>
					<input type="submit" class="submit_btn delete_btn" value="DELETE">
				</form>
				<a class="a_close_btn"><img src="images/close.png" width="30px" class="close_btn"></a>
			</div>
		</div>
		<div class="wall6">
			<div id="show_del">
				<div class="deleted_files">

				</div>
				<a class="vers_close_btn"><img src="images/close.png" width="30px" class="v_close_btn"></a>
			</div>
		</div>
		<div class="wall-lock">
			<div class="locking">
				<img src="images/load.gif">
			</div>
		</div>
		<div id="chat_div">

		</div>
		<input type="hidden" id="user" value=<?php echo $_SESSION["user"]; ?>>
		<?php
			$arr=query("SELECT * FROM repositories");
		?>
		<script type="text/javascript">
			addRow(<?php echo json_encode($arr); ?>,"rep");
		</script>
	</body>
</html>