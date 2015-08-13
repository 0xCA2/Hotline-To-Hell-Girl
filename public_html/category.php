<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../includes/layouts/header.php"); ?>
<?php
	$has_category = isset($_GET["category"]); 
?>
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
			<?php 
				if ($has_category) {
					$name = get_category_by_id($_GET["category"])["name"];
					echo "<h2>" . ucfirst($name) . "</h2> ";
					echo "<div id=\"category_search_data\"> ";
					include("load_search_data.php");
					echo "</div> ";
					echo "<script src=\"javascripts/vendor/jquery.js\"></script>";
					echo "<script src=\"javascripts/search_functions.js\"> </script>";
					echo "<script> make_pages(\"#category_search_data\"); </script>";
				}else {
					echo "<h2>Category</h2> ";
					echo "Select a category to the left. ";
				}	
			?>
		</div>
	</div>
</div>
<?php include("../includes/layouts/footer.php"); ?>