<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_user_logged_in(); ?>
<?php
	if (!isset($_GET["user"])) {
		set_error_output("User not selected. ");
	}else if (!check_existance_by_id("users", "id" , $_GET["user"])) {
		set_error_output("User does not exist. ");
	}
	

?>
<?php include("../includes/layouts/header.php"); ?>
<div class="row">
	<div class="large-12 columns">
		<div class="panel">
			<h4>Favorites</h4>
			<?php 
				echo "<div id=\"favorites_results_table\">";
				make_favorites_table($_GET["user"]);
				echo "</div>";
				
				if ($_SESSION["user_id"] == $_GET["user"]) {
					echo "<a id=\"delete_favorites_link\"> Delete favorites </a> ";
				}				
			?>
			<script src="javascripts/vendor/jquery.js"></script>
			<script src="javascripts/search_functions.js"> </script>
			<script> make_pages("#favorites_results_table"); </script>
			
		</div>
	</div>
</div>
<?php include("../includes/layouts/footer.php"); ?>
