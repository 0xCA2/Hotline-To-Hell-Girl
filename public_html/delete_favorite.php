<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
	if (!isset($_SESSION["user_id"])) {
		redirect_to("log_in.php");
	}else if (!isset($_GET["user"])) {
		set_error_output("Favorite deletion failed. User not selected.", "index.php");
	}else if ($_SESSION["user_id"] != $_GET["user"]) {
		set_error_output("Favorite deletion failed. ", "index.php");
	}else if (!isset($_GET["episode"])) {
		set_error_output("Favorite deletion failed. Episode not selected. ", "search.php?epname=");
	}else if (!check_existance_by_id("episodes", "EpID", $_GET["episode"])) {
		set_error_output("Episode does not exist.", "search.php?epname=");
	}else if (!check_existance_by_id("users", "id", $_GET["user"])) {
		set_error_output("User does not exist. ", "index.php");
	}else if (!already_favorite($_GET["user"], $_GET["episode"])){
		set_error_output("You never favorited that", "video.php?e=" . urlencode($_GET["episode"]));
	}

	if (!delete_favorite($_GET["user"], $_GET["episode"])){
		set_error_output("Favorite deletion failed. Database error.", "video.php?e=" . urlencode($_GET["episode"]));
	}
?>

Favorite:<i class="fi-like" id="video_info_panel_favorite_icon"></i>
