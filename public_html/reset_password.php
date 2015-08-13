<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
	if(!isset($_GET["email"]) || !isset($_GET["key"])) {
		show_error("Password change error. ");
	}
	
	if(isset($_POST["submit"])) {
		$email = mysql_prep($_GET["email"]);
		$activation = mysql_prep($_GET["key"]);
		
		$query = "SELECT id ";
		$query .= "FROM users ";
		$query .= "WHERE email = '{$email}' ";
		$query .= "AND ";
		$query .= " activation = '{$activation}' ";
		$query .= "LIMIT 1 ";
		
		$result = mysqli_query($connection, $query);
		if ($result && mysqli_affected_rows($connection) > 0) {
			$user_id = mysqli_fetch_assoc($result)["id"];
			$password = password_encrypt($_POST["password"]);

			$query = "UPDATE users ";
			$query .= "SET password = '{$password}' ";
			$query .= "WHERE id = {$user_id} ";
			$query .= "LIMIT 1 ";
			
			$result = mysqli_query($connection, $query);
			if (!$result || mysqli_affected_rows($connection) <= 0) {
				show_error("Reset password failed.");
			}
		}else {
			show_error("Reset password failed.");
		}

	}
?>

<?php include("../includes/layouts/header.php"); ?>
<div class="row">
	<div class="large-12 columns">
		<div class="panel">
			Password has been changed. Please <a href="log_in.php"> Log in </a>
		</div>
	</div>
</div>
<?php include("../includes/layouts/footer.php"); ?>
		