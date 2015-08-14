<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
	if (!isset($_SESSION["user_id"])) {
		set_error_output("Please log in to delete favorite. ");
	}else if (!isset($_GET["user"])) {
		set_error_output("Favorite deletion failed. User not selected.");
	}else if ($_SESSION["user_id"] != $_GET["user"]) {
		set_error_output("Favorite deletion failed. ");
	}else if (!isset($_GET["episode"])) {
		set_error_output("Favorite deletion failed. Episode not selected. ");
	}else if (!check_existance_by_id("episodes", "EpID", $_GET["episode"])) {
		set_error_output("Episode does not exist." . check_existance_by_id("episodes", "EpID", $_GET["episode"]));
	}else if (!check_existance_by_id("users", "id", $_GET["user"])) {
		set_error_output("User does not exist. ");
	}else if (!already_favorite($_GET["user"], $_GET["episode"])){
		set_error_output("You never favorited that");
	}
	
	if (!delete_favorite($_GET["user"], $_GET["episode"])){
		set_error_output("Favorite deletion failed. Database error.");
	}
?>

Favorite:<i class="fi-like" id="video_info_panel_favorite_icon"></i>

