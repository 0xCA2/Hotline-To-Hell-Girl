<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../includes/layouts/header.php"); ?>
<?php 
	if(!isset($_GET["e"])){
		show_error("No episode selected. ");
	}else {
		$episode = get_episode_by_id($_GET["e"]);
		if ($episode == null) {
			show_error("Episode not found. ");
		}
	}

?>	
	<div class="row">
		
		<div class="large-9 medium-9 columns">
			<div class="panel">
			<h3><?php echo $episode["EpName"]; ?></h3>
	     <!-- going to have to replace these query strings in my php -->
		<!--iframe src="http://videowing.me/embed/85ef0549e876ca9759874370ffc7f133?w=718&amp;h=438" scrolling="no" width="718" height="438" marginheight="0" marginwidth="0" frameborder="0"></iframe-->		
        <iframe src="<?php echo $episode["VidLink"]; ?>" 
		scrolling="no" width="680" height="438" marginheight="0" marginwidth="0" frameborder="0"></iframe>	
			<div id="video_info_panel">
			<span id="video_info_panel_labels">Season: 
			<?php 
				$output = "<a href=\"";
				$output .= "search.php?season=" . $episode["Season"] . "\">";
				$output .= $episode["Season"] . "</a> ";
				echo $output;
			?>
			Category: 
			<?php
				$category1_url = "category.php?category=" . $episode["Category1"];
				$category1_name = get_category_by_id($episode["Category1"])["name"];
				$output = " <a class=\"category_link\" href=\"" . $category1_url . "\">" . ucfirst($category1_name) . "</a>";
				$output .= "<script>console.log( " . $episode["Category2"] . " ) </script> "; 
				if ($episode["Category2"] != "0") {
					$output .= ", ";
					$category2_url = "category.php?category=" . $episode["Category2"];
					$category2_name = get_category_by_id($episode["Category2"])["name"];
					$output .= "<a class=\"category_link\" href=\"" . $category2_url . "\">" . ucfirst($category2_name) . "</a>";
				}	
				echo $output;
			?>
			</span> 
			<!-- javascript data attributes are blowing my mind -->
			<span data-user-id="<?php 
				if (isset($_SESSION["user_id"])) {
					echo $_SESSION["user_id"];
				} else {
					echo "-1"; // we will say please log in if we get this 
				}
			?>"
			data-ep-id="<?php echo $episode["EpID"]; ?>"  
			id="video_info_panel_favorite"> <?php //Favorite:<i class="fi-like" 
			//id="
				if (isset($_SESSION["user_id"]) && already_favorite($_SESSION["user_id"], $episode["EpID"])) {
					echo "Unfavorite:<i class=\"fi-like\" id=\"video_info_panel_favorite_icon_clicked\"></i>";
				}else {
					echo "Favorite:<i class=\"fi-like\" id=\"video_info_panel_favorite_icon\"></i>";
				}
			?>
			</span>
			</div>
			</div>
			
			<div class="panel">
				<h3>Comments</h3>
				<?php 
					if (isset($_SESSION["user_id"])) {
						$output = "<form id=\"comment_submit_form\" data-user-id=\"" . $_SESSION["user_id"] ."\"";
						$output .= " data-ep-id=\"". $episode["EpID"] . "\">";
						$output .= "<div class=\"row\" id=\"comment_input_panel\">";
						$output .= "<img id=\"comment_image\" class=\"left\" src=\"" . get_user_avatar($_SESSION["user_id"])["file_path"] . "\" />"; 
						$output .= "<div class=\"large-11 medium-11 small-11 columns\" >";
						$output .= "<textarea id=\"comment_text_area\" placeholder=\"Share your thoughts\"></textarea> ";
						$output .= "</div>";
						$output .= "</div>";
						$output .= "<div class=\"row\" id=\"user_login_button_set\">";
						$output .= "<div class=\"large-12 columns\">";
						$output .= "<a  id=\"comment_submit_button\" class=\"button tiny right\" >Submit</a>";
						$output .= "</div>";
						$output .= "</div>";
						$output .= "</form>";
						echo $output;
					}else {
					    echo "<div id=\"comment_login_message\"> Log in to post comments. </div>";
					}
				?>
				<div id="comment_panel">
					<?php 
						echo make_comments_table($episode["EpID"], 0, "top");
					
					?>
				</div>
		</div>
		</div>
		
		<div class="large-3 medium-3 columns">
			<div class="panel">
					<h4>Related Videos</h4>

					<ul class="medium-block-grid-1">
						<?php
							echo make_related_videos_table($episode); 
						?>
				    </ul>		
			</div>
		</div>
	</div>
	
<?php include("../includes/layouts/footer.php"); ?>
