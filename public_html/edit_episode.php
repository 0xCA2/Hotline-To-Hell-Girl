<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<!-- else if there's no post and you go have a get request, then show the 
	thing with the thing edited selected and with it's stuff easily editable 
--> 
<?php		
	if (isset($_POST["submit"])) {
		if (!isset($_GET["episode"])) {
			die("Please select episode.");
		}else {
			$EpID = $_GET["episode"];
		}
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
			
			$query = "UPDATE episodes SET ";
			$query .= " EpName = '{$EpName}', ";
			$query .= " Category1 = {$Category1}, "; 
			$query .= " Category2 = {$Category2}, "; 
			$query .= " Season = {$Season}, ";
			$query .= " EpNum = {$EpNum}, "; 
			$query .= " VidLink = '{$VidLink}' ";
			$query .= " WHERE EpID = {$EpID} ";
			$query .= " LIMIT 1 ";
			
			$result =  mysqli_query($connection, $query);
			// Test if there was a query error 
			if ($result && mysqli_affected_rows($connection) == 1){
				// Success
				$_SESSION["message"] = "Episode edited.";
				redirect_to("edit_episode.php"); 
			}else {
				// Failure
				$_SESSION["message"] = "Episode edited failed.";
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
	
	<h2>Edit Episode</h2>
	 
	<form id="episode_edit_form"
		action="edit_episode.php
		<?php 
			if (!isset($_POST["submit"]) && isset($_GET["episode"])) {
				echo "?episode=" . urlencode($_GET["episode"]);
			}
		?>" 
		method="post">
	<p>Select Episode: 
			<select name="Episode" id="episode_edit_selector">
			<option value="0">Select an Episode</option>

			<?php
				$episode_set = get_episodes();
				while ($episode = mysqli_fetch_assoc($episode_set)){
					echo "<option value=\"" . $episode["EpID"] . "\"";
					// for coming from manage_content
					if (!isset($_POST["submit"]) && isset($_GET["episode"])) {
						if ($episode["EpID"] == $_GET["episode"]) {
							echo "selected";
						}
					}					
					echo ">" . $episode["EpName"] . "</option>";
				}
			?>
			</select>
	 </p>
	 <div id="episode_data">
		<?php
			// for coming from manage_content
			if (!isset($_POST["submit"]) && isset($_GET["episode"])) {
				include("load_episode_data.php");
				
			}
		?>
	 </div>
    </form>
    <br />
    <a href="manage_content.php">Cancel</a>
	<!-- use span for inline elements -->
	<span id="edit_episode_delete_button"> </span>
	<!-- add delete page -->
  </div>
</div>

<?php include("../includes/layouts/cms_footer.php"); ?>