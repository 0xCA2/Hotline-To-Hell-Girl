<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
	$username = "";

	// figure out how to get this to work
	if ($_SESSION["username"] == $_POST["username"]) {
		echo "You are already logged in. ";
		echo "<script>console.log('i'm here in logged in ');</script>";
		echo "<meta http-equiv=\"refresh\" content=\"5;url=index.php\"/>";

		//header('Refresh: 10; URL=http://yoursite.com/page.php');
	}

	if (isset($_POST["submit"])) {

		// Attempt login

		$username = $_POST["username"];
		$password = $_POST["password"];


		$safe_username = mysql_prep($username);

		// if more then 10 failed attempts in the last 15 minutes, this will happen
		check_throttle_all();

		if(get_failed_login_attempts_by_username($safe_username) > 3) {
			$time_left = username_throttle_time_left($safe_username, 10*60);

			if($time_left > 0) {
				$wait_time = format_time_since_in_words($time_left);
				set_error_output("You have used too many login attempts. Please wait {$wait_time} and try again. ");
			}
		}


		$found_user = attempt_user_login($username, $password);

		// Test if there was a query error
		if ($found_user) {
		  // Success
		  // Mark user as logged in.
		  $_SESSION["user_id"] = $found_user["id"];
		  $_SESSION["username"] = $found_user["username"];
		  update_last_login_date($found_user["id"]);

		  redirect_to("index.php");
		} else {
		  // Failure
		  $safe_username = mysql_prep($username);
		  add_failed_attempt($safe_username);

		  // if more then 10 in the last 15 minutes, this will happen
		  throttle_all_logins();
		  // in last 15 minutes by default
		  if(get_failed_login_attempts_by_username($safe_username) >= 3) {
			  set_error_output("You have used too many login attempts. Please wait 10 minutes and try again.  ");
		  }
		  set_error_output("Username or password not found. ", "log_in.php");
		}

	}else {
		// this is probably a get request

	}
?>
