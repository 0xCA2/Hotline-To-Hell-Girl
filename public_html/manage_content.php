<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $layout_context = "admin" ?>
<?php include("../includes/layouts/cms_header.php"); ?>
<?php confirm_logged_in(); ?>
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
	 
		<h2>Manage Content</h2>
		<div id ="episode_search_bar">
		<form id="search_episode_form" action="" method="post">
		
			<span class = "search_form_option_name" > 
			Search: <input	id = "search_episode_name_input" type="text" name="EpName" value="" /> </span>
			|
			<span class = "search_form_option_name" >Category: 
					<select name="Category" id="search_category_selector">
					<option value="0">Select a Category</option>

					<?php
						$category_set = get_categories();
						while ($category = mysqli_fetch_assoc($category_set)){
							echo "<option value=\"" . $category["id"] . "\">" . $category["name"] . "</option>";
						}
					?>
					</select>
			 </span>
			 |
			 <span class = "search_form_option_name" >Season:
				<select name="Season" id="search_episode_season_selector">
				<option value="0">Select Season</option>

				<?php
					for ($i = 1; $i < 4; $i++) {
						echo "<option value=\"" . $i . "\">" . $i . "</option>";
					}
				?>
				</select>				
			 </span>
			 |
			<span class = "search_form_option_name">Episode Number: 
					<select name="EpNum" id="search_episode_num_selector">
					<option value="0">Select Episode Number</option>

					<?php
						for ($i = 1; $i < 27; $i++) {
							echo "<option value=\"" . $i . "\">" . $i . "</option>";
						}
					?>
					</select>
			 </span>
			 <p>
			 	<!--input id="episode_search_submit" type="submit" name="submit" value="Search" /--> 
				<button id="episode_search_submit" type="button" name="submit"  >Search</button> 

			 </p>
		 </form>
		</div>
		
		<div id="episode_search_results">
		
		
		</div>
		
		<!--div id="search_episode_page_selector">
		
		
		</div-->
	 </div>
 </div>
</div>
<?php include("../includes/layouts/cms_footer.php"); ?>