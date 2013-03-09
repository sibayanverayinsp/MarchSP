function checkField(id) {
	document.getElementById(id).value=document.getElementById(id).value.trim();
}

function jqueryFunc() {
	$("div.repo").hide();
	$("div.comp").hide();
	$("#upload_div").hide();
	$("div.repo").slideDown("slow",function() {
		$("div.comp").slideDown("slow");
	});
	//hide upload div
	//$("#upload_div").css("position","relative");
	//$("#upload_div").css("top",Math.max(0,(($(window).height()-$("#upload_div").outerHeight())/2)+$(window).scrollTop())+"px");
	//$("#upload_div").css("left",Math.max(0,(($(window).width()-$("#upload_div").outerWidth())/2)+$(window).scrollLeft())+"px");
	
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
		//$(".versions").slideUp("fast",function() {
		$(".versions").load("modules/getVersions.php",{ver_class:btn_class,ver_repo_id:repo_id,ver_name:name});
				//,function() {
		//		$(".versions").slideDown("fast");
		//	});
		//});
	});
	/*
	$("div.versions").delegate(".ver-btn","click",function() {
		var btn_class=$(this).attr("class").split(" ")[1];
		var btn_id=$(this).attr("id");
		$("div.versions .ver-btn div").removeClass("clicked");
		$("div.versions a#"+btn_id+".ver-btn div").addClass("clicked");
	});
	
	//hover in components
	$(".components").delegate(".ver-btn","mouseenter",function() {
		var btn_id=$(this).attr("id");
		$("div#pop-up").load("modules/getVersionInfo.php",{ver_id:btn_id},function() {
			$("div#pop-up").show();
		});
	});
	$(".components").delegate(".ver-btn","mouseleave",function() {
		$("div#pop-up").hide();
	});
	var moveLeft=20;
	var moveDown=10;
	$(".components").delegate(".ver-btn","mousemove",function(event) {
		$("div#pop-up").css("top",event.pageY+moveDown).css("left",event.pageX+moveLeft);
	});
	//hover in versions
	$(".versions").delegate(".ver-btn","mouseenter",function() {
		var btn_id=$(this).attr("id");
		$("div#pop-up").load("modules/getVersionInfo.php",{ver_id:btn_id},function() {
			$("div#pop-up").show();
		});
	});
	$(".versions").delegate(".ver-btn","mouseleave",function() {
		$("div#pop-up").hide();
	});
	var moveLeft=20;
	var moveDown=10;
	$(".versions").delegate(".ver-btn","mousemove",function(event) {
		$("div#pop-up").css("top",event.pageY+moveDown).css("left",event.pageX+moveLeft);
	});
	*/

	//add folder
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
			}
		};
		$("#download_form").submit();
		$("div.container").css("opacity","1.0");
		$("div.wall3").css("display","none");
		$("#download_parent_id").val("");
		$("#download_repo_id").val("");
		$("#download_name").val("");
		return false;
	});

	//diff
	$("div.versions").delegate("input[name=from]","change",function() {
		$("#diff_from").val($("input[name=from]:checked").val());
	});
	$("div.versions").delegate("input[name=to]","change",function() {
		$("#diff_to").val($("input[name=to]:checked").val());
	});
	$("div.versions").delegate("a.diff_a","click",function() {
		var repo_id=$(".repo-btn.clicked").attr("id");
		$("#diff_repo_id").val(repo_id);
		var options={
			//target: "#diff_iframe",
			success: function(data) {
				$("#diff_iframe").css("display","block");
				$("#diff_iframe").attr("srcdoc",data);
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
}