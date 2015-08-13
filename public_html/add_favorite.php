<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php 
	if (!isset($_SESSION["user_id"])) {
		show_error("Please log in to favorite video. ");
	}else if (!isset($_GET["user"])) {
		show_error("Favorite failed. User not selected.");
	}else if ($_SESSION["user_id"] != $_GET["user"]) {
		show_error("Favorite failed. ");
	}else if (!isset($_GET["episode"])) {
		show_error("Favorite failed. Episode not selected. ");
	}else if (!check_existance_by_id("episodes", "EpID", $_GET["episode"])) {
		show_error("Episode does not exist." . check_existance_by_id("episodes", "EpID", $_GET["episode"]));
	}else if (!check_existance_by_id("users", "id", $_GET["user"])) {
		show_error("User does not exist. ");
	}else if (already_favorite($_GET["user"], $_GET["episode"])){
		show_error("You have already favorited that");
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
