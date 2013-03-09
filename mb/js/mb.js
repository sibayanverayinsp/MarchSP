function showAddedRepo(data) {
	$("div.rep").append("<a class='repo-btn' id='"+data[0]["repo_id"]+"'><div class='div-button' style='width:75%;height:20px;line-height:18px;'><img src='images/folder.png' width='20px'>"+data[0]["repo_name"]+"</div></a><a title='Download'><div class='repo_dl download' id='"+data[0]["repo_id"]+"' style='line-height:25px;'>+</div></a><a title='Upload'><div class='repo_ul upload' id='"+data[0]["repo_id"]+"' style='line-height:25px;'>(+)</div></a>");
}


function lessWidth(str) {
	//alert($("table."+str).width());
	//alert($("."+str).parent().width());
}

function showUploaded() {
	$(".bar").width("100%");
	$(".percent").html("100%");
	alert($("#proj").val().split("\\")[2]+" successfully uploaded!");
	$("#proj").removeAttr("disabled");
	$("#message").removeAttr("disabled");
	$(".upload_btn").removeAttr("disabled");
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
	for(var i=0;i<arr.length;i++) {
		if(div=="rep") {
			$("div.rep").append("<a class='repo-btn' id='"+arr[i]["repo_id"]+"'><div class='div-button' style='width:75%;line-height:18px;'><img src='images/folder.png' width='20px'>"+arr[i]["repo_name"]+"</div></a><a title='Download'><div class='repo_dl download' id='"+arr[i]["repo_id"]+"' style='line-height:25px;'>+</div></a><a title='Upload'><div class='repo_ul upload' id='"+arr[i]["repo_id"]+"' style='line-height:25px;'>(+)</div></a>");
		}
		else if(div=="cmp") {
			var adddiv="", addclass="ver-btn "+arr[i]["vers_comp_id"], image="<img src='images/file.png' width='15px'>", isEven="", folder_btns="<a title='View info'><div class='dir_btns view_info' id='"+arr[i]["vers_id"]+"' style='line-height:25px;'>(+)</div></a>";
			if(arr[i]["vers_type"]=='dir') {
				adddiv="<div class='superDir "+arr[i]["vers_id"]+"' id='subdir"+arr[i]["vers_id"]+"'></div>";
				addclass="comp-btn";
				image="<img src='images/folder.png' width='20px'>";
				isEven="even";
				folder_btns="<a title='Upload'><div class='dir_btns upload' id='"+arr[i]["vers_id"]+"' style='line-height:25px;'>(+)</div></a><a title='Add folder'><div class='dir_btns add_folder' id='"+arr[i]["vers_id"]+"' style='line-height:25px;'>+</div></a>";
			}
			//$("div.cmp").append("<a class='"+addclass+" "+isEven+"' id='"+arr[i]["vers_id"]+"'><div class='div-button' style='width:70%;line-height:18px;float:left;'>"+image+arr[i]["vers_name"]+"</div></a>"+folder_btns+adddiv);
			$("div.cmp").append("<div class='superDir'><a class='"+addclass+" "+isEven+"' id='"+arr[i]["vers_id"]+"'><div class='div-button' style='line-height:18px;width:92%;'>"+image+arr[i]["vers_name"]+"</div></a>"+folder_btns+"</div>"+adddiv);
		}
		else if(div=="dir") {
			var adddiv="", addclass="ver-btn "+arr[i]["vers_comp_id"], image="<img src='images/file.png' width='15px'>", isEven="", folder_btns="<a title='View info'><div class='dir_btns view_info' id='"+arr[i]["vers_id"]+"' style='line-height:25px;'>(+)</div></a>";
			if(arr[i]["vers_type"]=='dir') {
				adddiv="<div class='"+arr[i]["vers_id"]+"'></div>";
				addclass="comp-btn";
				image="<img src='images/folder.png' width='20px'>";
				isEven="even";
				folder_btns="<a title='Upload'><div class='dir_btns upload' id='"+arr[i]["vers_id"]+"' style='line-height:25px;'>(+)</div></a><a title='Add folder'><div class='dir_btns add_folder' id='"+arr[i]["vers_id"]+"' style='line-height:25px;'>+</div></a>";
			}
			//$("div.vers"+arr[i]['vers_parent']).append("<a class='"+addclass+" "+isEven+"' id='"+arr[i]["vers_id"]+"'><div class='div-button' style='width:70%;line-height:18px;float:left;'>"+image+arr[i]["vers_name"]+"</div></a>"+folder_btns+adddiv);
			$("div.vers"+arr[i]['vers_parent']).append("<div class='superDir'><a class='"+addclass+" "+isEven+"' id='"+arr[i]["vers_id"]+"'><div class='div-button' style='line-height:18px;width:92%;'>"+image+arr[i]["vers_name"]+"</div></a>"+folder_btns+"</div>"+adddiv);
		}
		else if(div=="vers") {
			var adddiv="", addclass="ver-btn "+arr[i]["vers_comp_id"], image="<img src='images/file.png' width='15px'>";
			if(arr[i]["vers_type"]=='dir') {
				adddiv="<tr><td><div class='"+arr[i]["vers_id"]+"'></div></td></tr>";
				addclass="comp-btn";
				image="<img src='images/folder.png' width='20px'>";
			}
			//$("table.vers").append("<tr><td><a class='"+addclass+"' id='"+arr[i]["vers_id"]+"'><div class='div-button' style='width:93%;line-height:18px;'>"+image+arr[i]["vers_name"]+"</div></a></td></tr>"+adddiv);
			$("table.vers").append("<tr><td class='centered'><input type='radio' name='from' value='"+arr[i]["vers_id"]+"'></td><td class='centered'><input type='radio' name='to' value='"+arr[i]["vers_id"]+"'></td><td class='centered'>"+arr[i]["vers_repo_vers"]+"</td><td>"+arr[i]["vers_name"]+"</td><td>"+arr[i]["vers_date"]+"</td><td class='centered'>"+arr[i]["acct_name"]+"</td><td>"+arr[i]["vers_message"]+"</td><td class='centered'><a href=''>Q</a></td><td class='centered'><a href=''>R</a></td></tr>");
		}
	}
}

function getInfo(arr) {
	for(var i=0;i<arr.length;i++) {
		$("p.info").append("Version: "+arr[i]["vers_repo_vers"]+"<br>Date and Time: "+arr[i]["vers_date"]);
	}
}