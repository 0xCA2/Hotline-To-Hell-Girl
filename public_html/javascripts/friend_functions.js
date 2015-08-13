friend_deletion_code();

function friend_deletion_code() {
	var friend_id_list = new Array();
	var user = $("head").attr("data-user-id");

	//.includes(id); 
	//array.indexOf
	//str = arr.join([separator = ','])
	//array.splice(start, deleteCount)
	
	$(".friend_delete_checkbox").find("input").click(function() {
		var id = $(this).val();
		
		var index = friend_id_list.indexOf(id);
		
		if (this.checked) {
			if (index == -1) {
				friend_id_list.push(id);
			}else {
				friend_id_list.splice(index, 1);
			}
			//console.log(friend_id_list);
			//console.log("Friend set for deletion " + friend_id_list.join(" "));
		}else {
			friend_id_list.splice(index, 1);
			//console.log("Removed friend " + id);
			//console.log("Friend set for deletion " + friend_id_list.join(" "));
		}

	});
	
	$("#delete_friends_link").click(function() {
		if (friend_id_list.length > 0 && user != -1) {
			var friends_list_string = friend_id_list.join(" ");
			$.ajax({
				method: "POST",
				url : "delete_friends.php",
				data : {user_id: user, friend_id_list: friends_list_string, submit: "true"}
			})
			.done(function(data) {
				location.reload();
			});
		}
	});
}