function jqueryFunc() {
	$("div.repo").slideDown("slow",function() {
		$("div.comp").slideDown("slow",function() {
			$("div.ver").slideDown("slow");
		});
	});
	$(".repo-btn").click(function() {
		var btn_id=$(this).attr("id");
		$(".repo-btn div").removeClass("clicked");
		$(".comp-btn div").removeClass("clicked");
		$(".ver-btn div").removeClass("clicked");
		$(".repositories a#"+btn_id+" div").addClass("clicked");
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

	$(".components").delegate(".add_folder","click",function() {
		var btn_id=$(this).attr("id");
		var btn_class=$(".components .cmp a#"+btn_id+".comp-btn").attr("class").split(" ")[1];
		//alert($(".components div#subdir"+btn_id).attr("id"));
		$(".components div."+btn_id).load("modules/getSubdir.php",{vers_id:btn_id},function() {
			if(btn_class=="even") {
				$(".components div."+btn_id).slideDown("fast");
				$(".components .cmp a#"+btn_id+".comp-btn").removeClass("even");
				$(".components .cmp a#"+btn_id+".comp-btn").addClass("odd");
			}
		});
	});
}