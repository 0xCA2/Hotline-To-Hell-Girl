<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php	
	/*
		The purpose of this was to set categories to id numbers instead of just
		names.
	*/
	function set_new_category1($id, $name) {
		global $connection;

		$query = "UPDATE episodes SET ";
		$query .= " Category1 = '{$id}' ";
		$query .= " WHERE Category1= '{$name}' ";
		
		$result =  mysqli_query($connection, $query);

	}
	
	function set_new_category2($id, $name) {
		global $connection;

		$query = "UPDATE episodes SET ";
		$query .= " Category2 = '{$id}' ";
		$query .= " WHERE Category2= '{$name}' ";
		
		$result =  mysqli_query($connection, $query);

	}
	
	function get_all_categories() {
		global $connection;
		
		$query = "SELECT * FROM categories ";
		
		$category_set =  mysqli_query($connection, $query);
		confirm_query($category_set);
		return $category_set;		
	}
	
	// get all episodes 
	$category_set = get_all_categories();
	
	while ($category = mysqli_fetch_assoc($category_set)) {
		// set its category to its corresponding category it
		set_new_category1($category["id"], $category["name"]);
		set_new_category2($category["id"], $category["name"]);
	}
?>