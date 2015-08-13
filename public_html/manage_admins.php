<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in();?>
<?php $layout_context = "admin" ?>
<?php include("../includes/layouts/cms_header.php"); ?>
<div id="main">
  <div id="navigation">
  	<a href="admin.php">&laquo; Main menu</a> <br />

	&nbsp;
  </div>
  <div id="page">
	 <?php echo message(); ?>
	 
	<h2>Manage Admins</h2>
	
	<table class="admin_panel">
		<thead>	 
			<th>Username</th>
			<th>Actions</th>
		</thead>
		<tbody>
		<!-- generated dynamically by php -->
			<?php
				$admin_set = find_all_admins();
				while ($admin = mysqli_fetch_assoc($admin_set)) {
					echo "<tr>";
					echo "<td>" . htmlentities($admin["username"]) . "</td>";
					echo "<td><a href=\"edit_admin.php?id=".urlencode($admin["id"]) . "\">Edit</a>";
					echo "  <a href=\"delete_admin.php?id=".urlencode($admin["id"]) . "\"";
					echo " onclick=\"return confirm('Are you sure?');\"";
					echo ">Delete</a>";
					echo "</td>";
					echo "</tr>";
				}
			   	mysqli_free_result($admin_set);

			?>
		</tbody>
	</table>
	<br />
	<a href="new_admin.php">Add new admin</a>
	
	<hr />
  </div>
</div>
<?php include("../includes/layouts/cms_footer.php"); ?>
