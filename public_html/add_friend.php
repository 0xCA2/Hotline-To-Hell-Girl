<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
	if (!isset($_SESSION["user_id"])) {
		set_error_output("Please log in to add friend. ");
	}else if (!isset($_GET["user"])) {
		set_error_output("Invalid friend. ");
	}else if ($_SESSION["user_id"] == $_GET["user"]) {
		set_error_output("Cannot add yourself to your own friends list. ");
	}else if (already_friend($_SESSION["user_id"], $_GET["user"])) {
		set_error_output("That user is already on your friends list. ");
	}

	add_friend($_SESSION["user_id"], $_GET["user"]);
	redirect_to("user.php?user=" . $_SESSION["user_id"]);
?>