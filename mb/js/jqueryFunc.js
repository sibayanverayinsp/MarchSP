function checkField(id) {
	document.getElementById(id).value=document.getElementById(id).value.trim();
}

function checkpw() {
	if(document.getElementById("password").value!=document.getElementById("conf_password").value) {
		alert("Passwords do not match!");
		$("#conf_password").focus();
		$("#conf_password").css("border","2px solid red");
		return false;
	}
	else return true;
}

function loadLog(num) {
	$("div.log").load("modules/getLogs.php",{update:num});
}

function jqueryFunc() {
	$("div.repo").hide();
	$("div.comp").hide();
	$("div.logs").hide();
	$("#upload_div").hide();
	$("div.repo").slideDown("slow",function() {
		$("div.comp").slideDown("slow",function() {
			$("div.log").load("modules/getLogs.php",function() {
				$("div.logs").slideDown("slow");
			});
		});
	});
	setInterval(function() {
		$("div.log").load("modules/getLogs.php");
	},10000);

	//repo
	$("div.repositories").delegate(".repo-btn","click",function() {
		var btn_id=$(this).attr("id");
		$("a.repo-btn").removeClass("clicked");
		$(".repo-btn div").removeClass("clicked");
		$(".comp-btn div").removeClass("clicked");
		$(".ver-btn div").removeClass("clicked");
		$(".repositories a#"+btn_id+" div").addClass("clicked");
		$(".repositories a#"+btn_id).addClass("clicked");
		$(".components").slideUp("fast",function() {
			$(".components").load("modules/getComponents.php",{repo_id:btn_id},function() {
				$(".components").slideDown("fast");
			});
		});
	});
	$("div.components").delegate(".comp-btn","click",function() {
		var btn_id=$(this).attr("id");
		var btn_class=$(this).attr("class").split(" ")[1];
		$(".comp-btn div").removeClass("clicked");
		$(".ver-btn div").removeClass("clicked");
		$(".components a#"+btn_id+" div").addClass("clicked");
		$(".components div."+btn_id).slideUp("fast",function() {
			$(".components div."+btn_id).load("modules/getSubdir.php",{vers_id:btn_id},function() {
				if(btn_class=="even") {
					$(".components div."+btn_id).slideDown("fast");
					$(".components .cmp a#"+btn_id+".comp-btn").removeClass("even");
					$(".components .cmp a#"+btn_id+".comp-btn").addClass("odd");
				}
				else {
					$(".components .cmp a#"+btn_id+".comp-btn").removeClass("odd");
					$(".components .cmp a#"+btn_id+".comp-btn").addClass("even");
				}
			});
		});
	});
	//view versions
	$("div.components").delegate(".ver-btn","click",function() {
		var btn_class=$(this).attr("class").split(" ")[1];
		var btn_id=$(this).attr("id");
		var repo_id=$(".repo-btn.clicked").attr("id");
		var name=$("div.components a#"+btn_id+".ver-btn div").text();
		$(".comp-btn div").removeClass("clicked");
		$("div.components .ver-btn div").removeClass("clicked");
		$("div.components a#"+btn_id+".ver-btn div").addClass("clicked");
		$("div.vers_wrap").fadeIn();
		$("div.vers_wrap").focus();
		$("div.container").css("opacity","0.2");
		$("div.wall2").css("display","block");
		$(".versions").load("modules/getVersions.php",{ver_class:btn_class,ver_repo_id:repo_id,ver_name:name});
	});

	//add folder
	/*
	$(".components").delegate(".add_folder","click",function() {
		var btn_id=$(this).attr("id");
		var btn_class=$(".components .cmp a#"+btn_id+".comp-btn").attr("class").split(" ")[1];
		$(".components div."+btn_id).load("modules/getSubdir.php",{vers_id:btn_id},function() {
			if(btn_class=="even") {
				$(".components div."+btn_id).slideDown("fast");
				$(".components .cmp a#"+btn_id+".comp-btn").removeClass("even");
				$(".components .cmp a#"+btn_id+".comp-btn").addClass("odd");
			}
			$(".components div."+btn_id).append("<form action='modules/addFolder.php' method='post'><input type='text' class='folder_name"+btn_id+"' name='folder' id='folder_id"+btn_id+"' placeholder=' Folder Name' required='required'><input type='hidden' name='hidden_id' value='"+btn_id+"'><input type='submit' class='submit_btn' value='ADD'></form>");
			$(".folder_name"+btn_id).attr("onblur","checkField('folder_id"+btn_id+"')");
			$(".folder_name"+btn_id).focus();
		});
	});
	*/

	//upload
	$(".components").delegate(".upload","click",function() {
		var btn_id=$(this).attr("id");
		var repo_id=$(".repo-btn.clicked").attr("id");
		$("#upload_parent_id").val(btn_id);
		$("#upload_repo_id").val(repo_id);
		$("#upload_div").fadeIn();
		$("#upload_div").focus();
		$("div.container").css("opacity","0.2");
		$("div.wall").css("display","block");
	});
	$(".repositories").delegate(".upload","click",function() {
		var repo_id=$(this).attr("id");
		$("#upload_parent_id").val("0");
		$("#upload_repo_id").val(repo_id);
		$("#upload_div").fadeIn();
		$("#upload_div").focus();
		$("div.container").css("opacity","0.2");
		$("div.wall").css("display","block");
	});

	//close upload_div
	$("#upload_div").delegate(".a_close_btn","click",function() {
		$("#upload_div").fadeOut();
		$("div.container").animate({opacity:1.0});
		$("div.wall").css("display","none");
		$("#upload_form").clearForm();
	});

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
				var percentVal="0%";
				$(".bar").width(percentVal);
				$(".percent").html(percentVal);
				$("#proj").attr("disabled","disabled");
				$("#message").attr("disabled","disabled");
				$(".upload_btn").attr("disabled","disabled");
				$(".a_close_btn").css("display","none");
			},
			uploadProgress: function(event, position, total, percentComplete) {
				var percentVal=(percentComplete/2.0)+"%";
				$(".bar").width(percentVal);
				$(".percent").html(percentVal);
			},
			complete: showUploaded
		};
		$(this).ajaxSubmit(options);
		return false;
	});

	$(".repositories").delegate("#addrepo_form","submit",function() {
		var options={
			dataType: "json",
			success: showAddedRepo
		};
		$(this).ajaxSubmit(options);
		$("#repoName").val("");
		return false;
	});

	$("#chat-box").delegate("#chat_btn","click",function() {
		$("#chat-box").css("height","100px");
		$(".chat").css("display","block");
	});

	//download
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
		//$("#download_form").ajaxSubmit(options);
		$("#download_form").submit();
		$("div.container").css("opacity","1.0");
		$("div.wall3").css("display","none");
		$("#download_parent_id").val("");
		$("#download_repo_id").val("");
		$("#download_name").val("");
		$("#is_dir").val("");
		return false;
	});
	$(".components").delegate(".download","click",function() {
		var repo_id=$(".repo-btn.clicked").attr("id");
		var parent_id=$(this).attr("id");
		$("#download_parent_id").val(parent_id);
		$("#download_repo_id").val(repo_id);
		$("#download_name").val($(this).parent().parent().find("a#"+parent_id+" .div-button").text());
		if($(this).parent().parent().find("a#"+parent_id).attr("class").split(" ")[0]=="comp-btn") {
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
		//$("#download_form").ajaxSubmit(options);
		$("#download_form").submit();
		$("div.container").css("opacity","1.0");
		$("div.wall3").css("display","none");
		$("#download_parent_id").val("");
		$("#download_repo_id").val("");
		$("#download_name").val("");
		$("#is_dir").val("");
		return false;
	});

	//diff
	/*
	$("div.versions").delegate("input[name=from]","change",function() {
		$("#diff_from").val($("input[name=from]:checked").val());
	});
	$("div.versions").delegate("input[name=to]","change",function() {
		$("#diff_to").val($("input[name=to]:checked").val());
	});
	*/
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
			},
			error: function() {
				alert("error occurred");
			}
		};
		$("#diff_form").ajaxSubmit(options);
		//$("#diff_form").submit();
		return false;
	});

	//quickview
	$("div.versions").delegate("td.quickview","click",function() {
		var repo_id=$(".repo-btn.clicked").attr("id");
		$("#qv_repo_id").val(repo_id);
		$("#qv_file").val($(this).attr("id"));
		var options={
			success: function(data) {
				$("#diff_iframe").css("display","block");
				$("#diff_iframe").attr("src","modules/iframe.html");
				$("#qv_form").clearForm();
			},
			error: function() {
				alert("error occurred");
			}
		};
		$("#qv_form").ajaxSubmit(options);
		//$("#qv_form").submit();
		return false;
	});

	//show revert_div
	$("div.versions").delegate("td.revert","click",function() {
		$("#version_id").val($(this).attr("id"));
		$("#revert_div").fadeIn();
		$("#revert_div").focus();
		$("div.vers_wrap").css("opacity","0.2");
		$("div.wall4").css("display","block");
	});
	//close revert_div
	$("#revert_div").delegate(".a_close_btn","click",function() {
		$("#revert_div").fadeOut();
		$("div.vers_wrap").animate({opacity:1.0});
		$("div.wall4").css("display","none");
		$("#revert_form").clearForm();
	});

	//revert
	$("#revert_div").delegate("#revert_form","submit",function() {
		var options={
			complete: function() {
				alert("File successfully reverted!");
				$("#revert_div").fadeOut();
				$("div.vers_wrap").animate({opacity:1.0});
				$("div.wall4").css("display","none");
				$("#revert_form").clearForm();
				//update versions
				var btn_class=$(".ver-btn .div-button.clicked").parent().attr("class").split(" ")[1];
				var btn_id=$(".ver-btn .div-button.clicked").parent().attr("id");
				var repo_id=$(".repo-btn.clicked").attr("id");
				var name=$("div.components a#"+btn_id+".ver-btn div").text();
				$(".versions").load("modules/getVersions.php",{ver_class:btn_class,ver_repo_id:repo_id,ver_name:name});
			}
		};
		$(this).ajaxSubmit(options);
		return false;
	});

	//signup
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
}