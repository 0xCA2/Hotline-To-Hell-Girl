<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/init_email.php"); ?>
<?php include("../includes/layouts/header.php"); ?>
<?php 
	if (isset($_POST["submit"])) {
		// Make sure the email address is available:
		// Validation
		if (strlen($_POST["username"]) > 24) {
			set_error_output("Username can be at most 24 characters. ");
		}
		
		$username = mysql_prep($_POST["username"]);
		$password = password_encrypt($_POST["password"]);
		$email = mysql_prep($_POST["email"]);
		
		$query = "SELECT * FROM users ";
		$query .= "WHERE email = '{$email}' ";
		$result = mysqli_query($connection, $query);
		if (!$result) {
			set_error_output(" Database Error Occured ");
		} else if (mysqli_num_rows($result) != 0) { 
			set_error_output("That email address has already been registered. Please select another");
		}
		
		$query = "SELECT * FROM users ";
		$query .= "WHERE username = '{$username}' ";
		$result = mysqli_query($connection, $query);
		if (!$result) {
			set_error_output(" Database Error Occured ");
		} else if (mysqli_num_rows($result) != 0) { 
			set_error_output("That username has already been registered. Please select another.");
		}
		
		// Create a unique  activation code:
		$activation = md5(uniqid(rand(), true));

		$query = "INSERT INTO users ( ";
		$query .= "username, password, email, activation ";
		$query .= ") VALUES ( " ;
		$query .= " '{$username}', '{$password}', '{$email}', '{$activation}' ";
		$query .= " ) ";
		
		$result = mysqli_query($connection, $query);
		if ($result){
			// Success
			init_user_avatar($username);
			
			// Send the email 
			$body = " To activate your account, please click on this link:\n\n";
			$body .= WEBSITE_URL . '/activation.php?email=' . urlencode($email) . "&key={$activation}";
			
			$mailer = Swift_Mailer::newInstance($transport);

			$message = Swift_Message::newInstance('Registration Confirmation')
			  ->setFrom(array(EMAIL => 'Hotline to Hell Girl'))
			  ->setTo(array($email))
			  ->setBody($body);

			$result = $mailer->send($message);			
			
			echo '<div class="panel">Thank you for registering! A confirmation email has been sent to ' . $email .
				' Please click on the Activation Link to Activate your account </div>';

		}else {
			// Failure
			set_error_output("You could not be registered due to a system error. We apologize for any
					inconvenience.");		
		}					

	} 
?>

<?php include("../includes/layouts/footer.php"); ?>