<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php 
	if (isset($_GET["episode"])) {
		$current_episode = get_episode_by_id($_GET["episode"]);
	} else {
		die("No episode selected.");
	}
?>
<?php
	/*
	$result = "
	<p>Select Episode: 
			<select name=\"Episode\" id=\"episode_edit_selector\">
			<option value=\"0\">Select an Episode</option>";

	
	$episode_set = get_episodes();
	while ($episode = mysqli_fetch_assoc($episode_set)){
		$result .= "<option value=\"" . $episode["EpID"] . "\"";
		if ($episode["EpID"] == $current_episode["EpID"]) $result .= "selected";
		$result .= ">" . $episode["EpName"] . "</option>";
	}
	
	$result .= "
			</select>
	 </p> "; */
	 
	$result = "
	<p>Episode Name:
	<input	type=\"text\" name=\"EpName\" value=\"" . $current_episode["EpName"] . "\" />
	</p>";

	$result .= "
	<p>Category 1: (required)
		<select name=\"Category1\">";

	$category_set = get_categories();
	while ($category = mysqli_fetch_assoc($category_set)){
		$result .= "<option value=\"" . $category["id"] . "\""; 
		if ($category["id"] == $current_episode["Category1"]) $result .= "selected";
		$result .= ">" . $category["name"] . "</option>";
	}

	$result .= "
			</select>
	 </p> ";

	$result .= "
	 <p>Category 2: 
		<select name=\"Category2\">
			<option value=\"0\">n/a</option>";
			
	$category_set = get_categories();
	while ($category = mysqli_fetch_assoc($category_set)){
		$result .= "<option value=\"" . $category["id"] . "\"";
		if ($category["id"] == $current_episode["Category2"]) $result .= "selected";
		$result .= ">" . $category["name"] . "</option>";
	}

	$result .= "
		</select>	 
	 </p>";

	$result .= "<p>Season:";
	$result .= "<input type=\"radio\" name=\"Season\" value=\"1\" /"; 
	if ($current_episode["Season"] == 1) $result .= "checked";
	$result .= "> 1";
	$result .= "&nbsp;";
	
	$result .= "<input type=\"radio\" name=\"Season\" value=\"2\" /";
	if ($current_episode["Season"] == 2) $result .= "checked";
	$result .= "> 2";
	$result .= "&nbsp;";
	
	$result .= "<input type=\"radio\" name=\"Season\" value=\"3\" /";
	if ($current_episode["Season"] == 3) $result .= "checked";	
	$result .= "> 3";
	$result .= "</p>";

	$result .= "
	<p>Episode Number:
	<input	type=\"text\" name=\"EpNum\" value=\"" . $current_episode["EpNum"] . "\" />
	</p>";     

	$result .= "
	<p>Video URL:
	<input	type=\"text\" name=\"VidLink\" value=\"" . $current_episode["VidLink"] . "\" />
	</p> ";
 

	$result .= "<input type=\"submit\" name=\"submit\" value=\"Edit Episode\" />"; 
	
	echo $result;
?>

