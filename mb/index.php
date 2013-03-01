<!DOCTYPE html>
<html>
	<?php
		include("modules/query.php");
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
			});
		</script>
		<script type="text/javascript">
			function checkFields() {
				document.getElementById('repoName').value = document.getElementById('repoName').value.trim();
			}
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
					<form id="myform" action="modules/addRepo.php" method="post">
						<input type="text" placeholder=" Repository Name" id="repoName" name="repoName" required="required" onblur="checkFields()">
						<input type="submit" value="ADD" class="submit_btn">
					</form>
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
		</div>
		<?php
			$arr=query("SELECT * FROM repositories");
		?>
		<script type="text/javascript">
			addRow(<?php echo json_encode($arr); ?>,"rep");
		</script>
	</body>
</html>