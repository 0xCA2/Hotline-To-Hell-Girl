<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../includes/layouts/header.php"); ?>
<?php
	/*there's a better way to do this, this is just for now */
	$from_bar_name = isset($_GET["epname"]);
	$from_bar_season = isset($_GET["season"]);

?>
	<div class="row">
		<div class="large-12 columns">
			<div class="panel" id="search_form_wrapper">
				<span id ="episode_search_bar">
				<form id="search_episode_form" action="" method="post">
					<span id="search_box">
					<span class ="search_form_option_name" > 
					Search: <input	id = "search_episode_name_input" type="text" name="EpName" 
					value="<?php if ($from_bar_name) echo $_GET["epname"]; ?>" /> </span>
					</span>
					
					<span class = "search_form_option_name" >Category: 
							<select name="Category" id="search_category_selector">
							<option value="0">Select a Category</option>

							<?php
								$category_set = get_categories();
								while ($category = mysqli_fetch_assoc($category_set)){
									echo "<option value=\"" . $category["id"] . "\">" . ucfirst($category["name"]) . "</option>";
								}
							?>
							</select>
					 </span>
		
					 <span class = "search_form_option_name" >Season:
						<select name="Season" id="search_episode_season_selector">
						<option value="0">Select</option>

						<?php
							for ($i = 1; $i < 4; $i++) {
								echo "<option value=\"" . $i . "\">" . $i . "</option>";
							}
						?>
						</select>				
					 </span>
					 
					<span class = "search_form_option_name">Episode No.: 
							<select name="EpNum" id="search_episode_num_selector">
							<option value="0">Select</option>

							<?php
								for ($i = 1; $i < 27; $i++) {
									echo "<option value=\"" . $i . "\">" . $i . "</option>";
								}
							?>
							</select>
					 </span>
					 <span>
						<!--input id="episode_search_submit" type="submit" name="submit" value="Search" /--> 
						<button class="button tiny right" id="episode_search_submit" type="button" name="submit"  >Search</button> 

					 </span>
				 </form>
				</span>
	
			</div>
			<div class="panel" id="episode_search_results">
				<?php 
					if ($from_bar_name || $from_bar_season) {
						include("load_search_data.php");
						echo "<script src=\"javascripts/vendor/jquery.js\"></script>";
						echo "<script src=\"javascripts/search_functions.js\"> </script>";
						echo "<script> make_pages(); </script>";
					}
				
				?>
			</div>			
		</div>
	</div>
	
<?php include("../includes/layouts/footer.php"); ?>