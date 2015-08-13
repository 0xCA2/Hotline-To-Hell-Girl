<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	$episode_set  = episode_search();
	$episodes_per_page = 12;
	if ($episode_set == null) {
		die("Value not found. ");
	}else {
		$total_episodes = mysqli_num_rows($episode_set);
		$pages = (int) ceil($total_episodes / $episodes_per_page);

	}
?>
<?php 
	$table = "";
	for ($page = 1; $page <= $pages; $page++) {
		
		$table .= "<table class=\"cms_episode_search_result_table\" id=\"cms_episode_result_page_" . $page . "\"";
		$table .= "<thead>";	 
		$table .= "<th>Episodes</th>";
		$table .= "<th>Actions</th>";
		$table .= "</thead>";
		$table .= "<tbody>";
		
		$count = 1; 
		$episode = mysqli_fetch_assoc($episode_set);
		while ($episode && ($count <= $episodes_per_page)) {
			$table .= "<tr id=\"EpID_". $episode["EpID"]  ."\" class=\"cms_episode_search_result_row\">";
			$table .= "<td class=\"cms_episode_search_result_name\">" . htmlentities($episode["EpName"]) . "</td>";
			$table .= "<td><a href=\"edit_episode.php?episode=".urlencode($episode["EpID"]) . "\">Edit</a>";
			$table .= "  <a href=\"delete_episode.php?episode=".urlencode($episode["EpID"]) . "\"";
			$table .= " onclick=\"return confirm('Are you sure?');\"";
			$table .= ">Delete</a>";
			$table .= "</td>";
			$table .= "</tr>";
			$count++;
			$episode = mysqli_fetch_assoc($episode_set);

		}
				
		$table .= "</tbody>";
		$table .= "</table>";
	}

	mysqli_free_result($episode_set);
	echo $table;
	
?>