<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/init_email.php"); ?>
<?php 
	
	if (!isset($_GET["email"]) || !isset($_GET["key"])) {
		show_error("Reset password failed. ");
	}else {
		$email = mysql_prep($_GET["email"]);
		$key = mysql_prep($_GET["key"]);
		
		if (!check_existance_of_item("users", "email", $email)) {
			show_error("Email not found. ");
		}else {
			$query = "SELECT id ";
			$query .= "FROM users ";
			$query .= "WHERE activation = '{$key}' ";
			$query .= "LIMIT 1 ";
			
			$result = mysqli_query($connection, $query);
			if ($result && mysqli_affected_rows($connection) > 0) {
				$user_id = mysqli_fetch_assoc($result)["id"];
			}else {
				show_error("Reset password failed.");
			}
		}
	}

?>

<?php include("../includes/layouts/header.php"); ?>
<div class="row">
	
	<div class="large-12 columns">
		<div class="panel">
				<h2>Enter new password.</h2>
				<form data-abide action="<?php
					$output = "reset_password.php?email=" . urlencode($_GET["email"]) . "&key={$_GET["key"]}";
					echo $output;
				?>" method="post">
						<div class="row">
							<div class="large-12 columns">
								<label> Password:
									<input name="password" id="password" type="password" placeholder="Enter password." required>
								</label>
								<small class="error">Password is required.</small>

							</div>
						</div>	
						<div class="row">
							<div class="large-12 columns">
								<label> Confirm Password:
									<input type="password" placeholder="Confirm Password." data-equalto="password">
								</label>
								<small class="error">The password did not match</small>

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
<?php include("../includes/layouts/footer.php"); ?>
		