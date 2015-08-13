<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php 
	if(isset($_POST["submit"])) {		
		// insert comment into comments 
		
		$success = submit_comment($_POST["user_id"], $_POST["episode_id"], $_POST["comment"]);
		if ($success) {
			$comment_id = mysqli_insert_id($connection);
			init_comment_votes($comment_id);
			echo make_comment_from_id($comment_id);
		} else {
			echo "Comment submission failed.";
		}
	}
?>