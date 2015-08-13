<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
/*
	if (!isset($_SESSION["user_id"])) {
		show_error("Please log in to delete favorite. ");
	}else if (!isset($_GET["user"])) {
		show_error("Favorite deletion failed. User not selected.");
	}else if ($_SESSION["user_id"] != $_GET["user"]) {
		show_error("Favorite deletion failed. ");
	}else if (!isset($_GET["episode"])) {
		show_error("Favorite deletion failed. Episode not selected. ");
	}else if (!check_existance_by_id("episodes", "EpID", $_GET["episode"])) {
		show_error("Episode does not exist." . check_existance_by_id("episodes", "EpID", $_GET["episode"]));
	}else if (!check_existance_by_id("users", "id", $_GET["user"])) {
		show_error("User does not exist. ");
	}else if (!already_favorite($_GET["user"], $_GET["episode"])){
		show_error("You never favorited that");
	}
	
	if (!delete_favorite($_GET["user"], $_GET["episode"])){
		show_error("Favorite deletion failed. Database error.");
	}
*/
	if (isset($_POST["submit"])) {
		$user_id = mysqli_real_escape_string($connection, $_POST["user_id"]);
		$episode_id_list = mysqli_real_escape_string($connection, $_POST["episode_id_list"]);
		
		$episode_id_array = explode(" ", $episode_id_list);
		
		if (check_existance_by_id("users", "id", $user_id)) {
			foreach($episode_id_array as $id) {
				if (check_existance_by_id("episodes", "EpID", $id)){
					delete_favorite($user_id, $id);
				}
			}
		}
		
	}
	
?>