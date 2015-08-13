<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php
	$episode_set  = episode_search("EpID");
	$episodes_per_page = 16;
	if ($episode_set == null) {
		die("Value not found. ");
	}else {
		$total_episodes = mysqli_num_rows($episode_set);
		$pages = (int) ceil($total_episodes / $episodes_per_page);

	}
?>
<?php 
	$table = "";
	if ($total_episodes == 0) {
		$table .= "No episodes found. ";
	}
	
	for ($page = 1; $page <= $pages; $page++) {
		$table .= "<script>console.log(\"pages:" . $pages . "\");</script>";
	    $table .= "<script>console.log(\"total_episodes:" . $total_episodes  . "\");</script>";
	    $table .= "<script>console.log(\"total_episodes / episodes_per_page:" . $total_episodes / $episodes_per_page . "\");</script>";
		$episode = mysqli_fetch_assoc($episode_set);
		/* sometimes this thing will say it has more rows than there are acutally episodes, 
		  this is coming from sql. I do this check here to stop the program from generating
		  an unecesary page. */ 
		if ($episode) {
			$table .= "<ul class=\"medium-block-grid-4 episode_search_result_table\" id=\"episode_result_page_" . $page . "\"";
			$table .= ">";
		
			$count = 1; 
			while ($episode && ($count <= $episodes_per_page)) {
				$table .= make_episode_cell($episode["EpID"]);
				$count++;
				$episode = mysqli_fetch_assoc($episode_set);

			}
					
			$table .= "</ul>";
		}
	}

	mysqli_free_result($episode_set);
	echo $table;
	
?>