<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php

	if (isset($_POST["submit"])) {
		$user_id = mysqli_real_escape_string($connection, $_POST["user_id"]);
		$friend_id_list = mysqli_real_escape_string($connection, $_POST["friend_id_list"]);
		
		$friend_id_array = explode(" ", $friend_id_list);
		
		if (check_existance_by_id("users", "id", $user_id)) {
			foreach($friend_id_array as $id) {
				if (check_existance_by_id("users", "id", $id)){
					delete_friend($user_id, $id);
				}
			}
		}
		
	}
	
?>