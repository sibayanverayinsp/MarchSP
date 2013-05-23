<?php

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

	session_start();
	if(!isset($_SESSION["user"]) || !isset($_POST["sort"])) {
		?>
		<html>
			<head>
				<title>404 Not Found</title>
			</head>
			<body>
				<h1>Not Found</h1>
				<?php
				echo "<p>The requested URL ".$_SERVER["REQUEST_URI"]." was not found on this server.</p>";
				?>
			</body>
		</html>
		<?php
	}
	else {
		include("query.php");
		if($_POST["sort"]=="default" || $_POST["sort"]=="path") {
			$arr=query("SELECT * FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a, accounts as b, repositories c WHERE a.vers_type='file(del)' AND a.vers_acct_id=b.acct_id AND a.vers_repo_id=c.repo_id ORDER BY vers_date DESC");
		}
		else {
			if($_POST["sort"]=="name" && $_POST["order"]=="asc") {
				$arr=query("SELECT * FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a, accounts as b, repositories c WHERE a.vers_type='file(del)' AND a.vers_acct_id=b.acct_id AND a.vers_repo_id=c.repo_id ORDER BY vers_name");
			}
			else if($_POST["sort"]=="name" && $_POST["order"]=="desc") {
				$arr=query("SELECT * FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a, accounts as b, repositories c WHERE a.vers_type='file(del)' AND a.vers_acct_id=b.acct_id AND a.vers_repo_id=c.repo_id ORDER BY vers_name DESC");
			}
			else if($_POST["sort"]=="date" && $_POST["order"]=="asc") {
				$arr=query("SELECT * FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a, accounts as b, repositories c WHERE a.vers_type='file(del)' AND a.vers_acct_id=b.acct_id AND a.vers_repo_id=c.repo_id ORDER BY vers_date");
			}
			else if($_POST["sort"]=="date" && $_POST["order"]=="desc") {
				$arr=query("SELECT * FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a, accounts as b, repositories c WHERE a.vers_type='file(del)' AND a.vers_acct_id=b.acct_id AND a.vers_repo_id=c.repo_id ORDER BY vers_date DESC");
			}
			else if($_POST["sort"]=="user" && $_POST["order"]=="asc") {
				$arr=query("SELECT * FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a, accounts as b, repositories c WHERE a.vers_type='file(del)' AND a.vers_acct_id=b.acct_id AND a.vers_repo_id=c.repo_id ORDER BY acct_name");
			}
			else if($_POST["sort"]=="user" && $_POST["order"]=="desc") {
				$arr=query("SELECT * FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a, accounts as b, repositories c WHERE a.vers_type='file(del)' AND a.vers_acct_id=b.acct_id AND a.vers_repo_id=c.repo_id ORDER BY acct_name DESC");
			}
			else if($_POST["sort"]=="comment" && $_POST["order"]=="asc") {
				$arr=query("SELECT * FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a, accounts as b, repositories c WHERE a.vers_type='file(del)' AND a.vers_acct_id=b.acct_id AND a.vers_repo_id=c.repo_id ORDER BY vers_message");
			}
			else if($_POST["sort"]=="comment" && $_POST["order"]=="desc") {
				$arr=query("SELECT * FROM (SELECT *, MAX(vers_repo_vers) FROM(SELECT * FROM versions ORDER BY vers_repo_vers DESC) AS c GROUP BY c.vers_comp_id ORDER BY vers_type, vers_name) AS a, accounts as b, repositories c WHERE a.vers_type='file(del)' AND a.vers_acct_id=b.acct_id AND a.vers_repo_id=c.repo_id ORDER BY vers_message DESC");
			}
		}
		?>
		<p style="font-weight:bolder;">Deleted Files</p>
		<div class="table_del">
			<table class="del">
				<tr class="header">
					<td class="sort sort-path <?php echo $_POST["order"]; ?>"><a title='Sort by path'>Path</a></td>
					<td class="sort sort-name <?php echo $_POST["order"]; ?>"><a title='Sort by name'>Name</a></td>
					<td class="sort sort-date <?php echo $_POST["order"]; ?>"><a title='Sort by date'>Date</a></td>
					<td class="sort sort-user <?php echo $_POST["order"]; ?>"><a title='Sort by user'>User</a></td>
					<td class="sort sort-comment <?php echo $_POST["order"]; ?>"><a title='Sort by comment'>Comment</a></td>
					<td>Action</td>
				</tr>
				<?php
					$len=sizeof($arr);
					if($_POST["sort"]=="path" && $_POST["order"]=="asc") {
						for($i = 1; $i < $len; $i++) {
							for($j = $i; $j > 0; $j--) {
								if(strcmp(getPath($arr[$j]["vers_id"]),getPath($arr[$j-1]["vers_id"])) < 0) {
									$temp = $arr[$j];
									$arr[$j] = $arr[$j-1];
									$arr[$j-1] = $temp;
								}
								else {
									break;
								}
							}
						}
					}
					else if($_POST["sort"]=="path" && $_POST["order"]=="desc") {
						for($i = 1; $i < $len; $i++) {
							for($j = $i; $j > 0; $j--) {
								if(strcmp(getPath($arr[$j]["vers_id"]),getPath($arr[$j-1]["vers_id"])) > 0) {
									$temp = $arr[$j];
									$arr[$j] = $arr[$j-1];
									$arr[$j-1] = $temp;
								}
								else {
									break;
								}
							}
						}
					}
					for($i=0; $i<$len; $i++) {
						$arr[$i]["vers_date"]=date("M\ d\, Y \(D\)-h\:i\:s a",strtotime($arr[$i]["vers_date"]));
						$path=getPath($arr[$i]["vers_id"]);
						echo "<tr class='not_header'>";
						echo "<td class='ellipsis'><a title='".$arr[$i]["repo_name"]."$path'>".$arr[$i]["repo_name"]."$path</a></td>";
						echo "<td class='ellipsis'><a title='".$arr[$i]["vers_name"]."'>".$arr[$i]["vers_name"]."</a></td>";
						echo "<td class='ellipsis'><a title='".$arr[$i]["vers_date"]."'>".$arr[$i]["vers_date"]."</a></td>";
						echo "<td class='ellipsis'><a title='".$arr[$i]["acct_name"]."'>".$arr[$i]["acct_name"]."</a></td>";
						echo "<td class='ellipsis'><a title='".$arr[$i]["vers_message"]."'>".$arr[$i]["vers_message"]."</a></td>";
						echo "<td class='centered undelete' id='".$arr[$i]["vers_id"]."'><a id='".$arr[$i]["vers_id"]."' class='undelete_btn'>Undo delete</a></td>";
						echo "</tr>";
					}
				?>
			</table>
		</div>
		<?php
	}

	function getPath($vers_id) {
		log_connect();
		$path="";
		$parent=$vers_id;
		do {
			$query="SELECT `vers_parent`, `vers_name`
			FROM `versions` 
			WHERE vers_id =$parent";
			$result=mysql_query($query) or die(mysql_error());
			$arr=convert($result);
			$name=$arr[0]['vers_name'];
			$parent=$arr[0]['vers_parent'];
			$path="/$name$path";
		} while($parent!=0);
		return $path;
	}
	
	function convert($result) {
		$table_result=array();
		$r=0;
		while($row=mysql_fetch_assoc($result)) {
			$arr_row=array();
			$c=0;
			while($c<mysql_num_fields($result)) {
				$col=mysql_fetch_field($result,$c);
				$arr_row[$col->name]=$row[$col->name];
				$c++;
			}
			$table_result[$r]=$arr_row;
			$r++;
		}
		mysql_free_result($result);
		return $table_result;
	}
	
	function log_connect(){
		$link=mysql_connect("localhost","root","") or die('Database connection error.');	
		if(!empty($link->connect_error)) {
			$result['error']=$link->connect_error;
			print_r($result);
		}
		else {
			mysql_select_db("magicboxdb");
		}
	}
?>