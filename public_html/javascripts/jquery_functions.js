$(document).ready(function(){
   // jQuery methods go here...
   // For episode editor. 
	$("#episode_edit_selector").click(function() {
		var selection = $("#episode_edit_selector").val();
		if (selection != '0') {
			//$("#episode_edit_form").load("load_episode_data.php?" + "episode=" + selection);
			$("#episode_edit_form").attr("action", "edit_episode.php?episode=" + selection); 
			$("#episode_data").load("load_episode_data.php?" + "episode=" + selection);
			
			var delete_button = "<a href=\"delete_episode.php?episode=" + encodeURIComponent(selection) + "\"";
			delete_button += " onclick=\"return confirm('Are you sure?');\">Delete Page </a>";
			$("#edit_episode_delete_button").html(delete_button);
		
			/*
			$("#episode_edit_form").append($('<div>').load("load_episode_data.php?" + "episode=" + selection));
			*/
		}
	});
	
	// episode search submission 
	$("#episode_search_submit").click(function() {
		var episode_name = $("#search_episode_name_input").val();
		var season_selection =  $("#search_episode_season_selector").val();
		var category_selection = $("#search_category_selector").val();
		var episode_number_seletion = $("#search_episode_num_selector").val();
		var query_string = "cms_load_search_data.php?";
		
		var conditions = new Array(); 
		if (episode_name != "") conditions.push( "epname=" + episode_name );
		if (season_selection != 0) conditions.push( "season=" + season_selection );
		if (category_selection != 0) conditions.push( "category=" + category_selection );
		if (episode_number_seletion != 0) conditions.push( "epnum=" + episode_number_seletion );
		
		query_string += conditions.join("&");
		//console.log(query_string);
		$("#episode_search_results").load(query_string, function() {
				// page switching for search box 
				var numPages = $('.cms_episode_search_result_table').length;
				//console.log(numPages);
				if (numPages > 1 ) {
					//console.log(numPages);
					// To start, hide all pages but the first.
					$('.cms_episode_search_result_table').each(function() {
						if (this.id != "cms_episode_result_page_1") {
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
				}
				
				$(".cms_episode_search_result_row").each(function() {
					//console.log(this.id);
					$(this).find("td.cms_episode_search_result_name").on("click", {id: this.id},
					toggle_episode_table_data);
				});
		});
	});
	
	function switch_to_page() {
		var current_page_id = "cms_episode_result_page_" + $(this).html();
		$("#"+current_page_id).show();
		$('.cms_episode_search_result_table').each(function() {
			if (this.id != current_page_id)  {
				$(this).hide();
			}
		});		
	}
	
	function toggle_episode_table_data(event) {
		var id = event.data.id.substring(5);
		//console.log("i'm hre: " + id);
		
		var row_tag = "<tr class=\"cms_episode_search_result_row\" id=\"data_" + id + "\">";
		var link_url = "cms_search_load_episode_info.php?episode=" + id;
		
		if ($("#"+ "data_" + id).length==0) {
			// close all other tags 
			$(".cms_episode_search_result_row").each(function() {
				var ref = this;
				
				// if it's a data row 
				if (ref.id.indexOf("data_") > -1) {
					$(ref).hide();
				}
			});
			
			$("#" + event.data.id).after($(row_tag).load(link_url, function(){				
				$("#" + event.data.id).find("td.cms_episode_search_result_name").off("click", 
				toggle_episode_table_data);
				
				$("#" + event.data.id).find("td.cms_episode_search_result_name").on("click", {id: event.data.id},
				function() {
					$("#"+ "data_" + id).remove(); 
					$("#" + event.data.id).find("td.cms_episode_search_result_name").on("click", {id: event.data.id},
					toggle_episode_table_data);
				});
			}));
		}
	}
	
	/*
	$('#search_episode_form').submit(function() {

		//get all the inputs into an array 
		var $inputs = $("#search_episode_form :input");
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		
		var query_string = "cms_load_search_data.php?";
		if (values["EpName"] != "") query_string += "epname=" + values["EpName"];
		if (values["Season"] != 0) query_string += "&season=" + values["Season"];
		if (values["Category"] != 0) query_string += "&category=" + values["Season"];
		if (values["EpNum"] != 0) query_string += "&epnum=" + values["EpNum"];
		
		$("#episode_search_results").load(query_string);

	});*/
});