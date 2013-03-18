function showAddedRepo(data) {
	$("div.rep").append("<a class='repo-btn' id='"+data[0]["repo_id"]+"'><div class='div-button' style='width:75%;height:20px;line-height:18px;'><img src='images/folder.png' width='20px'>"+data[0]["repo_name"]+"</div></a><a title='Download'><div class='repo_dl download' id='"+data[0]["repo_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a><a title='Upload'><div class='repo_ul upload' id='"+data[0]["repo_id"]+"'><img src='images/upload.png' width='20px' height='20px'></div></a>");
}

function showUploaded() {
	$(".bar").width("100%");
	$(".percent").html("100%");
	alert($("#proj").val().split("\\")[$("#proj").val().split("\\").length-1]+" successfully uploaded!");
	$("#proj").removeAttr("disabled");
	$("#message").removeAttr("disabled");
	$(".upload_btn").removeAttr("disabled");
	$(".a_close_btn").css("display","block");
	$("#proj").val("");
	$("#upload_div").fadeOut();
	$("div.container").animate({opacity:1.0});
	$("div.wall").css("display","none");
	$(".bar").width("0%");
	$(".percent").html("0%");
	var btn_id=$("#upload_repo_id").val();
	$(".comp-btn div").removeClass("clicked");
	$(".ver-btn div").removeClass("clicked");
	$(".repo-btn div").removeClass("clicked");
	$(".repo-btn").removeClass("clicked");
	$(".repositories a#"+btn_id+" div").addClass("clicked");
	$(".repositories a#"+btn_id).addClass("clicked");
	$(".components").slideUp("fast",function() {
		$(".components").load("modules/getComponents.php",{repo_id:btn_id},function() {
			$(".components").slideDown("fast");
		});
	});
	$("#upload_parent_id").val("");
	$("#upload_repo_id").val("");
	$("#message").val("");
}

function addRow(arr,div) {
	var len=arr.length;
	for(var i=0;i<len;i++) {
		if(div=="rep") {
			$("div.rep").append("<a class='repo-btn' id='"+arr[i]["repo_id"]+"'><div class='div-button' style='width:75%;line-height:18px;'><img src='images/folder.png' width='20px'>"+arr[i]["repo_name"]+"</div></a><a title='Download'><div class='repo_dl download' id='"+arr[i]["repo_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a><a title='Upload'><div class='repo_ul upload' id='"+arr[i]["repo_id"]+"'><img src='images/upload.png' width='20px' height='20px'></div></a>");
		}
		else if(div=="cmp") {
			var adddiv="", addclass="ver-btn "+arr[i]["vers_comp_id"], image="<img src='images/file.png' width='15px'>", isEven="", folder_btns="<a title='Download'><div class='dir_btns download' id='"+arr[i]["vers_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a>";
			if(arr[i]["vers_type"]=='dir') {
				adddiv="<div class='superDir "+arr[i]["vers_id"]+"' id='subdir"+arr[i]["vers_id"]+"'></div>";
				addclass="comp-btn";
				image="<img src='images/folder.png' width='20px'>";
				isEven="even";
				folder_btns="<a title='Download'><div class='dir_btns download' id='"+arr[i]["vers_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a><a title='Upload'><div class='dir_btns upload' id='"+arr[i]["vers_id"]+"'><img src='images/upload.png' width='20px' height='20px'></div></a>";
			}
			$("div.cmp").append("<div class='superDir'><a class='"+addclass+" "+isEven+"' id='"+arr[i]["vers_id"]+"'><div class='div-button' style='line-height:18px;width:89%;'>"+image+arr[i]["vers_name"]+"</div></a>"+folder_btns+"</div>"+adddiv);
		}
		else if(div=="dir") {
			var adddiv="", addclass="ver-btn "+arr[i]["vers_comp_id"], image="<img src='images/file.png' width='15px'>", isEven="", folder_btns="<a title='Download'><div class='dir_btns download' id='"+arr[i]["vers_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a>";
			if(arr[i]["vers_type"]=='dir') {
				adddiv="<div class='"+arr[i]["vers_id"]+"'></div>";
				addclass="comp-btn";
				image="<img src='images/folder.png' width='20px'>";
				isEven="even";
				folder_btns="<a title='Download'><div class='dir_btns download' id='"+arr[i]["vers_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a><a title='Upload'><div class='dir_btns upload' id='"+arr[i]["vers_id"]+"'><img src='images/upload.png' width='20px' height='20px'></div></a>";
			}
			$("div.vers"+arr[i]['vers_parent']).append("<div class='superDir'><a class='"+addclass+" "+isEven+"' id='"+arr[i]["vers_id"]+"'><div class='div-button' style='line-height:18px;width:89%;'>"+image+arr[i]["vers_name"]+"</div></a>"+folder_btns+"</div>"+adddiv);
		}
		else if(div=="vers") {
			var adddiv="", addclass="ver-btn "+arr[i]["vers_comp_id"], image="<img src='images/file.png' width='15px'>";
			if(arr[i]["vers_type"]=='dir') {
				adddiv="<tr><td><div class='"+arr[i]["vers_id"]+"'></div></td></tr>";
				addclass="comp-btn";
				image="<img src='images/folder.png' width='20px'>";
			}
			if(len==1) {
				$("table.vers").append("<tr class='not_header'><td class='centered'><input type='radio' name='from' value='"+arr[i]["vers_id"]+"' checked='true'></td><td class='centered'><input type='radio' name='to' value='"+arr[i]["vers_id"]+"' checked='true'></td><td class='centered'>"+arr[i]["vers_repo_vers"]+"</td><td class='ellipsis'><a title='"+arr[i]["vers_name"]+"'>"+arr[i]["vers_name"]+"</a></td><td class='ellipsis'><a title='"+arr[i]["vers_date"]+"'>"+arr[i]["vers_date"]+"</a></td><td class='centered' class='ellipsis'><a title='"+arr[i]["acct_name"]+"'>"+arr[i]["acct_name"]+"</a></td><td class='ellipsis'><a title='"+arr[i]["vers_message"]+"'>"+arr[i]["vers_message"]+"</a></td><td class='centered quickview' id='"+arr[i]["vers_id"]+"'><a title='Quick View' id='"+arr[i]["vers_id"]+"' class='qv_btn'>Q</a></td><td class='centered'></td></tr>");
			}
			else if(i==0) {
				$("table.vers").append("<tr class='not_header'><td class='centered'><input type='radio' name='from' value='"+arr[i]["vers_id"]+"'></td><td class='centered'><input type='radio' name='to' value='"+arr[i]["vers_id"]+"' checked='true'></td><td class='centered'>"+arr[i]["vers_repo_vers"]+"</td><td class='ellipsis'><a title='"+arr[i]["vers_name"]+"'>"+arr[i]["vers_name"]+"</a></td><td class='ellipsis'><a title='"+arr[i]["vers_date"]+"'>"+arr[i]["vers_date"]+"</a></td><td class='centered' class='ellipsis'><a title='"+arr[i]["acct_name"]+"'>"+arr[i]["acct_name"]+"</a></td><td class='ellipsis'><a title='"+arr[i]["vers_message"]+"'>"+arr[i]["vers_message"]+"</a></td><td class='centered quickview' id='"+arr[i]["vers_id"]+"'><a title='Quick View' id='"+arr[i]["vers_id"]+"' class='qv_btn'>Q</a></td><td class='centered'></td></tr>");
			}
			else if(i==1) {
				$("table.vers").append("<tr class='not_header'><td class='centered'><input type='radio' name='from' value='"+arr[i]["vers_id"]+"' checked='true'></td><td class='centered'><input type='radio' name='to' value='"+arr[i]["vers_id"]+"'></td><td class='centered'>"+arr[i]["vers_repo_vers"]+"</td><td class='ellipsis'><a title='"+arr[i]["vers_name"]+"'>"+arr[i]["vers_name"]+"</a></td><td class='ellipsis'><a title='"+arr[i]["vers_date"]+"'>"+arr[i]["vers_date"]+"</a></td><td class='centered' class='ellipsis'><a title='"+arr[i]["acct_name"]+"'>"+arr[i]["acct_name"]+"</a></td><td class='ellipsis'><a title='"+arr[i]["vers_message"]+"'>"+arr[i]["vers_message"]+"</a></td><td class='centered quickview' id='"+arr[i]["vers_id"]+"'><a title='Quick View' id='"+arr[i]["vers_id"]+"' class='qv_btn'>Q</a></td><td class='centered revert' id='"+arr[i]["vers_id"]+"'><a title='Revert' id='"+arr[i]["vers_id"]+"' class='rev_btn'>R</a></td></tr>");
			}
			else {
				$("table.vers").append("<tr class='not_header'><td class='centered'><input type='radio' name='from' value='"+arr[i]["vers_id"]+"'></td><td class='centered'><input type='radio' name='to' value='"+arr[i]["vers_id"]+"'></td><td class='centered'>"+arr[i]["vers_repo_vers"]+"</td><td class='ellipsis'><a title='"+arr[i]["vers_name"]+"'>"+arr[i]["vers_name"]+"</a></td><td class='ellipsis'><a title='"+arr[i]["vers_date"]+"'>"+arr[i]["vers_date"]+"</a></td><td class='centered' class='ellipsis'><a title='"+arr[i]["acct_name"]+"'>"+arr[i]["acct_name"]+"</a></td><td class='ellipsis'><a title='"+arr[i]["vers_message"]+"'>"+arr[i]["vers_message"]+"</a></td><td class='centered quickview' id='"+arr[i]["vers_id"]+"'><a title='Quick View' id='"+arr[i]["vers_id"]+"' class='qv_btn'>Q</a></td><td class='centered revert' id='"+arr[i]["vers_id"]+"'><a title='Revert' id='"+arr[i]["vers_id"]+"' class='rev_btn'>R</a></td></tr>");
			}
		}
	}
}

function getInfo(arr) {
	for(var i=0;i<arr.length;i++) {
		$("p.info").append("Version: "+arr[i]["vers_repo_vers"]+"<br>Date and Time: "+arr[i]["vers_date"]);
	}
}