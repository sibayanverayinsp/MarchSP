<?php
	function getTexts($repo_id, $file1id) {
		$data=file_get_contents("upload/uploads/repositories/".$repo_id."/".$file1id);
		$line=explode(" ",$data);
		$text="";
		for($i=0; $i<count($line); $i++) {
			$text.=htmlspecialchars($line[$i])." ";
		}
		$text=trim($text);
		return $text;
	}
	session_start();
	if(!isset($_SESSION["user"])) {
		header("Location: ../login");
	}
	else {
		if(isset($_POST["qv_file"])) {
			$str=getTexts($_POST["qv_repo_id"],$_POST["qv_file"]);
			$string='<link rel="stylesheet" type="text/css" href="../css/prettify.css"><script src="../js/prettify.js"></script><body onload="prettyPrint()"><pre style="word-wrap:break-word;" class="prettyprint linenums">'.$str.'</pre></body>';
			file_put_contents("iframe.html",$string);
		}
		else {
			header("Location: ../login");
		}
	}
?>