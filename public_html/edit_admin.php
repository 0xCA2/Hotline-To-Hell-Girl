<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	if (isset($_GET["id"])){
		$current_admin = find_admin_by_id($_GET["id"]);
	} else {
		redirect_to("manage_admins.php");
	}
?>
<?php	
	if (isset($_POST["submit"])) {
		//validations
		$required_fields = array("username", "password");
		validate_presences($required_fields);
		
		$fields_with_max_lengths = array("username" => 50);
		validate_max_lengths($fields_with_max_lengths);

		if(empty($errors)) {
			$id = $current_admin["id"];
			$username = mysql_prep($_POST['username']);
			$hashed_password = password_encrypt($_POST["password"]);
			
			$query =  "UPDATE admins SET ";
			$query .= "username = '{$username}', ";
			$query .= "password = '{$hashed_password}' ";
			$query .= "WHERE id = {$id} ";
			$query .= "LIMIT 1";
			
			$result =  mysqli_query($connection, $query);
			// Test if there was a query error 
			if ($result && mysqli_affected_rows($connection) == 1) {
			  // Success
			  $_SESSION["message"] = "Admin updated.";
			  redirect_to("manage_admins.php");
			} else {
			  // Failure
			  $_SESSION["message"] = "Admin update failed.\n" . mysqli_error($connection);
			}
		}
	}else {
		// this is probably a get request 
		
	}
?>
<?php $layout_context = "admin" ?>
<?php include("../includes/layouts/cms_header.php"); ?>
<div id="main">
  <div id="navigation">
  </div>
  <div id="page">
    <?php echo message(); ?>
	<?php echo form_errors($errors); ?>
	
	<h2>Edit Admin: <?php echo htmlentities($current_admin["username"]);?> </h2>

	<?php 
		echo "<form action=\"edit_admin.php?id=". urlencode($current_admin["id"]) . "\" ";
		echo "method=\"post\">";
	?>	
	 <p>Username:
	    <input	type="text" name="username" value="<?php 
			echo htmlentities($current_admin["username"]);
		?>" />
	 </p>
	 <p>Password:
	    <input	type="password" name="password" value=""/>
	 </p>

      <input type="submit" name="submit" value="Edit Admin" />
    </form>
    <br />
    <a href="manage_admins.php">Cancel</a>
	&nbsp;
	&nbsp;
	<?php
	//	echo "<a href=\"delete_page.php?page=" . urlencode($current_page["id"]) . "\"";
	//	echo " 	onclick=\"return confirm('Are you sure?');\">Delete Page </a>";
	?>
	
  </div>
</div>

<?php include("../includes/layouts/cms_footer.php"); ?>
