<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php 
	if(isset($_POST["submit"])) {		
		$user_id = mysqli_real_escape_string($connection, $_POST["user_id"]);
		$comment_id = mysqli_real_escape_string($connection, $_POST["comment_id"]);
		
		if ($_POST["user_id"] == "-1"  || !user_logged_in())  {
			die("<scirpt>window.location.replace(\"log_in.php\");");
		}

		if (already_downvoted($user_id, $comment_id)) {
			update_vote($user_id, $comment_id, "0");
		}else if (already_upvoted($user_id, $comment_id) || exists_but_neutral($user_id, $comment_id)){   
			update_vote($user_id, $comment_id, "-1");
		}else {
			add_vote($user_id, $comment_id, "-1");
		}
	}
?>