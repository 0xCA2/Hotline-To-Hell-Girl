<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php

	
	$current_episode = get_episode_by_id($_GET["episode"]);
	if (!$current_episode) {
		// Subject ID was missing or invalid or 
		// subject couldn't be found in database. 
		redirect_to("manage_content.php");
	}
	
	
	$id = $current_episode["EpID"];
	$query = "DELETE FROM episodes WHERE EpID = {$id} LIMIT 1";
	
	$result =  mysqli_query($connection, $query);
	// Test if there was a query error 
	if ($result && mysqli_affected_rows($connection) == 1){
		// Success
		$_SESSION["message"] = "Episode deleted.";
		redirect_to("manage_content.php"); 
	}else {
		// Failure
		$_SESSION["message"] = "Episode deletion failed." . mysqli_error($connection);
		redirect_to("manage_content.php"); 
	}
?>