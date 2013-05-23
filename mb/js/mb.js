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

function addRow(arr,div) {
	var len=arr.length;
	var deleted=0, isSecond=false;
	for(var i=0;i<len;i++) {
		if(div=="rep") {
			$("div.rep").append("<a class='repo-btn' id='"+arr[i]["repo_id"]+"'><div class='div-button' style='width:75%;line-height:18px;'><img src='images/folder.png' width='20px'>"+arr[i]["repo_name"]+"</div></a><a title='Download'><div class='repo_dl download' id='"+arr[i]["repo_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a><a title='Upload'><div class='repo_ul upload' id='"+arr[i]["repo_id"]+"'><img src='images/upload.png' width='20px' height='20px'></div></a>");
		}
		else if(div=="cmp") {
			if(arr[i]["vers_lock_acct_id"]==0) {
				var folder_btns="<a title='Lock'><div class='dir_btns lock' id='"+arr[i]["vers_id"]+"'><img src='images/lock.png' width='20px' height='20px'></div></a><a title='Delete'><div class='dir_btns delete' id='"+arr[i]["vers_id"]+"'><img src='images/delete.png' width='20px' height='20px'></div></a><a title='Download'><div class='dir_btns download' id='"+arr[i]["vers_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a>";
			}
			else {
				var folder_btns="<a title='Unlock'><div class='dir_btns lock' id='"+arr[i]["vers_id"]+"'><img src='images/unlock.png' width='20px' height='20px'></div></a><a title='Delete'><div class='dir_btns delete' id='"+arr[i]["vers_id"]+"'><img src='images/delete.png' width='20px' height='20px'></div></a><a title='Download'><div class='dir_btns download' id='"+arr[i]["vers_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a>";
			}
			var adddiv="", addclass="ver-btn "+arr[i]["vers_comp_id"], image="<img src='images/file.png' width='15px'>", isEven="";
			if(arr[i]["vers_type"]=='dir') {
				adddiv="<div class='superDir "+arr[i]["vers_id"]+"' id='subdir"+arr[i]["vers_id"]+"'></div>";
				addclass="fileFolder-btn";
				image="<img src='images/folder.png' width='20px'>";
				isEven="even";
				if(arr[i]["vers_lock_acct_id"]==0) {
					folder_btns="<a title='Lock'><div class='dir_btns lock' id='"+arr[i]["vers_id"]+"'><img src='images/lock.png' width='20px' height='20px'></div></a><a title='Delete'><div class='dir_btns delete' id='"+arr[i]["vers_id"]+"'><img src='images/delete.png' width='20px' height='20px'></div></a><a title='Download'><div class='dir_btns download' id='"+arr[i]["vers_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a><a title='Upload'><div class='dir_btns upload' id='"+arr[i]["vers_id"]+"'><img src='images/upload.png' width='20px' height='20px'></div></a>";
				}
				else {
					folder_btns="<a title='Unlock'><div class='dir_btns lock' id='"+arr[i]["vers_id"]+"'><img src='images/unlock.png' width='20px' height='20px'></div></a><a title='Delete'><div class='dir_btns delete' id='"+arr[i]["vers_id"]+"'><img src='images/delete.png' width='20px' height='20px'></div></a><a title='Download'><div class='dir_btns download' id='"+arr[i]["vers_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a><a title='Upload'><div class='dir_btns upload' id='"+arr[i]["vers_id"]+"'><img src='images/upload.png' width='20px' height='20px'></div></a>";
				}
			}
			$("div.cmp").append("<div class='superDir'><a class='"+addclass+" "+isEven+"' id='"+arr[i]["vers_id"]+"'><div class='div-button' style='line-height:18px;width:70%;'>"+image+arr[i]["vers_name"]+"</div></a>"+folder_btns+"</div>"+adddiv);
		}
		else if(div=="dir") {
			if(arr[i]["vers_lock_acct_id"]==0) {
				folder_btns="<a title='Lock'><div class='dir_btns lock' id='"+arr[i]["vers_id"]+"'><img src='images/lock.png' width='20px' height='20px'></div></a><a title='Delete'><div class='dir_btns delete' id='"+arr[i]["vers_id"]+"'><img src='images/delete.png' width='20px' height='20px'></div></a><a title='Download'><div class='dir_btns download' id='"+arr[i]["vers_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a>";
			}
			else {
				folder_btns="<a title='Unlock'><div class='dir_btns lock' id='"+arr[i]["vers_id"]+"'><img src='images/unlock.png' width='20px' height='20px'></div></a><a title='Delete'><div class='dir_btns delete' id='"+arr[i]["vers_id"]+"'><img src='images/delete.png' width='20px' height='20px'></div></a><a title='Download'><div class='dir_btns download' id='"+arr[i]["vers_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a>";
			}
			var adddiv="", addclass="ver-btn "+arr[i]["vers_comp_id"], image="<img src='images/file.png' width='15px'>", isEven="";
			if(arr[i]["vers_type"]=='dir') {
				adddiv="<div class='"+arr[i]["vers_id"]+"'></div>";
				addclass="fileFolder-btn";
				image="<img src='images/folder.png' width='20px'>";
				isEven="even";
				if(arr[i]["vers_lock_acct_id"]==0) {
					folder_btns="<a title='Lock'><div class='dir_btns lock' id='"+arr[i]["vers_id"]+"'><img src='images/lock.png' width='20px' height='20px'></div></a><a title='Delete'><div class='dir_btns delete' id='"+arr[i]["vers_id"]+"'><img src='images/delete.png' width='20px' height='20px'></div></a><a title='Download'><div class='dir_btns download' id='"+arr[i]["vers_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a><a title='Upload'><div class='dir_btns upload' id='"+arr[i]["vers_id"]+"'><img src='images/upload.png' width='20px' height='20px'></div></a>";
				}
				else {
					folder_btns="<a title='Unlock'><div class='dir_btns lock' id='"+arr[i]["vers_id"]+"'><img src='images/unlock.png' width='20px' height='20px'></div></a><a title='Delete'><div class='dir_btns delete' id='"+arr[i]["vers_id"]+"'><img src='images/delete.png' width='20px' height='20px'></div></a><a title='Download'><div class='dir_btns download' id='"+arr[i]["vers_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a><a title='Upload'><div class='dir_btns upload' id='"+arr[i]["vers_id"]+"'><img src='images/upload.png' width='20px' height='20px'></div></a>";
				}
			}
			$("div.vers"+arr[i]['vers_parent']).append("<div class='superDir'><a class='"+addclass+" "+isEven+"' id='"+arr[i]["vers_id"]+"'><div class='div-button' style='line-height:18px;width:70%;'>"+image+arr[i]["vers_name"]+"</div></a>"+folder_btns+"</div>"+adddiv);
		}
		else if(div=="vers") {
			var adddiv="", addclass="ver-btn "+arr[i]["vers_comp_id"], image="<img src='images/file.png' width='15px'>";
			if(arr[i]["vers_type"]=='dir') {
				adddiv="<tr><td><div class='"+arr[i]["vers_id"]+"'></div></td></tr>";
				addclass="fileFolder-btn";
				image="<img src='images/folder.png' width='20px'>";
			}
			if(arr[i]["vers_type"]=="file(del)") {
				$("table.vers").append("<tr class='not_header'><td class='centered'></td><td class='centered'></td><td class='centered'>"+arr[i]["vers_repo_vers"]+"</td><td class='ellipsis'><a title='"+arr[i]["vers_date"]+"'>"+arr[i]["vers_date"]+"</a></td><td class='centered' class='ellipsis'><a title='"+arr[i]["acct_name"]+"'>"+arr[i]["acct_name"]+"</a></td><td class='ellipsis'><a title='"+arr[i]["vers_message"]+"'>"+arr[i]["vers_message"]+"</a></td><td class='centered'></td><td class='centered'></td></tr>");
				deleted=deleted+1;
			}
			else {
				if(len-deleted==1) {
					$("table.vers").append("<tr class='not_header'><td class='centered'><input type='radio' name='from' value='"+arr[i]["vers_id"]+"' checked='true'></td><td class='centered'><input type='radio' name='to' value='"+arr[i]["vers_id"]+"' checked='true'></td><td class='centered'>"+arr[i]["vers_repo_vers"]+"</td><td class='ellipsis'><a title='"+arr[i]["vers_date"]+"'>"+arr[i]["vers_date"]+"</a></td><td class='centered' class='ellipsis'><a title='"+arr[i]["acct_name"]+"'>"+arr[i]["acct_name"]+"</a></td><td class='ellipsis'><a title='"+arr[i]["vers_message"]+"'>"+arr[i]["vers_message"]+"</a></td><td class='centered quickview' id='"+arr[i]["vers_id"]+"'><a title='Quick View' id='"+arr[i]["vers_id"]+"' class='qv_btn'>Q</a></td><td class='centered'></td></tr>");
				}
				else if(i==0) {
					$("table.vers").append("<tr class='not_header'><td class='centered'><input type='radio' name='from' value='"+arr[i]["vers_id"]+"'></td><td class='centered'><input type='radio' name='to' value='"+arr[i]["vers_id"]+"' checked='true'></td><td class='centered'>"+arr[i]["vers_repo_vers"]+"</td><td class='ellipsis'><a title='"+arr[i]["vers_date"]+"'>"+arr[i]["vers_date"]+"</a></td><td class='centered' class='ellipsis'><a title='"+arr[i]["acct_name"]+"'>"+arr[i]["acct_name"]+"</a></td><td class='ellipsis'><a title='"+arr[i]["vers_message"]+"'>"+arr[i]["vers_message"]+"</a></td><td class='centered quickview' id='"+arr[i]["vers_id"]+"'><a title='Quick View' id='"+arr[i]["vers_id"]+"' class='qv_btn'>Q</a></td><td class='centered'></td></tr>");
				}
				else if(isSecond==false) {
					$("table.vers").append("<tr class='not_header'><td class='centered'><input type='radio' name='from' value='"+arr[i]["vers_id"]+"' checked='true'></td><td class='centered'><input type='radio' name='to' value='"+arr[i]["vers_id"]+"'></td><td class='centered'>"+arr[i]["vers_repo_vers"]+"</td><td class='ellipsis'><a title='"+arr[i]["vers_date"]+"'>"+arr[i]["vers_date"]+"</a></td><td class='centered' class='ellipsis'><a title='"+arr[i]["acct_name"]+"'>"+arr[i]["acct_name"]+"</a></td><td class='ellipsis'><a title='"+arr[i]["vers_message"]+"'>"+arr[i]["vers_message"]+"</a></td><td class='centered quickview' id='"+arr[i]["vers_id"]+"'><a title='Quick View' id='"+arr[i]["vers_id"]+"' class='qv_btn'>Q</a></td><td class='centered revert' id='"+arr[i]["vers_id"]+"'><a title='Revert' id='"+arr[i]["vers_id"]+"' class='rev_btn'>R</a></td></tr>");
					isSecond=true;
				}
				else {
					$("table.vers").append("<tr class='not_header'><td class='centered'><input type='radio' name='from' value='"+arr[i]["vers_id"]+"'></td><td class='centered'><input type='radio' name='to' value='"+arr[i]["vers_id"]+"'></td><td class='centered'>"+arr[i]["vers_repo_vers"]+"</td><td class='ellipsis'><a title='"+arr[i]["vers_date"]+"'>"+arr[i]["vers_date"]+"</a></td><td class='centered' class='ellipsis'><a title='"+arr[i]["acct_name"]+"'>"+arr[i]["acct_name"]+"</a></td><td class='ellipsis'><a title='"+arr[i]["vers_message"]+"'>"+arr[i]["vers_message"]+"</a></td><td class='centered quickview' id='"+arr[i]["vers_id"]+"'><a title='Quick View' id='"+arr[i]["vers_id"]+"' class='qv_btn'>Q</a></td><td class='centered revert' id='"+arr[i]["vers_id"]+"'><a title='Revert' id='"+arr[i]["vers_id"]+"' class='rev_btn'>R</a></td></tr>");
				}
			}
		}
	}
}