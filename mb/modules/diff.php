<?php
	/**
	* Paul's Simple Diff Algorithm v 0.1
	*(C) Paul Butler 2007 <http://www.paulbutler.org/>
	* May be used and distributed under the zlib/libpng license.
	*/
	function diff($old, $new) {
		$maxlen=0;
		foreach($old as $oindex => $ovalue) {
			$nkeys=array_keys($new,$ovalue);
			foreach($nkeys as $nindex) {
				$matrix[$oindex][$nindex]=isset($matrix[$oindex-1][$nindex-1])?$matrix[$oindex-1][$nindex-1]+1:1;
				if($matrix[$oindex][$nindex]>$maxlen) {
					$maxlen=$matrix[$oindex][$nindex];
					$omax=$oindex+1-$maxlen;
					$nmax=$nindex+1-$maxlen;
				}
			}
		}
		if($maxlen==0) {
			return array(array("d"=>$old,"i"=>$new));
		}
		return array_merge(diff(array_slice($old,0,$omax),array_slice($new,0,$nmax)),array_slice($new,$nmax,$maxlen),diff(array_slice($old,$omax+$maxlen),array_slice($new,$nmax+$maxlen)));
	}

	function htmlDiff($old, $new) {
		$ret="";
		preg_match_all("/\p{L}+|./",$old,$matches_old,PREG_PATTERN_ORDER);
		preg_match_all("/\p{L}+|./",$new,$matches_new,PREG_PATTERN_ORDER);
		$diff=diff($matches_old[0], $matches_new[0]);
		foreach($diff as $k) {
			if(is_array($k)) {
				$ret.=(!empty($k["d"])?"<del>".htmlspecialchars(implode("",$k["d"]))."</del>":"").(!empty($k["i"])?"<ins>".htmlspecialchars(implode("",$k["i"]))."</ins>":"");
			}
			else {
				$ret.=htmlspecialchars($k);
			}
		}
		return $ret;
	}
	
	function getTextDiff($repo_id, $file1id, $file2id) {
		//STARTS HERE
		chdir("inc");
		include("Text/Diff.php");
		ini_set("memory_limit","512M");
		//echo getcwd();
		$from=file_get_contents("../upload/uploads/repositories/".$repo_id."/".$file1id);
		$to=file_get_contents("../upload/uploads/repositories/".$repo_id."/".$file2id);
		$from_lines=explode("\n",$from);
		$to_lines=explode("\n",$to);
		$textdiff=new Text_Diff("native",$from_lines,$to_lines);
		$edits=$textdiff->getDiff();
		$text="";
		foreach($edits as $edit) {
			if(is_a($edit,"Text_Diff_Op_delete")) {
				foreach($edit->orig as $line) {
					$text.="<del>".htmlspecialchars($line)."\r\n</del>";
				}
			}
			else if(is_a($edit,"Text_Diff_Op_add")) {
				foreach($edit->final as $line) {
					$text.="<ins>".htmlspecialchars($line)."\r\n</ins>";
				}
			}
			else if(is_a($edit,"Text_Diff_Op_change")) {
				$count=min(count($edit->orig),count($edit->final));
				for($i=0; $i<$count; $i++) {
					$line=htmlDiff($edit->orig[$i],$edit->final[$i]);
					if(preg_match("/^<del>[^<]*<\/del>$|^<ins>[^<]*<\/ins>$/i",$line)) {
						$text.=substr($line,0,strlen($line)-6)."\r\n".substr($line,strlen($line)-6);
					}
					else {
						$text.="<em>".$line."\r\n</em>";
					}
				}
				if(count($edit->orig)>$count) {
					foreach(array_slice($edit->orig,$count) as $line) {
						$text.="<del>".htmlspecialchars($line)."\r\n</del>";
					}
				}
				if(count($edit->final)>$count) {
					foreach(array_slice($edit->final,$count) as $line) {
						$text.="<ins>".htmlspecialchars($line)."\r\n</ins>";
					}
				}
			}
			else {
				foreach($edit->orig as $line) {
					$text.=htmlspecialchars($line)."\r\n";
				}
			}
		}
		$text=trim($text);
		return $text;
	}
	
	session_start();
	if(!isset($_SESSION["user"])) {
		header("Location: ../login");
	}
	else {
		if(isset($_POST["diff_from"])) {
			$str=getTextDiff($_POST["diff_repo_id"], $_POST["diff_from"], $_POST["diff_to"]);
			chdir("../");
			$string='<link rel="stylesheet" type="text/css" href="../css/prettify.css"><script src="../js/prettify.js"></script><body onload="prettyPrint()"><pre style="word-wrap:break-word;" class="prettyprint linenums">'.$str.'</pre></body>';
			file_put_contents("iframe.html",$string);
		}
		else {
			header("Location: ../");
		}
	}
?>