function checkField(id) {
	document.getElementById(id).value = document.getElementById(id).value.trim();
	$(".addrepo_error_msg").fadeOut();
}

function jqueryFunc() {
	$("div.repo").slideDown("slow",function() {
		$("div.comp").slideDown("slow",function() {
			$("div.ver").slideDown("slow");
		});
	});
	$("div.repositories").delegate(".repo-btn","click",function() {
		var btn_id=$(this).attr("id");
		$("a.repo-btn").removeClass("clicked");
		$(".repo-btn div").removeClass("clicked");
		$(".comp-btn div").removeClass("clicked");
		$(".ver-btn div").removeClass("clicked");
		$(".repositories a#"+btn_id+" div").addClass("clicked");
		$(".repositories a#"+btn_id).addClass("clicked");
		$(".versions").slideUp("fast");
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
		$(".versions").slideUp("fast");
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
	$("div.components").delegate(".ver-btn","click",function() {
		var btn_class=$(this).attr("class").split(" ")[1];
		var btn_id=$(this).attr("id");
		$(".comp-btn div").removeClass("clicked");
		$("div.components .ver-btn div").removeClass("clicked");
		$("div.components a#"+btn_id+".ver-btn div").addClass("clicked");
		$(".versions").slideUp("fast",function() {
			$(".versions").load("modules/getVersions.php",{ver_class:btn_class},function() {
				$(".versions").slideDown("fast");
			});
		});
	});
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

	//add file
	$(".components").delegate(".add_file","click",function() {
		var btn_id=$(this).attr("id");
		var btn_class=$(".components .cmp a#"+btn_id+".comp-btn").attr("class").split(" ")[1];
		var repo_id=$(".repo-btn.clicked").attr("id");
		$("#upload_div").fadeIn();
		$("#upload_div").focus();
		$("div.container").css("opacity","0.2");
		$("div.container :input").attr("disable",true);
	});

	//lost focus of upload_div
	$("#upload_div").focusout(function() {
		$("#upload_div").fadeOut();
		$("div.container").css("opacity","1.0");
	});

	//add repo
	$(".repositories").delegate(".addrepo_btn","click",function() {
		if(document.getElementById('repoName').value=="") {
			$(".addrepo_error_msg").css("opacity","1");
			$(".addrepo_error_msg").hide();
			$(".addrepo_error_msg").fadeIn();
			$("#repoName").focus();
			return false;
		}
		var dataString='repoName='+document.getElementById('repoName').value;
		var btn_class=$(this).attr("class").split(" ")[1];
		$.ajax({
			type: "POST",
			url: "modules/addRepo.php",
			data: dataString,
			success: function() {
				$("table.rep").remove();
				$(".repositories").slideUp("fast",function() {
					$(".repositories").load("modules/getRepositories.php",{addrepo_class:btn_class},function() {
						$(".repositories").slideDown("fast",function() {
							alert("New repository successfully added!");
						});
					});
				});
			}
		});
		return false;
	});

	$("#chat-box").delegate("#chat_btn","click",function() {
		$("#chat-box").css("height","100px");
		$(".chat").css("display","block");
	});
}