<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
if (!isset($_SESSION["user_id"])) {
	set_error_output("Please log in to favorite video. ", "video.php?e=" . urlencode($_GET["episode"]));
}else if (!isset($_GET["user"])) {
	set_error_output("Favorite failed. User not selected.", "video.php?e=" . urlencode($_GET["episode"]));
}else if ($_SESSION["user_id"] != $_GET["user"]) {
	set_error_output("Favorite failed. ", "video.php?e=" . urlencode($_GET["episode"]));
}else if (!isset($_GET["episode"])) {
	set_error_output("Favorite failed. Episode not selected. ", "search.php?epname=+");
}else if (!check_existance_by_id("episodes", "EpID", $_GET["episode"])) {
	set_error_output("Episode does not exist.", "search.php?epname=+");
}else if (!check_existance_by_id("users", "id", $_GET["user"])) {
	set_error_output("User does not exist. ", "video.php?e=" . urlencode($_GET["episode"]));
}else if (already_favorite($_GET["user"], $_GET["episode"])){
	set_error_output("You have already favorited that", "video.php?e=" . urlencode($_GET["episode"]));
}


	add_favorite($_GET["user"], $_GET["episode"]);

	// now that we know it was added, we can now output the html to replace the other.
?>
<!--span data-user-id="<?php /*
	if (isset($_SESSION["user_id"])) {
		echo $_SESSION["user_id"];
	} else {
		echo "-1"; // we will say please log in if we get this
	}		*/
?>"id="video_info_panel_favorite"--> Unfavorite:<i class="fi-like" id="video_info_panel_favorite_icon_clicked"></i>
