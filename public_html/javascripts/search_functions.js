// episode search submission 
$("#episode_search_submit").click(function() {
	console.log("i'm here.");
	var episode_name = $("#search_episode_name_input").val();
	var season_selection =  $("#search_episode_season_selector").val();
	var category_selection = $("#search_category_selector").val();
	var episode_number_seletion = $("#search_episode_num_selector").val();
	var query_string = "load_search_data.php?";
	
	var conditions = new Array(); 
	if (episode_name != "") conditions.push( "epname=" + episode_name );
	if (season_selection != 0) conditions.push( "season=" + season_selection );
	if (category_selection != 0) conditions.push( "category=" + category_selection );
	if (episode_number_seletion != 0) conditions.push( "epnum=" + episode_number_seletion );
	
	query_string += conditions.join("&");
	//console.log(query_string);
	$("#episode_search_results").load(query_string, function() {
		/*
			// page switching for search box 
			var numPages = $('.episode_search_result_table').length;
			//console.log(numPages);
			if (numPages > 1 ) {
				//console.log(numPages);
				// To start, hide all pages but the first.
				$('.episode_search_result_table').each(function() {
					if (this.id != "episode_result_page_1") {
						$("#"+this.id).hide();
					}
				});
				
				var link_div = "<div id=\"search_episode_page_selector\"></div>";
				$("#episode_search_results").append(link_div);
				
				// then we place all the links
				var link_list ="<ul>";
				for (var i = 1; i <= numPages; i++) {
					link_list += "<li> <a href=\"#\" id =\"episde_search_page_link_" + i + "\"";
					link_list += "class=\"search_page_link\" "
					link_list += ">";
					link_list += (i + "</a> </li>");
				}
				link_list += "</ul>";
				//console.log(link_list);
				
				$("#search_episode_page_selector").append(link_list);
				
				// then we say that if you click one link, hide everything else 
				$('.search_page_link').each(function() {
					// assign event handlers to all of thse 
					$(this).on("click", switch_to_page);
				});
			}*/
							make_pages();

	});
});

function switch_to_page() {
	var current_page_id = "episode_result_page_" + $(this).html();
	$("#"+current_page_id).show();
	$('.episode_search_result_table').each(function() {
		if (this.id != current_page_id)  {
			$(this).hide();
		}
	});		
}

function make_pages(insert_into) {
			insert_into = typeof insert_into !== 'undefined' ? insert_into : '#episode_search_results';
			
			//console.log("i'm here");
				var numPages = $('.episode_search_result_table').length;
			//console.log(numPages);
			if (numPages > 1 ) {
				//console.log(numPages);
				// To start, hide all pages but the first.
				$('.episode_search_result_table').each(function() {
					if (this.id != "episode_result_page_1") {
						$("#"+this.id).hide();
					}
				});
				
				var link_div = "<div id=\"search_episode_page_selector\"></div>";
				$(insert_into).append(link_div);
				
				// then we place all the links
				var link_list ="<ul>";
				for (var i = 1; i <= numPages; i++) {
					link_list += "<li> <a href=\"#\" id =\"episde_search_page_link_" + i + "\"";
					link_list += "class=\"search_page_link\" "
					link_list += ">";
					link_list += (i + "</a> </li>");
				}
				link_list += "</ul>";
				//console.log(link_list);
				
				$("#search_episode_page_selector").append(link_list);
				
				// then we say that if you click one link, hide everything else 
				$('.search_page_link').each(function() {
					// assign event handlers to all of thse 
					$(this).on("click", switch_to_page);
				});
			}
}
