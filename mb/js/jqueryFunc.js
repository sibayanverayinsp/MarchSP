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

//check if field is blank
function checkField(id) {
	document.getElementById(id).value=document.getElementById(id).value.trim();
}

//check if passwords are equal
function checkpw() {
	if(document.getElementById("password").value!=document.getElementById("conf_password").value) {
		alert("Passwords do not match!");
		$("#conf_password").focus();
		$("#conf_password").css("border","2px solid red");
		return false;
	}
	else return true;
}

//jQuery functions
function jqueryFunc() {
	$("div.repo").hide();
	$("div.comp").hide();
	$("div.logs").hide();
	$("#upload_div").hide();
	$("div.repo").slideDown("slow");
	$("div.comp").slideDown("slow");

	//open sign up form
	$("#login_form").delegate(".signup_btn","click",function() {
		$(".login_signup").slideUp("fast",function() {
			var one="1";
			$(".login_signup").load("../signup/index.php",{loaded:one},function() {
				$("#signup_form").append("<select class='type' name='type' id='type'><option value='Member'>Member</option></select><br><br><input type='submit' class='submit_btn' value='Sign Up'>&nbsp;&nbsp;&nbsp;<a href='' class='login_btn'>Log In</a>");
				if(location.host==$(".ip").text() || location.host=="localhost" || location.host=="127.0.0.1") {
					$(".type").append("<option value='Admin'>Admin</option>");
				}
				$(".login_signup").slideDown("fast");
			});
		});
	});

	//sign up
	$(".login_signup").delegate("#signup_form","submit",function() {
		var options={
			beforeSubmit: function() {
				$("#username").css("border","");
				$("#conf_password").css("border","");
				return checkpw();
			},
			success: function(data) {
				if(data=="noerr") {
					$("#signup_form").clearForm();
					document.getElementById("type").value="Member";
					alert("Account successfully added!");
					location.href="";
				}
				else {
					alert("Username already exists!");
					$("#username").focus();
					$("#username").css("border","2px solid red");
				}
			}
		};
		$(this).ajaxSubmit(options);
		return false;
	});

	//logout
	$("div.container").delegate("a.logout","click",function() {
		$.ajax({
			url: "logout/index.php",
			data: {
				logout: 1
			},
			type: "POST"
		});
	});

	//load logs
	$("div.log").load("modules/getLogs.php",{isCleared:0},function() {
		$("div.logs").slideDown("slow");
	});
	setInterval(function() {
		$("div.log").load("modules/getLogs.php",{isCleared:0});
	},1000);

	//clear logs
	$("div.logs").delegate(".clear_log_btn","click",function() {
		$("div.log").load("modules/getLogs.php",{isCleared:1},function() {
			alert("Logs successfully cleared!");
		});
	});

	//repository
	$("div.repositories").delegate(".repo-btn","click",function() {
		var btn_id=$(this).attr("id");
		$("a.repo-btn").removeClass("clicked");
		$(".repo-btn div").removeClass("clicked");
		$(".fileFolder-btn div").removeClass("clicked");
		$(".ver-btn div").removeClass("clicked");
		$(".repositories a#"+btn_id+" div").addClass("clicked");
		$(".repositories a#"+btn_id).addClass("clicked");
		$(".fileFolders").hide();
		$(".fileFolders").load("modules/getFileFolders.php",{repo_id:btn_id},function() {
			$(".fileFolders").show();
		});
	});

	//files and folders
	$("div.fileFolders").delegate(".fileFolder-btn","click",function() {
		var btn_id=$(this).attr("id");
		var btn_class=$(this).attr("class").split(" ")[1];
		$(".fileFolder-btn div").removeClass("clicked");
		$(".ver-btn div").removeClass("clicked");
		$(".fileFolders a#"+btn_id+" div").addClass("clicked");
		$(".fileFolders div."+btn_id).hide();
		$(".fileFolders div."+btn_id).load("modules/getSubdir.php",{vers_id:btn_id},function() {
			if(btn_class=="even") {
				$(".fileFolders div."+btn_id).show();
				$(".fileFolders .cmp a#"+btn_id+".fileFolder-btn").removeClass("even");
				$(".fileFolders .cmp a#"+btn_id+".fileFolder-btn").addClass("odd");
			}
			else {
				$(".fileFolders .cmp a#"+btn_id+".fileFolder-btn").removeClass("odd");
				$(".fileFolders .cmp a#"+btn_id+".fileFolder-btn").addClass("even");
			}
		});
	});

	//versions
	$("div.fileFolders").delegate(".ver-btn","click",function() {
		var btn_class=$(this).attr("class").split(" ")[1];
		var btn_id=$(this).attr("id");
		var repo_id=$(".repo-btn.clicked").attr("id");
		var name=$("div.fileFolders a#"+btn_id+".ver-btn div").text();
		$(".fileFolder-btn div").removeClass("clicked");
		$("div.fileFolders .ver-btn div").removeClass("clicked");
		$("div.fileFolders a#"+btn_id+".ver-btn div").addClass("clicked");
		$("div.vers_wrap").fadeIn();
		$("div.vers_wrap").focus();
		$("div.container").css("opacity","0.2");
		$("div.wall2").css("display","block");
		$(".versions").load("modules/getVersions.php",{ver_class:btn_class,ver_repo_id:repo_id,ver_name:name});
	});

	//add repository
	$(".repositories").delegate("#addrepo_form","submit",function() {
		var options={
			dataType: "json",
			success: function(data) {
				$("div.rep").append("<a class='repo-btn' id='"+data[0]["repo_id"]+"'><div class='div-button' style='width:75%;height:20px;line-height:18px;'><img src='images/folder.png' width='20px'>"+data[0]["repo_name"]+"</div></a><a title='Download'><div class='repo_dl download' id='"+data[0]["repo_id"]+"'><img src='images/download.ico' width='20px' height='20px'></div></a><a title='Upload'><div class='repo_ul upload' id='"+data[0]["repo_id"]+"'><img src='images/upload.png' width='20px' height='20px'></div></a>");
				$("#addrepo_form").clearForm();
			}
		};
		$(this).ajaxSubmit(options);
		return false;
	});

	//open upload repository form
	$(".repositories").delegate(".upload","click",function() {
		var repo_id=$(this).attr("id");
		$("#upload_parent_id").val("0");
		$("#upload_repo_id").val(repo_id);
		$("#upload_div").fadeIn();
		$("#upload_div").focus();
		$("div.container").css("opacity","0.2");
		$("div.wall").css("display","block");
	});

	//open upload file or folder form
	$(".fileFolders").delegate(".upload","click",function() {
		var isLocked=$(this).parent().parent().find("div.lock").parent().attr("title");
		if(isLocked=="Unlock") {
			alert("Cannot upload in locked folder!");
		}
		else {
			var btn_id=$(this).attr("id");
			var repo_id=$(".repo-btn.clicked").attr("id");
			$("#upload_parent_id").val(btn_id);
			$("#upload_repo_id").val(repo_id);
			$("#upload_div").fadeIn();
			$("#upload_div").focus();
			$("div.container").css("opacity","0.2");
			$("div.wall").css("display","block");
		}
		
	});

	//close upload form
	$("#upload_div").delegate(".a_close_btn","click",function() {
		$("#upload_div").fadeOut();
		$("div.container").animate({opacity:1.0});
		$("div.wall").css("display","none");
		$("#upload_form").clearForm();
	});

	//close versions
	$("div.vers_wrap").delegate(".vers_close_btn","click",function() {
		$("div.vers_wrap").fadeOut();
		$("div.container").animate({opacity:1.0});
		$("div.wall2").css("display","none");
	});

	//commit
	$("#upload_div").delegate("#upload_form","submit",function() {
		if($("#proj").val()=="") {
			return false;
		}
		var options={
			beforeSend: function() {
				$("#proj").attr("disabled","disabled");
				$("#message").attr("disabled","disabled");
				$(".upload_btn").attr("disabled","disabled");
				$(".a_close_btn").css("display","none");
				$(".locking").fadeIn();
				$(".locking").focus();
				$("div.wall-lock").css("display","block");
			},
			complete: function() {
				alert($("#proj").val().split("\\")[$("#proj").val().split("\\").length-1]+" successfully uploaded!");
				$(".locking").fadeOut();
				$("div.wall-lock").css("display","none");
				$("#proj").removeAttr("disabled");
				$("#message").removeAttr("disabled");
				$(".upload_btn").removeAttr("disabled");
				$(".a_close_btn").css("display","block");
				$("#proj").val("");
				$("#upload_div").fadeOut();
				$("div.container").animate({opacity:1.0});
				$("div.wall").css("display","none");
				var btn_class=$("#upload_parent_id").val();
				$(".fileFolder-btn div").removeClass("clicked");
				$(".ver-btn div").removeClass("clicked");
				if(btn_class=="0") {
					var btn_id=$("#upload_repo_id").val();
					$(".repo-btn, .repo-btn div").removeClass("clicked");
					$("a#"+btn_id+".repo-btn, a#"+btn_id+".repo-btn div").addClass("clicked");
					$(".fileFolders").hide();
					$(".fileFolders").load("modules/getFileFolders.php",{repo_id:btn_id},function() {
						$(".fileFolders").show();
					});
				}
				else {
					$(".fileFolders a#"+btn_class+" div").addClass("clicked");
					$(".fileFolders div."+btn_class).hide();
					$(".fileFolders div."+btn_class).load("modules/getSubdir.php",{vers_id:btn_class},function() {
						$(".fileFolders div."+btn_class).show();
						$(".fileFolders .cmp a#"+btn_class+".fileFolder-btn").removeClass("even");
						$(".fileFolders .cmp a#"+btn_class+".fileFolder-btn").addClass("odd");
					});
				}
				$("#upload_parent_id").val("");
				$("#upload_repo_id").val("");
				$("#message").val("");
			}
		};
		$(this).ajaxSubmit(options);
		return false;
	});

	//download repository
	$(".repositories").delegate(".download","click",function() {
		var repo_id=$(this).attr("id");
		$("#download_parent_id").val("0");
		$("#download_repo_id").val(repo_id);
		$("#download_name").val($(this).parent().parent().find("a#"+repo_id+" .div-button").text());
		$("#is_dir").val(true);
		$("div.container").css("opacity","0.2");
		$("div.wall3").css("display","block");
		var options={
			success: function() {
				location.href=location.href+"modules/download/downloads/files.zip";
				$("div.container").css("opacity","1.0");
				$("div.wall3").css("display","none");
				$("#download_parent_id").val("");
				$("#download_repo_id").val("");
				$("#download_name").val("");
				$("#is_dir").val("");
			}
		};
		$("#download_form").submit();
		$("div.container").css("opacity","1.0");
		$("div.wall3").css("display","none");
		$("#download_parent_id").val("");
		$("#download_repo_id").val("");
		$("#download_name").val("");
		$("#is_dir").val("");
		return false;
	});

	//download file or folder
	$(".fileFolders").delegate(".download","click",function() {
		var repo_id=$(".repo-btn.clicked").attr("id");
		var parent_id=$(this).attr("id");
		$("#download_parent_id").val(parent_id);
		$("#download_repo_id").val(repo_id);
		$("#download_name").val($(this).parent().parent().find("a#"+parent_id+" .div-button").text());
		if($(this).parent().parent().find("a#"+parent_id).attr("class").split(" ")[0]=="fileFolder-btn") {
			$("#is_dir").val("true");
		}
		else {
			$("#is_dir").val("false");
		}
		$("div.container").css("opacity","0.2");
		$("div.wall3").css("display","block");
		var options={
			success: function() {
				location.href=location.href+"modules/download/downloads/files.zip";
				$("div.container").css("opacity","1.0");
				$("div.wall3").css("display","none");
				$("#download_parent_id").val("");
				$("#download_repo_id").val("");
				$("#download_name").val("");
				$("#is_dir").val("");
			}
		};
		$("#download_form").submit();
		$("div.container").css("opacity","1.0");
		$("div.wall3").css("display","none");
		$("#download_parent_id").val("");
		$("#download_repo_id").val("");
		$("#download_name").val("");
		$("#is_dir").val("");
		return false;
	});

	//open delete form
	$(".fileFolders").delegate(".delete","click",function() {
		var isLocked=$(this).parent().parent().find("div.lock").parent().attr("title");
		var versid=$(this).attr("id");
		if(isLocked=="Unlock") {
			alert("Cannot delete locked file/folder!");
		}
		else {
			$.ajax({
				url: "modules/findLock.php",
				data: {
					id: versid
				},
				type: "POST",
				success: function (data) {
					if(data=="has locked!") {
						alert("Cannot delete folder containing locked file/folder!");
					}
					else {
						$("#del_version_id").val(versid);
						$("#del_div").fadeIn();
						$("#del_div").focus();
						$("div.container").css("opacity","0.2");
						$("div.wall5").css("display","block");
					}
				}
			});
		}
	});

	//close delete form
	$("#del_div").delegate(".a_close_btn","click",function() {
		$("#del_div").fadeOut();
		$("div.container").animate({opacity:1.0});
		$("div.wall5").css("display","none");
		$("#delete_form").clearForm();
	});

	//delete
	$("#del_div").delegate("#delete_form","submit",function() {
		var options={
			beforeSend: function() {
				$("#delete_message").attr("disabled","disabled");
				$(".delete_btn").attr("disabled","disabled");
				$(".a_close_btn").css("display","none");
				$(".locking").fadeIn();
				$(".locking").focus();
				$("div.wall-lock").css("display","block");
			},
			complete: function() {
				alert("File/s successfully deleted!");
				$(".locking").fadeOut();
				$("div.wall-lock").css("display","none");
				$("#delete_message").removeAttr("disabled");
				$(".delete_btn").removeAttr("disabled");
				$(".a_close_btn").css("display","block");
				$("#del_div").fadeOut();
				$("div.container").animate({opacity:1.0});
				$("div.wall5").css("display","none");
				var btn_class=$("#"+$("#del_version_id").val()+".delete").parent().parent().parent().parent().attr("class").split(" ")[1];
				if(btn_class==undefined) {
					var btn_class=$("#"+$("#del_version_id").val()+".delete").parent().parent().parent().parent().attr("class");
				}
				if(btn_class=="fileFolders") {
					var btn_id=$(".repo-btn.clicked").attr("id");
					$(".fileFolders").hide();
					$(".fileFolders").load("modules/getFileFolders.php",{repo_id:btn_id},function() {
						$(".fileFolders").show();
					});
				}
				else {
					$(".ver-btn div").removeClass("clicked");
					$(".fileFolder-btn div").removeClass("clicked");
					$(".fileFolders a#"+btn_class+" div").addClass("clicked");
					$(".fileFolders div."+btn_class).hide();
					$(".fileFolders div."+btn_class).load("modules/getSubdir.php",{vers_id:btn_class},function() {
						$(".fileFolders div."+btn_class).show();
					});
				}
				$("#delete_form").clearForm();
			}
		};
		$(this).ajaxSubmit(options);
		return false;
	});

	//lock or unlock
	$(".fileFolders").delegate(".lock","click",function() {
		var id=$(this).attr("id");
		var a=$(this);
		$.ajax({
			beforeSend: function() {
				$(".locking").fadeIn();
				$(".locking").focus();
				$("div.container").css("opacity","0.2");
				$("div.wall-lock").css("display","block");
			},
			url: "modules/lockUnlock.php",
			data: {
				lock_id: id
			},
			type: "POST",
			success: function(data) {
				$(".locking").fadeOut();
				$("div.container").animate({opacity:1.0});
				$("div.wall-lock").css("display","none");
				if(data=="cannot unlock!") {
					alert("You cannot unlock this file/folder!");
				}
				else if(data=="locked!") {
					alert("File successfully locked!");
				}
				else if(data=="unlocked!") {
					alert("File successfully unlocked!");
				}
				else if(data=="folder_locked!") {
					alert("Folder successfully locked!");
				}
				else if(data=="folder_unlocked!"){
					alert("Folder successfully unlocked!");
				}
				var btn_class2=a.parent().parent().parent().parent().attr("class");
				if(btn_class2=="fileFolders") {
					var btn_id=$(".repo-btn.clicked").attr("id");
					$(".fileFolders").hide();
					$(".fileFolders").load("modules/getFileFolders.php",{repo_id:btn_id},function() {
						$(".fileFolders").show();
					});	
				}
				else {
					var btn_class=btn_class2.split(" ")[1];
					if(btn_class==undefined) {
						btn_class=btn_class2.split(" ")[0];
					}
					$(".ver-btn div").removeClass("clicked");
					$(".fileFolder-btn div").removeClass("clicked");
					$(".fileFolders a#"+btn_class+" div").addClass("clicked");
					$(".fileFolders div."+btn_class).hide();
					$(".fileFolders div."+btn_class).load("modules/getSubdir.php",{vers_id:btn_class},function() {
						$(".fileFolders div."+btn_class).show();
					});
				}
			}
		});
	});

	//get difference of two versions
	$("div.versions").delegate("td.diff_td","click",function() {
		var repo_id=$(".repo-btn.clicked").attr("id");
		$("#diff_repo_id").val(repo_id);
		$("#diff_from").val($("input[name=from]:checked").val());
		$("#diff_to").val($("input[name=to]:checked").val());
		var options={
			success: function(data) {
				$("#diff_iframe").css("display","block");
				$("#diff_iframe").attr("src","modules/iframe.html");
				$("#diff_form").clearForm();
			}
		};
		$("#diff_form").ajaxSubmit(options);
		return false;
	});

	//quick view a version
	$("div.versions").delegate("td.quickview","click",function() {
		var repo_id=$(".repo-btn.clicked").attr("id");
		$("#qv_repo_id").val(repo_id);
		$("#qv_file").val($(this).attr("id"));
		var options={
			success: function(data) {
				$("#diff_iframe").css("display","block");
				$("#diff_iframe").attr("src","modules/iframe.html");
				$("#qv_form").clearForm();
			}
		};
		$("#qv_form").ajaxSubmit(options);
		return false;
	});

	//open revert form
	$("div.versions").delegate("td.revert","click",function() {
		if($("span.isLocked").text()=="(Locked)") {
			alert("Cannot revert locked file/s!");
		}
		else {
			$("#version_id").val($(this).attr("id"));
			$("#revert_div").fadeIn();
			$("#revert_div").focus();
			$("div.vers_wrap").css("opacity","0.2");
			$("div.wall4").css("display","block");
		}
	});

	//close revert
	$("#revert_div").delegate(".a_close_btn","click",function() {
		$("#revert_div").fadeOut();
		$("div.vers_wrap").animate({opacity:1.0});
		$("div.wall4").css("display","none");
		$("#revert_form").clearForm();
	});

	//revert
	$("#revert_div").delegate("#revert_form","submit",function() {
		var options={
			dataType: "html",
			success: function(data) {
				$("#revert_div").fadeOut();
				$("div.vers_wrap").animate({opacity:1.0});
				$("div.wall4").css("display","none");
				$("#revert_form").clearForm();
				alert("File successfully reverted!");
				var btn_class=$(".ver-btn .div-button.clicked").parent().attr("class").split(" ")[1];
				var btn_id=$(".ver-btn .div-button.clicked").parent().attr("id");
				var repo_id=$(".repo-btn.clicked").attr("id");
				var name=$("div.fileFolders a#"+btn_id+".ver-btn div").text();
				$(".versions").load("modules/getVersions.php",{ver_class:btn_class,ver_repo_id:repo_id,ver_name:name});
			}
		};
		$(this).ajaxSubmit(options);
		return false;
	});

	//show deleted files
	$(".container").delegate(".show_deleted","click",function() {
		$("div.deleted_files").load("modules/showDeleted.php",{sort:"default",order:"desc"},function() {
			$("div#show_del").fadeIn();
			$("div#show_del").focus();
		});
		$("div.container").css("opacity","0.2");
		$("div.wall6").css("display","block");
	});

	//close deleted files
	$("#show_del").delegate(".vers_close_btn","click",function() {
		$("div#show_del").fadeOut();
		$("div.container").animate({opacity:1.0});
		$("div.wall6").css("display","none");
	});

	//undo delete a file
	$("div.deleted_files").delegate("td.undelete","click",function() {
		$.ajax({
			url: "modules/delete.php",
			data: {
				undel_version_id: $(this).attr("id")
			},
			type: "POST",
			success: function() {
				alert("File successfully undeleted!");
				$("div.deleted_files").load("modules/showDeleted.php",{sort:"default",order:"desc"});
			}
		});
	});

	//sort by path
	$("div.deleted_files").delegate("td.sort-path","click",function() {
		if($(this).attr("class").split(" ")[2]=="desc") {
			$("div.deleted_files").load("modules/showDeleted.php",{sort:"path",order:"asc"});
		}
		else {
			$("div.deleted_files").load("modules/showDeleted.php",{sort:"path",order:"desc"});
		}
	});

	//sort by name
	$("div.deleted_files").delegate("td.sort-name","click",function() {
		if($(this).attr("class").split(" ")[2]=="desc") {
			$("div.deleted_files").load("modules/showDeleted.php",{sort:"name",order:"asc"});
		}
		else {
			$("div.deleted_files").load("modules/showDeleted.php",{sort:"name",order:"desc"});
		}
	});

	//sort by date
	$("div.deleted_files").delegate("td.sort-date","click",function() {
		if($(this).attr("class").split(" ")[2]=="desc") {
			$("div.deleted_files").load("modules/showDeleted.php",{sort:"date",order:"asc"});
		}
		else {
			$("div.deleted_files").load("modules/showDeleted.php",{sort:"date",order:"desc"});
		}
	});

	//sort by user
	$("div.deleted_files").delegate("td.sort-user","click",function() {
		if($(this).attr("class").split(" ")[2]=="desc") {
			$("div.deleted_files").load("modules/showDeleted.php",{sort:"user",order:"asc"});
		}
		else {
			$("div.deleted_files").load("modules/showDeleted.php",{sort:"user",order:"desc"});
		}
	});

	//sort by comment
	$("div.deleted_files").delegate("td.sort-comment","click",function() {
		if($(this).attr("class").split(" ")[2]=="desc") {
			$("div.deleted_files").load("modules/showDeleted.php",{sort:"comment",order:"asc"});
		}
		else {
			$("div.deleted_files").load("modules/showDeleted.php",{sort:"comment",order:"desc"});
		}
	});
}