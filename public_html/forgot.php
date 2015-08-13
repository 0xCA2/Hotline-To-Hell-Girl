<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/init_email.php"); ?>
<?php include("../includes/layouts/header.php"); ?>
<div class="row">
	
	<div class="large-12 columns">
		<div class="panel">
			<?php
				if (isset($_POST["submit"])) {
					$email = mysqli_real_escape_string($connection, $_POST["email"]);
					
					if(check_existance_of_item("users", "email",  $email)) { // not using id but whatever 
						$reset_code = md5(uniqid(rand(), true));

						$query = "UPDATE users ";
						$query .= "SET activation = '{$reset_code}' ";
						$query .= "WHERE email = '{$email}' ";
						$result = mysqli_query($connection, $query);

						if ($result) {
							// Send the email 
							$body = " To reset your password, please click on this link:\n\n";
							$body .= WEBSITE_URL . '/reset.php?email=' . urlencode($email) . "&key={$reset_code}";
							
							$mailer = Swift_Mailer::newInstance($transport);

							$message = Swift_Message::newInstance('Reset Password')
							  ->setFrom(array(EMAIL => 'Hotline to Hell Girl'))
							  ->setTo(array($email))
							  ->setBody($body);

							$result = $mailer->send($message);		

							die("A reset password link has been sent to your email. Please click that link to reset your password. ");
						}else {
							die("Error: could not reset password. ");
						}
					}else {
						echo "Email not found. Please try again. ";
					}
				}
			?>
			<div class="large-5 small-centered columns">
			<h2>Forgot Password</h2>
			<form data-abide action="forgot.php" method="post">
				<div class="row">
					<div class="large-12 columns">
						<label> Enter your email:
							<input name="email" type="text" placeholder="Enter email." required pattern="email">
							<small class="error">Please use a valid email address.</small>
						</label>
					</div>
				</div>					
				<div class="row" id="user_login_button_set">
					<div class="large-12 columns">
						<input type="submit"  class="button small right" value="Submit" name="submit">
						<a id="log_in_cancel_button" href="index.php" class="button small right">Cancel</a>
					</div>
				</div>
			</form>
			
			</div>
		</div>
	</div>
	
</div>
<?php include("../includes/layouts/footer.php"); ?>
