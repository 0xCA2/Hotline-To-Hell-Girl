<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php 
	if (!isset($_GET["episode"])){
		die("Please enter episode.");
	}else {
		$episode = get_episode_by_id($_GET["episode"]);
		
		if ($episode == null) {
			die("Episode not found.");
		}
	}
?>
<?php
	$info_box = "";
	$info_box .= "<td colspan=2>";
	$info_box .= "<div id=\"cms_episode_info_box\">";
    $info_box .= "<div id=\"cms_thumbnail_panel\">";
	$info_box .= "<div id=\"cms_thumbnail\">";
	$thumbnail = "images/thumbnails/" . $episode["EpID"] .".jpg";
	$info_box .= "<img src=\"";
	(file_exists($thumbnail)) ? $info_box .= $thumbnail : $info_box .= "images/thumbnails/default.jpg";
	$info_box .= "\">";
	$info_box .= "</div>";
	$info_box .= "<div id=\"cms_episode_search_data_name\">";
	if (strlen($episode["EpName"]) > 30) {
		$info_box .= substr($episode["EpName"], 0, 30) . "...";
	}else {
		$info_box .= $episode["EpName"];
	}
	$info_box .= "</div>";
	$info_box .= "</div>";
    $info_box .= "<div id=\"cms_info_panel\"> ";
	$info_box .= "<ul>";
	$info_box .= "<li><span id=\"episode_info_name\">ID:</span> ". $episode["EpID"] . "</li>";
	$category1 = get_category_by_id($episode["Category1"]);
	$info_box .= "<li><span id=\"episode_info_name\">Category1:</span> " . ucfirst($category1["name"]) . "</li>";
	$category2 = get_category_by_id($episode["Category2"]);
	$info_box .= "<li><span id=\"episode_info_name\">Category2:</span> "; 
	($category2 == null) ?  $info_box .= "None" : $info_box .= ucfirst($category2["name"]); 
	$info_box .= "</li>";
    $info_box .= "<li><span id=\"episode_info_name\">Episode No.: </span> " .  $episode["EpNum"] . "</li>";
	$info_box .= "<li><span id=\"episode_info_name\">Season:</span>" . $episode["Season"] . "</li>";
	$info_box .= "<li><span id=\"episode_info_name\">Video Link:</span> ";
	$info_box .= "<a href=\"". $episode["VidLink"] . "\"> Watch </li>";
	$info_box .= "</ul>";
    $info_box .= "</div>";
	$info_box .= "</div>";
	$info_box .= "</td>";
	echo $info_box;
?>