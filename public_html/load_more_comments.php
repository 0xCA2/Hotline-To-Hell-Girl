<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php 
	if(isset($_POST["submit"])) {		
		
		echo make_comments_table($_POST["episode_id"], $_POST["offset"], $_POST["sort"]);

	}
?>