<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php

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
