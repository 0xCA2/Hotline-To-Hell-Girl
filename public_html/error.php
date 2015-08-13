<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../includes/layouts/header.php"); ?>
<div class="row"> ";
	<div class ="large-12 columns"> 
		 <div class="panel"> 
			<h4> Error </h4> 
				<?php 
					if(isset($_SESSION["error"])){
						echo $_SESSION["error"];
					} else {
						echo "No error given. ";
					}
				?>
		</div> 
	</div> 
</div> 
<?php include("../includes/layouts/footer.php"); ?>