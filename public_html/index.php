<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php include("../includes/layouts/header.php"); ?>

	<div class="row">
		<div class="large-2 columns">
			<div class="panel" id="category_panel">
				  <h6 id="#category_list_header"> Categories </h6>
				  <!-- Make sure that when you click on these it saves the one you clicked on -->
				  <?php category_navigation(); ?>
			</div>
			<div class="panel" >
				   <a href="mailto:sevenchild@hotlinetohellgirl.com">Contact me</a> if any links are broken.
			
			</div>
		</div>
		
		
		<div class="large-10 columns">
			<div class="panel">
				<h2>Welcome</h2>
				<p>
					Hotline to Hell Girl is the premier Hell Girl fan site for sorting thorugh and 
					searching for Hell Girl episodes. Pick a category to the left, search at the 
					top, and enjoy! 
				</p>
				<?php make_random_homepage_row("category"); ?>			
			</div>
		</div>
	</div>
	
<?php include("../includes/layouts/footer.php"); ?>