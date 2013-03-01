function addRow(arr,div) {
	for(var i=0;i<arr.length;i++) {
		if(div=="rep") {
			$("table.rep").append("<tr><td><a class='repo-btn' id='"+arr[i]["repo_id"]+"'><div class='div-button' style='width:93%;height:20px;line-height:18px;'><img src='images/folder.png' width='20px'>"+arr[i]["repo_name"]+"</div></a></td></tr>");
		}
		else if(div=="cmp") {
			var adddiv="", addclass="ver-btn "+arr[i]["vers_comp_id"], image="<img src='images/file.png' width='15px'>", isEven="", folder_btns="";
			if(arr[i]["vers_type"]=='dir') {
				adddiv="<tr><td><div class='"+arr[i]["vers_id"]+"' id='subdir"+arr[i]["vers_id"]+"'></div></td></tr>";
				addclass="comp-btn";
				image="<img src='images/folder.png' width='20px'>";
				isEven="even";
				folder_btns="<a title='Add file'><div class='dir_btns add_file' id='"+arr[i]["vers_id"]+"' style='width:30px;height:27px;line-height:27px;'>(+)</div></a><a title='Add folder'><div class='dir_btns add_folder' id='"+arr[i]["vers_id"]+"' style='width:30px;height:27px;line-height:27px;'>+</div></a>";
			}
			$("table.cmp").append("<tr><td><a class='"+addclass+" "+isEven+"' id='"+arr[i]["vers_id"]+"'><div class='div-button' style='width:72%;height:20px;line-height:18px;float:left;'>"+image+arr[i]["vers_name"]+"</div></a>"+folder_btns+"</td></tr>"+adddiv);
		}
		else if(div=="dir") {
			var adddiv="", addclass="ver-btn "+arr[i]["vers_comp_id"], image="<img src='images/file.png' width='15px'>", isEven="", folder_btns="";
			if(arr[i]["vers_type"]=='dir') {
				adddiv="<tr><td><div class='"+arr[i]["vers_id"]+"'></div></td></tr>";
				addclass="comp-btn";
				image="<img src='images/folder.png' width='20px'>";
				isEven="even";
				folder_btns="<a title='Add file'><div class='dir_btns add_file' id='"+arr[i]["vers_id"]+"' style='width:30px;height:27px;line-height:27px;'>(+)</div></a><a title='Add folder'><div class='dir_btns add_folder' id='"+arr[i]["vers_id"]+"' style='width:30px;height:27px;line-height:27px;'>+</div></a>";
			}
			$("table.vers"+arr[i]['vers_parent']).append("<tr><td><a class='"+addclass+" "+isEven+"' id='"+arr[i]["vers_id"]+"'><div class='div-button' style='width:72%;height:20px;line-height:18px;float:left;'>"+image+arr[i]["vers_name"]+"</div></a>"+folder_btns+"</td></tr>"+adddiv);
		}
		else if(div=="vers") {
			var adddiv="", addclass="ver-btn "+arr[i]["vers_comp_id"], image="<img src='images/file.png' width='15px'>";
			if(arr[i]["vers_type"]=='dir') {
				adddiv="<tr><td><div class='"+arr[i]["vers_id"]+"'></div></td></tr>";
				addclass="comp-btn";
				image="<img src='images/folder.png' width='20px'>";
			}
			$("table.vers").append("<tr><td><a class='"+addclass+"' id='"+arr[i]["vers_id"]+"'><div class='div-button' style='width:93%;height:20px;line-height:18px;'>"+image+arr[i]["vers_name"]+"</div></a></td></tr>"+adddiv);
		}
	}
}

function getInfo(arr) {
	for(var i=0;i<arr.length;i++) {
		$("p.info").append("Version: "+arr[i]["vers_repo_vers"]+"<br>Date and Time: "+arr[i]["vers_date"]);
	}
}