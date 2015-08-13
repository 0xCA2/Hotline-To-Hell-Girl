$("#video_info_panel_favorite_icon").click(function() {
	var user_id = $("#video_info_panel_favorite").attr("data-user-id");
	var episode_id  = $("#video_info_panel_favorite").attr("data-ep-id");
	
	var query = "add_favorite.php?user=" + user_id + "&episode=" + episode_id;

	if (user_id == '-1') {
		// i'll just redirect them to the login page 
		window.location.replace("log_in.php");
	}else {
		$("#video_info_panel_favorite").load(query, function() {
			$("#video_info_panel_favorite_icon_clicked").click(function() {
				delete_favorite();
			});
		});		
	}
});

$("#video_info_panel_favorite_icon_clicked").click(function() {
	var user_id = $("#video_info_panel_favorite").attr("data-user-id");
	var episode_id  = $("#video_info_panel_favorite").attr("data-ep-id");
	
	var query = "delete_favorite.php?user=" + user_id + "&episode=" + episode_id;

	if (user_id == '-1') { 
		// i'll just redirect them to the login page 
		window.location.replace("log_in.php");
	}else {
		$("#video_info_panel_favorite").load(query, function() {
			$("#video_info_panel_favorite_icon").click(function() {
				add_favorite();
			});
		});	
	}
});

function delete_favorite() {
	var user_id = $("#video_info_panel_favorite").attr("data-user-id");
	var episode_id  = $("#video_info_panel_favorite").attr("data-ep-id");
	
	var query = "delete_favorite.php?user=" + user_id + "&episode=" + episode_id;

	if (user_id == '-1') { 
		// i'll just redirect them to the login page 
		window.location.replace("log_in.php");
	}else {
		$("#video_info_panel_favorite").load(query, function() {
			$("#video_info_panel_favorite_icon").click(function() {
				add_favorite();
			});
		});	

	}	
}

function add_favorite() {
	var user_id = $("#video_info_panel_favorite").attr("data-user-id");
	var episode_id  = $("#video_info_panel_favorite").attr("data-ep-id");
	
	var query = "add_favorite.php?user=" + user_id + "&episode=" + episode_id;
	
	if (user_id == '-1') {
		// i'll just redirect them to the login page 
		window.location.replace("log_in.php");
	}else {
		$("#video_info_panel_favorite").load(query, function() {
			$("#video_info_panel_favorite_icon_clicked").click(function() {
				delete_favorite();
			});
		});		
	}
	
}
favorite_deletion_code();

function favorite_deletion_code() {
	var ep_id_list = new Array();
	var user = $("head").attr("data-user-id");

	//.includes(id); 
	//array.indexOf
	//str = arr.join([separator = ','])
	//array.splice(start, deleteCount)
	
	$(".fav_delete_checkbox").find("input").click(function() {
		var id = $(this).val();
		
		var index = ep_id_list.indexOf(id);
		
		if (this.checked) {
			if (index == -1) {
				ep_id_list.push(id);
			}else {
				ep_id_list.splice(index, 1);
			}
			//console.log(ep_id_list);
			//console.log("Episodes set for deletion " + ep_id_list.join(" "));
		}else {
			ep_id_list.splice(index, 1);
			//console.log("Removed episode " + id);
			//console.log("Episodes set for deletion " + ep_id_list.join(" "));
		}

	});
	
	$("#delete_favorites_link").click(function() {
		if (ep_id_list.length > 0 && user != -1) {
			var ep_list_string = ep_id_list.join(" ");
			$.ajax({
				method: "POST",
				url : "delete_favorites.php",
				data : {user_id: user, episode_id_list: ep_list_string, submit: "true"}
			})
			.done(function(data) {
				location.reload();
			});
		}
	});
}