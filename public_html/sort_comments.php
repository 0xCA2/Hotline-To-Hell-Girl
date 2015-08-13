<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php 
	if(isset($_POST["submit"])) {		
		
		$episode_id = mysqli_real_escape_string($connection, $_POST["episode_id"]);
		$sort_type = mysqli_real_escape_string($connection, $_POST["sort"]);
		
		echo make_comments_table($episode_id, 0, $sort_type);

	}
?>