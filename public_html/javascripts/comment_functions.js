var offset_num = 10;

$("#comment_submit_button").click(function() {
	var id = $("#comment_submit_form").attr("data-user-id");
	var ep_id  = $("#comment_submit_form").attr("data-ep-id");
	var comment_text = $("#comment_text_area").val();
	
	$.ajax({
		method: "POST",
		url : "submit_comment.php",
		data : {user_id : id , episode_id: ep_id, comment : comment_text, submit: "true"}
	})
	.done(function(data) {
		// this is how you get data back from ajax, you should do something with it later 
		if ($("#comment_panel").text().includes("No comments posted.")) {
			$("#comment_panel").html(' ');
		}
		if ($("#comment_sort").length == 0) {
			$("#comment_panel").prepend(data); 
		}else {
			$("#comment_sort").after(data);
		}
		set_downvote_event();
		set_upvote_event();
		
	}); 
	
});

$("#load_comments_button").click(function() {
	var ep_id = location.search.replace("?e=", "");
	var sort_type = $("#comment_sort").val();

	$.ajax({
		method: "POST",
		url : "load_more_comments.php",
		data : {episode_id: ep_id, offset: offset_num, sort: sort_type, submit: "true"}
	})
	.done(function(data) {
		// this is how you get data back from ajax, you should do something with it later 
		offset_num += 10;
		$("#load_comments_button").remove();
		$("#comment_panel").append(data); 
		set_downvote_event();
		set_upvote_event();
	}); 
});

$("#comment_panel").on("change", '#comment_sort', function() {
//$("#comment_sort").click(function() {
	var sort_type = $("#comment_sort").val();
	var ep_id = location.search.replace("?e=", "");

	$.ajax({
		method: "POST",
		url : "sort_comments.php",
		data : {episode_id: ep_id, sort: sort_type, submit: "true"}
	})
	.done(function(data) {
		// this is how you get data back from ajax, you should do something with it later 
		offset_num = 10;
		if ($("#load_comments_button").length != 0) {
			$("#load_comments_button").remove();
		}
		
		//$("#comment_panel").append(data); 
		$("#comment_panel").html(data); 
		
		set_downvote_event();
		set_upvote_event();
	}); 	
});

/* i hope to god this is done */
set_downvote_event();
set_upvote_event();
function set_comment_sort_event() {
	$("#comment_sort").click(function() {
		var sort_type = $("#comment_sort").val();
		var ep_id = location.search.replace("?e=", "");

		$.ajax({
			method: "POST",
			url : "sort_comments.php",
			data : {episode_id: ep_id, sort: sort_type, submit: "true"}
		})
		.done(function(data) {
			// this is how you get data back from ajax, you should do something with it later 
			offset_num = 10;
			if ($("#load_comments_button").length != 0) {
				$("#load_comments_button").remove();
			}
			//$("#comment_panel").append(data); 
			$("#comment_panel").html(data); 
			//console.log("i'm here in comment sort");
			set_downvote_event();
			set_upvote_event();

		}); 	
	});
}
function set_downvote_event() {
	$(".downvote_button").click(function () {
		var id = $("head").attr("data-user-id");
		var c_id = $(this).closest(".comment_output_panel").attr("data-comment-id");
		var ref = this;
		var vote = parseInt(remove_sign($(ref).siblings(".upvote_button").find(".vote_display_box").text()));
		
		if (id == '-1') { 
			// i'll just redirect them to the login page 
			window.location.replace("log_in.php");		
		}else{
			$.ajax({
				method: "POST",
				url : "downvote_comment.php",
				data : {user_id: id, comment_id: c_id, submit: "true"}
			})
			.done(function(data) {
								console.log("i'm here. ");

				// this is how you get data back from ajax, you should do something with it later 
				// set css to clicked 
				if ($(ref).hasClass("downvote_button_clicked")) {
					$(ref).removeClass("downvote_button_clicked");
					vote ++;
					//console.log("vote: " +  vote);
					$(ref).siblings(".upvote_button").find(".vote_display_box").text(add_sign_to_vote(vote));
					reset_vote_color($(ref).siblings(".upvote_button").find(".vote_display_box"), vote);					
				} else {
					$(ref).addClass("downvote_button_clicked");
					vote --;
					//console.log("vote: " +  vote);

					$(ref).siblings(".upvote_button").find(".vote_display_box").text(add_sign_to_vote(vote));
					reset_vote_color($(ref).siblings(".upvote_button").find(".vote_display_box"), vote);					
					if ($(ref).siblings(".upvote_button").hasClass("upvote_button_clicked")) {
						vote--;
						$(ref).siblings(".upvote_button").removeClass("upvote_button_clicked");
						reset_vote_color($(ref).siblings(".upvote_button").find(".vote_display_box"), vote);					
						$(ref).siblings(".upvote_button").find(".vote_display_box").text(add_sign_to_vote(vote));						
					}
				}
				//$(this).addClass(".downvote_button_clicked");
			});		
		}	
	});
}

function remove_sign(vote) {
	vote = vote.replace("+", "");
	return vote;
}

function add_sign_to_vote(vote) {
	if (vote > 0) {
		vote = "+" +vote;
	}
	return vote;
}
function replace_vote_color_class(vote_display_box, color_class, replace_with) {
	vote_display_box.removeClass(color_class);
	vote_display_box.addClass(replace_with);
}
//$(ref).find(".vote_display_box")
function reset_vote_color(vote_display_box, num) {
	if (num == 0) {
		if (!vote_display_box.hasClass("zero_votes")) {
			if (vote_display_box.hasClass("positive_votes")) {
				replace_vote_color_class(vote_display_box, "positive_votes", "zero_votes");
			}else if (vote_display_box.hasClass("negative_votes")){
				replace_vote_color_class(vote_display_box, "negative_votes", "zero_votes");
			}
		}
	}else if (num < 0) {
		if (!vote_display_box.hasClass("negative_votes")) {
			if (vote_display_box.hasClass("positive_votes")) {
				replace_vote_color_class(vote_display_box, "positive_votes", "negative_votes");
			}else if (vote_display_box.hasClass("zero_votes")){
				replace_vote_color_class(vote_display_box, "zero_votes", "negative_votes");				
			}
		}		
	}else if (num > 0){
		if (!vote_display_box.hasClass("positive_votes")) {
			if (vote_display_box.hasClass("zero_votes")) {
				replace_vote_color_class(vote_display_box, "zero_votes", "positive_votes");				
			}else if (vote_display_box.hasClass("negative_votes")){
				replace_vote_color_class(vote_display_box, "negative_votes", "positive_votes");				
			}
		}			
	}
}

function set_upvote_event() {
	$(".upvote_button").click(function () {
		var id = $("head").attr("data-user-id");
		var c_id = $(this).closest(".comment_output_panel").attr("data-comment-id");
		var ref = this;
		var vote = parseInt(remove_sign($(ref).find(".vote_display_box").text()));
		
		if (id == '-1') { 
			// i'll just redirect them to the login page 
			window.location.replace("log_in.php");
			
		}else {
			$.ajax({
				method: "POST",
				url : "upvote_comment.php",
				data : {user_id: id, comment_id: c_id, submit: "true"}
			})
			.done(function(data) {
				console.log("i'm here. ");
				// this is how you get data back from ajax, you should do something with it later 
				// set css to clicked 
				//$('[data-comment-id="' + c_id +'"]').addClass("upvote_button_clicked");
				if ($(ref).hasClass("upvote_button_clicked")) {
					$(ref).removeClass("upvote_button_clicked");
					vote --;
					console.log("vote: " +  vote);
					$(ref).find(".vote_display_box").text(add_sign_to_vote(vote));
					reset_vote_color($(ref).find(".vote_display_box"), vote);
				} else {
					$(ref).addClass("upvote_button_clicked");
					vote ++;
					console.log("vote: " +  vote);
					$(ref).find(".vote_display_box").text(add_sign_to_vote(vote));
					reset_vote_color($(ref).find(".vote_display_box"), vote);
					if ($(ref).siblings(".downvote_button").hasClass("downvote_button_clicked")) {
						vote++;
						$(ref).siblings(".downvote_button").removeClass("downvote_button_clicked");
						reset_vote_color($(ref).find(".vote_display_box"), vote);					
						$(ref).find(".vote_display_box").text(add_sign_to_vote(vote));
					}	
				}
				//$(this).addClass("upvote_button_clicked");
			});		
		}
		
	});	
}