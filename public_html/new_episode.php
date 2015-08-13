<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php	
	if (isset($_POST["submit"])) {
		//validations
		$required_fields = array("EpName", "Category1", "Season", "EpNum", "VidLink");
		validate_presences($required_fields);
		
		$fields_with_max_lengths = array("EpNum" => 2);
		validate_max_lengths($fields_with_max_lengths);
		
		// need to fix category crap first. 
		if(empty($errors)) {
			$EpName = mysql_prep($_POST['EpName']);
			$Category1 = (int)$_POST['Category1'];
			$Category2 = (isset($_POST['Category2'])) ? (int)$_POST['Category2'] : null;
			$Season = (int) $_POST['Season'];
			$EpNum = (int) $_POST['EpNum'];
			$VidLink = mysql_prep($_POST["VidLink"]);
			
			$query = "INSERT INTO episodes (";
			$query .= " EpName, Category1, Category2, Season, EpNum, VidLink"; 
			$query .= ") VALUES (";
			$query .= " '{$EpName}', {$Category1}, {$Category2}, {$Season}, {$EpNum}, '{$VidLink}' ";
			$query .= ")";
			
			$result =  mysqli_query($connection, $query);
			// Test if there was a query error 
			if ($result){
				// Success
				$_SESSION["message"] = "Episode created.";
				redirect_to("manage_content.php"); 
			}else {
				// Failure
				$_SESSION["message"] = "Episode creation failed." . mysqli_error($connection);
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
	<br />
	Main menu 
	<ul >
		<li><a href="new_episode.php"> Create episode </a></li>
		<li><a href="edit_episode.php"> Edit episode </a></li>
	</ul>
  </div>
  <div id="page">
	<?php echo message(); ?>
	<?php echo form_errors($errors); ?>
	
	<h2>Create Episode</h2>
	 
	<form action="new_episode.php" method="post">
	 <p>Episode Name:
	    <input	type="text" name="EpName" value="" />
	 </p>
	 
	 <p>Category 1: (required)
			<select name="Category1">
			<?php
				$category_set = get_categories();
				while ($category = mysqli_fetch_assoc($category_set)){
					echo "<option value=\"" . $category["id"] . "\">" . $category["name"] . "</option>";
				}
			?>
			</select>
	 </p>

	 <p>Category 2: 
		<select name="Category2">
			<option value="0">n/a</option>
			<?php
				$category_set = get_categories();
				while ($category = mysqli_fetch_assoc($category_set)){
					echo "<option value=\"" . $category["id"] . "\">" . $category["name"] . "</option>";
				}
			?>
		</select>	 
	 </p>
	 
      <p>Season:
        <input type="radio" name="Season" value="1" /> 1
        &nbsp;
        <input type="radio" name="Season" value="2" /> 2
		&nbsp;
        <input type="radio" name="Season" value="3" /> 3
      </p>

	 <p>Episode Number:
	    <input	type="text" name="EpNum" value="" />
	 </p>      
	 	  
	 <p>Video URL:
	    <input	type="text" name="VidLink" value="" />
	 </p>      
	 
	
	<input type="submit" name="submit" value="Create Episode" /> 
    </form>
    <br />
    <a href="manage_content.php">Cancel</a>
  </div>
</div>

<?php include("../includes/layouts/cms_footer.php"); ?>