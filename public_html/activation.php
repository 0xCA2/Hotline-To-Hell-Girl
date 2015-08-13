<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../includes/layouts/header.php"); ?>
<?php
	// Validating Key 
	$valid_email_chars = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/";
	if (isset($_GET['email']) && preg_match($valid_email_chars, $_GET['email'])) {
		$email = $_GET['email'];
	}
	
	// Validating activation key  
	//The Activation key will always be 32 since it is MD5 Hash
	if (isset($_GET['key']) && (strlen($_GET['key']) == 32)){
		$key = $_GET['key'];
	}

	if (isset($email) && isset($key)) {
		// Update the database to set the "activation" field to null
		$query = "UPDATE users ";
		$query .= "SET activation=NULL , activation_date = CURDATE() ";
		$query .= "WHERE(email = '{$email}' AND activation='{$key}') ";
		$query .= "LIMIT 1";
	 
		$result = mysqli_query($connection, $query);

		// Print a customized message:
		// if update query was successfull
		if (mysqli_affected_rows($connection) == 1){
			echo '<div>Your account is now active. You may now <a href="log_in.php">Log in</a></div>';
		} else {
			echo '<div>Oops !Your account could not be activated. Please recheck the link or contact the system administrator.</div>';
		}

	} else {
		echo '<div>Error Occured .</div>';
	}
?>
<?php include("../includes/layouts/footer.php"); ?>