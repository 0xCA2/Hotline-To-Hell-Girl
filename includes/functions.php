<?php 
	function confirm_query($result_set){
		// Test if there was a query error 
		global $connection; 
		if (!$result_set){
			debug_print_backtrace();
			die("Database query failed." . "\n" .  mysqli_error($connection) . "\n");
		}				
	}
	
	function form_errors($errors = array()) {
		$output = "";
		if (!empty($errors)) {
			$output .= "<div class=\"error\">";
			$output .= "Please fix the following errors: ";
			$output .= "<ul>";
			foreach($errors as $key => $error) {
				$output .= "<li>";
				$output .= htmlentities($error);
				$output .= "</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";
		}
		return $output;
	}
	
	function find_all_subjects($public=true) {
		global $connection;
		
		$query = "SELECT * ";
		$query .= "FROM subjects ";
		if ($public) {
			$query .= "WHERE visible = 1 "; // We're in the admin area so we should see everything. 
		}
		$query .= "ORDER BY position ASC";
		$subject_set =  mysqli_query($connection, $query);
		confirm_query($subject_set);		
		return $subject_set;
	}
	
	function find_pages_for_subject($subject_id, $public=true) {
		global $connection;
		
		$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);

		
		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE subject_id = {$safe_subject_id} ";
		if ($public) {
			$query .= "AND visible = 1 ";
		}
		$query .= "ORDER BY position ASC";
		$page_set =  mysqli_query($connection, $query);
		confirm_query($page_set);		
		return $page_set;
	}

	function find_subject_by_id($subject_id, $public=true) {
		global $connection;
		
		$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);
		
		$query  = "SELECT * ";
		$query .= "FROM subjects ";
		$query .= "WHERE id = {$safe_subject_id} ";
		if ($public) {
			$query .= " AND visible = 1 ";
		}
		$query .= "LIMIT 1";
		$subject_set = mysqli_query($connection, $query);
		confirm_query($subject_set);
		if($subject = mysqli_fetch_assoc($subject_set)) {
			return $subject;
		} else {
			return null;
		}
	}
	
	function find_page_by_id($page_id, $public=true) {
		global $connection;
		
		$safe_page_id = mysqli_real_escape_string($connection, $page_id);
		
		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE id = {$safe_page_id} ";
		if ($public) {
			$query .= " AND visible = 1 ";
		}
		$query .= "LIMIT 1";
		$page_set =  mysqli_query($connection, $query);
		confirm_query($page_set);		
		if ($page = mysqli_fetch_assoc($page_set)) {
			return $page;		
		}else {
			return null;
		}		
	}
	
	function find_default_page_for_subject($subject_id) {
		$page_set = find_pages_for_subject($subject_id);
		
		if ($first_page = mysqli_fetch_assoc($page_set)) {
			return $first_page;		
		}else {
			return null;
		}		
	}
	
	function find_selected_page($public=false) {
		global $current_subject;
		global $current_page;
		
		if (isset($_GET["subject"])) {
			$current_subject = find_subject_by_id($_GET["subject"], $public);
			if ($current_subject && $public) {
				$current_page = find_default_page_for_subject($current_subject["id"]);
			} else {
				$current_page = null;
			}
		} elseif (isset($_GET["page"])) {
			$current_subject = null;
			$current_page = find_page_by_id($_GET["page"], $public);
		} else {
			$current_subject = null;
			$current_page = null;
		}		
	}
	
	// navigation takes 2 arguments
	// the current subject array or null
	// the current page array or null 
	function navigation($subject_array, $page_array) {
		$output =  "<ul class=\"subjects\">";
		$subject_set = find_all_subjects(false);	
		while($subject = mysqli_fetch_assoc($subject_set)) {
			$output .= "<li";
			if ($subject_array && $subject_array["id"] === $subject["id"]) {
				$output .= " class = \"selected\"";
			}
			$output .= ">";
			
			$output .= "<a href=\"manage_content.php?subject=" . urlencode($subject["id"]) . "\">";
			$output .= htmlentities($subject["menu_name"]);  
			$output .= "</a>";
			
			$page_set = find_pages_for_subject($subject["id"], false); 			
			$output .= "<ul class=\"pages\">";
			while($page = mysqli_fetch_assoc($page_set)) { 
				$output .= "<li";
				if($page_array && $page_array["id"] === $page["id"]){
					$output .=  " class = \"selected\"";
				}
				$output .= ">";
				
				$output .= "<a href=\"manage_content.php?page=" . urlencode($page["id"]) . "\">";
				$output .= htmlentities($page["menu_name"]);
				$output .= "</a>";
				$output .= "</li>";
			} 
			mysqli_free_result($page_set);	
			$output .= "</ul>";
			$output .= "</li>";
	   }	
	   mysqli_free_result($subject_set);	
	   $output .= "</ul>";
	   return $output;
	}

	function public_navigation($subject_array, $page_array) {
		$output =  "<ul class=\"subjects\">";
		$subject_set = find_all_subjects();	
		while($subject = mysqli_fetch_assoc($subject_set)) {
			$output .= "<li";
			if ($subject_array && $subject_array["id"] === $subject["id"]) {
				$output .= " class = \"selected\"";
			}
			$output .= ">";
			
			$output .= "<a href=\"index.php?subject=" . urlencode($subject["id"]) . "\">";
			$output .= htmlentities($subject["menu_name"]);  
			$output .= "</a>";
			
			if($subject_array["id"] == $subject["id"] ||
			   $page_array["subject_id"] == $subject["id"]
			) {
				$page_set = find_pages_for_subject($subject["id"]); 			
				$output .= "<ul class=\"pages\">";
				while($page = mysqli_fetch_assoc($page_set)) { 
					$output .= "<li";
					if($page_array && $page_array["id"] === $page["id"]){
						$output .=  " class = \"selected\"";
					}
					$output .= ">";
					
					$output .= "<a href=\"index.php?page=" . urlencode($page["id"]) . "\">";
					$output .= htmlentities($page["menu_name"]);
					$output .= "</a>";
					$output .= "</li>";
				} 
				$output .= "</ul>";
				mysqli_free_result($page_set);	
			}
			$output .= "</li>"; // end of subject li 
	   }	
	   mysqli_free_result($subject_set);	
	   $output .= "</ul>";
	   return $output;		
	}
	
	function redirect_to($new_location) {
		header("Location: " . $new_location);
		exit;
	}
	
	function mysql_prep($string) {
		global $connection;
		
		$escaped_string = mysqli_real_escape_string($connection, $string);
		return $escaped_string;
	}
	
	// we have a table named admins 
	function find_all_admins() {
		global $connection;
		
		$query = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "ORDER BY username ASC";
		$admin_set =  mysqli_query($connection, $query);
		confirm_query($admin_set);		
		return $admin_set;		
	}
	
	function find_admin_by_id($admin_id) {

		global $connection;
		
		$safe_admin_id = mysqli_real_escape_string($connection, $admin_id);
		
		$query = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "WHERE id = {$safe_admin_id} "; 
		$query .= "LIMIT 1";
		
		$admin_set =  mysqli_query($connection, $query);
		confirm_query($admin_set);
		if($admin = mysqli_fetch_assoc($admin_set)) {
			return $admin;
		} else {
			return null;
		}	
	}
	
	function find_admin_by_username($username) {

		global $connection;
		$safe_username = mysqli_real_escape_string($connection, $username);
	
		$query = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "WHERE username = '{$safe_username}' "; 
		$query .= "LIMIT 1";
		
		$admin_set =  mysqli_query($connection, $query);
		confirm_query($admin_set);
		if($admin = mysqli_fetch_assoc($admin_set)) {
			return $admin;
		} else {
			return null;
		}	
		
		
	}
	
	// like password_hash is php 5.5
	function password_encrypt($password) {
		$hash_format = "$2y$10$"; // Tells PHP to use Blowfish with a cost of 10$ 
		$salt_length = 22;        // Blowfish salts should be 22 chars or more 
		$salt = generate_salt($salt_length);
		$format_and_salt = $hash_format . $salt;
		$hash = crypt($password, $format_and_salt);
		return $hash;
	}
	
	function generate_salt($length) {
		// Not 100% unique, not 100% random but good enough for a salt 
		// MD5 returns 32 characters
		$unique_random_string = md5(uniqid(mt_rand(), true));
		
		// Valid characters for a salt are [a-zA-Z0-9./]
		$base64_string = base64_encode($unique_random_string);
		
		// But not "+" which is valid in base64 encoding
		$modified_base64_string = str_replace("+", ".", $base64_string);
		
		// Truncate string to the current length 
		$salt = substr($modified_base64_string, 0, $length);
		return $salt;
	}
	
	// like password_verify in php 5.5. 
	function password_check($password, $existing_hash) {
		//existing hash contains format and hash at start
		$hash = crypt($password, $existing_hash);
		if ($hash === $existing_hash){
			return true;
		} else {
			return false;
		}
		
	}

	function attempt_login($username, $password) {
		$admin = find_admin_by_username($username);
		
		if ($admin) {
			// found admin now check password
			if (password_check($password, $admin["password"])) {
				return $admin;
			}else {
				// password does not match 
				return false;
			}	
		}else {
			// admin not found 
			return false;
		}
		
	}
	
	// for pages where the site shows the same pages for logged in and not logged in
	// but different content/different options 
	function logged_in() {
		return isset($_SESSION["admin_id"]);
	}
	
	function confirm_logged_in() {
		if (!logged_in()) {
			redirect_to("login.php");
		}			
	}
	
	
	function get_categories() {
		global $connection;

		$query = "SELECT * ";
		$query .= "FROM categories ";
		$query .= "ORDER BY name ASC";

		
		$category_set =  mysqli_query($connection, $query);
		confirm_query($category_set);
		return $category_set;
		
	}
	
	function get_category_by_id($id) {
		global $connection;
		
		$safe_category_id = mysqli_real_escape_string($connection, $id);
		
		$query = "SELECT * ";
		$query .= "FROM categories ";
		$query .= "WHERE id = {$safe_category_id} "; 
		$query .= "LIMIT 1";
		
		$category_set =  mysqli_query($connection, $query);
		confirm_query($category_set);
		if($category = mysqli_fetch_assoc($category_set)) {
			return $category;
		} else {
			return null;
		}			
	}
	
	function get_episodes() {
		global $connection;

		$query = "SELECT * ";
		$query .= "FROM episodes ";
		$query .= "ORDER BY Season ASC, EpNum ASC ";

		
		$episode_set =  mysqli_query($connection, $query);
		confirm_query($episode_set);
		return $episode_set;		
		
	}
	
	function get_episode_by_id($id) {
		global $connection;
		
		$safe_episode_id = mysqli_real_escape_string($connection, $id);
		
		$query = "SELECT * ";
		$query .= "FROM episodes ";
		$query .= "WHERE EpID = {$safe_episode_id} "; 
		$query .= "LIMIT 1";
		
		$episode_set =  mysqli_query($connection, $query);
		confirm_query($episode_set);
		if($episode = mysqli_fetch_assoc($episode_set)) {
			return $episode;
		} else {
			return null;
		}			
		
		
	}
	

	
	function episode_search($data = "*") {
		global $connection;
				
		if (!empty($_GET)) {
			if (isset($_GET['epname'])) $EpName = mysql_prep($_GET['epname']);
			if (isset($_GET['category'])) $Category = (int)$_GET['category'];
			if (isset($_GET['season'])) $Season = (int) $_GET['season'];
			if (isset($_GET['epnum'])) $EpNum = (int) $_GET['epnum'];

			$query = "SELECT {$data} FROM episodes ";
			$conditions = array();

			if (isset($EpName)) {
				$conditions[] = " EpName LIKE '%". $EpName . "%' ";
			}
			if(isset($Category)) {
				$conditions[] = " (Category1=" . $Category ." OR Category2=".$Category.") ";
			}
			if(isset($Season)) {
				$conditions[] = " Season=" . $Season;
			}
			if(isset($EpNum)) {
				$conditions[] = " EpNum=" . $EpNum;
			}
			
			//$sql = $query;
			if (count($conditions)>0) {
				$query .= " WHERE " . implode(" AND " , $conditions);
			}
			
			$result =  mysqli_query($connection, $query);
			// Test if there was a query error 
			if ($result){
				return $result;
			}else {
				echo $query;
				echo "<br />";
				echo mysqli_error($connection); 
			}					
		}else {
			die("No search data entered.");
		}		
		
	}
	
	function category_navigation() {
		$output = "<ul id=\"category_list\">";
		$category_set = get_categories();	
		while($category = mysqli_fetch_assoc($category_set)) {
			$output .= "<li ";
			if (isset($_GET["category"])) {
				if ($_GET["category"] == $category["id"]) {
					$output .= " class=\"selected\" ";
				}
			}
			$url = "category.php?category=" . $category["id"];
			$output .= "><a href=\"" . $url ."\">" . ucfirst($category["name"]) . "</a>";
			$output .= "</li>";
		}
		$output .= "</ul>";
		echo $output;
	}
	
	function make_episode_cell($id, $html_under_cell="") {
		$episode = get_episode_by_id($id);
		if ($episode == null) die("Episode not found.");
		$output = "";
		
		$url = "video.php?e=" . $id;
		$output .= "<li>";
		$output .= "<div class=\"video_item\">";
		$output .= "<a href=\"" . $url . "\">";
		$thumbnail = "images/thumbnails/" . $episode["EpID"] .".jpg";
		$output .= "<img src=\"";
		(file_exists($thumbnail)) ? $output .= $thumbnail : $output .= "images/thumbnails/default.jpg";
		$output .= "\">";
		$output .= "</a>";
		$output .= "<a class=\"episode_name_link\" href=\"". $url . "\">";
		$output .= "<div class=\"episode_name\"> " . $episode["EpName"] . " </div> ";	
		$output .= "</a>";
		$output .= "<div class=\"category_link_wrapper\">";
		$output .= "Category: ";
		$category1_url = "category.php?category=" . $episode["Category1"];
		$category1_name = get_category_by_id($episode["Category1"])["name"];
		$output .= "<a class=\"category_link\" href=\"" . $category1_url . "\">" . ucfirst($category1_name) . "</a>";
		$output .= "<script>console.log( " . $episode["Category2"] . " ) </script> "; 
		if ($episode["Category2"] != "0") {
			$output .= ", ";
			$category2_url = "category.php?category=" . $episode["Category2"];
			$category2_name = get_category_by_id($episode["Category2"])["name"];
			$output .= "<a class=\"category_link\" href=\"" . $category2_url . "\">" . ucfirst($category2_name) . "</a>";
		}
		$output .= "</div>";
		$output .= "<div> Season " .  $episode["Season"] . " , Episode " . $episode["EpNum"] . " </div>";
		$output .= "</div>";
		$output .= $html_under_cell;
		$output .= "</li>";
		return $output;
	}
	
	// choose a category 
	function make_random_homepage_row($type) {
		global $connection;
		// i want to display what category it is /what season it is 
		$output = "";
		if ($type == "category") {
			//$result = mysqli_query($connection, "SELECT COUNT(*) AS count FROM categories");
			//$row = mysqli_fetch_assoc($result); 
			//$total_categories = $row["count"];
			//$rand_category_id = rand(1, (int)$total_categories);
			//$output .= "<script>console.log( \"Category ID: " . $rand_category_id . "\" ) </script> "; 

			//$output .= "<h4>" .  ucfirst(get_category_by_id($rand_category_id)["name"]) . "</h4>";
			$output .= "<ul class=\"medium-block-grid-4 small-block-grid-1\">";
			$seed = srand();
			
			$episode_total_query = "SELECT DISTINCT(EpID) FROM episodes ";
			$episode_total_query .= "ORDER BY RAND() LIMIT 32 ";
			$result = mysqli_query($connection, $episode_total_query);
			$total_episodes = mysqli_num_rows($result);
			
			$id_array = array();
			$index_array = array();
			
			while ($row = mysqli_fetch_assoc($result)) {
				if (in_array($row["EpID"], $id_array) == false) {
					$id_array[] = $row["EpID"];
					$output .= "<script>console.log( \"Added ID: " . $row["EpID"] . "\" ) </script> "; 

				}
			} 
			$count = 1;
			
			while ($count <= 16) {
				$rand_episode_index = rand(1, (int)$total_episodes);
				if (in_array($rand_episode_index, $index_array)) {
					continue;
				}
				$output .= make_episode_cell($id_array[$rand_episode_index-1]);
				$index_array[] = $rand_episode_index;
				$count++;
			}
			
			$output .= "</ul>";

		}else if ($type == "season") {
			
		}

		echo $output;
	}
	
/* public version, for public users  */
	function attempt_user_login($username, $password) {
		$user = find_user_by_username($username);
		
		if ($user) {
			// found admin now check password
			if (password_check($password, $user["password"])) {
				return $user;
			}else {
				// password does not match 
				return false;
			}	
		}else {
			// admin not found 
			return false;
		}
		
	}
	
	// for pages where the site shows the same pages for logged in and not logged in
	// but different content/different options 
	function user_logged_in() {
		return isset($_SESSION["user_id"]);
	}
	
	function confirm_user_logged_in() {
		if (!user_logged_in()) {
			redirect_to("log_in.php");
		}			
	}	
	
	function find_user_by_id($user_id) {

		global $connection;
		
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		
		$query = "SELECT * ";
		$query .= "FROM users ";
		$query .= "WHERE id = {$safe_user_id} "; 
		$query .= "LIMIT 1";
		
		$user_set =  mysqli_query($connection, $query);
		confirm_query($user_set);
		if($user = mysqli_fetch_assoc($user_set)) {
			return $user;
		} else {
			return null;
		}	
	}
	
	function find_user_by_username($username) {

		global $connection;
		$safe_username = mysqli_real_escape_string($connection, $username);
	
		$query = "SELECT * ";
		$query .= "FROM users ";
		$query .= "WHERE username = '{$safe_username}' "; 
		$query .= "LIMIT 1";
		
		$user_set =  mysqli_query($connection, $query);
		confirm_query($user_set);
		if($user = mysqli_fetch_assoc($user_set)) {
			return $user;
		} else {
			return null;
		}	
		
	}

	function update_last_login_date($user_id) {
		global $connection;
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);

		$query =  "UPDATE users SET ";
		$query .= "last_login = NOW() ";
		$query .= "WHERE id = {$safe_user_id} ";
		$query .= "LIMIT 1";
		
		$result =  mysqli_query($connection, $query);
		// Test if there was a query error 
		if (!($result && mysqli_affected_rows($connection) == 1)) {
		  die("Database error. ");
		}
	}
	function convert_to_eastern_secs($time) {
		$offset =  3600 * 4; //(3600 * 4);
		echo $time  . " form convert function";
		echo ($time + $offset) . " form convert function";
		return $time + $offset;
	}
	
	//one day i will fix this to get user offset 
	function format_time_in_words ($time){		
		// to make it eastern :), will have to give ability to set timezone 
		$time = time()  - ($time+14400) ; // to get the time since that moment
			
		$tokens = array (
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);

		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
		}

	}
	
	function format_time_since_in_words($time_since) {
		$tokens = array (
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);
		foreach ($tokens as $unit => $text) {
			if ($time_since < $unit) continue;
			$numberOfUnits = floor($time_since / $unit);
			return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
		}		
	}
	
	function get_user_avatar($user_id) {
		global $connection;
		
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		
		$query = "SELECT file_path ";
		$query .= "FROM avatars ";
		$query .= "WHERE user_id = {$safe_user_id} "; 
		$query .= "LIMIT 1";
		
		$avatar_set =  mysqli_query($connection, $query);
		confirm_query($avatar_set);
		if($avatar = mysqli_fetch_assoc($avatar_set)) {
			return $avatar;
		} else {
			return null;
		}			
		
	}
	
	function init_user_avatar($username) {
		global $connection;
		$user_id =	find_user_by_username($username)["id"];
			
		$query = "INSERT INTO avatars ( ";
		$query .= "user_id ";
		$query .= ") VALUES ( " ;
		$query .= " {$user_id} ";
		$query .= " ) ";
		
		$result = mysqli_query($connection, $query);
		if (!($result)) {
			echo ("Avatar not initalized. ");
		}
	
	}
	
	function update_user_avatar($user_id, $new_path) {
		global $connection;
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		$safe_new_path = mysqli_real_escape_string($connection, $new_path);
		
		$query =  "UPDATE avatars SET ";
		$query .= "file_path = '{$safe_new_path}' ";
		$query .= "WHERE user_id = {$safe_user_id} ";
		$query .= "LIMIT 1";
		
		$result =  mysqli_query($connection, $query);
		// Test if there was a query error 
		if (!($result && mysqli_affected_rows($connection) == 1)) {
		  die("Database error. ");
		}
		
	}
	
	function get_friends_by_id($user_id) {
		global $connection;
		
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		
		$query = "SELECT friend_id ";
		$query .= "FROM friends ";
		$query .= "WHERE user_id = {$safe_user_id} "; 
		
		$friend_set =  mysqli_query($connection, $query);
		if(mysqli_affected_rows($connection) > 0) {
			return $friend_set;
		} else {
			return null;
		}			
		
		
	}
	
	function get_favorites_by_id($user_id) {
		global $connection;
		
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		
		$query = "SELECT  episode_id ";
		$query .= "FROM favorites ";
		$query .= "WHERE user_id = {$safe_user_id} "; 
		
		$favorites_set =  mysqli_query($connection, $query);
		if(mysqli_affected_rows($connection) > 0) {
			return $favorites_set;
		} else {
			return null;
		}			
	
		
	}

	function already_friend($user_id, $friend_id) {
		global $connection;
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		$safe_friend_id = mysqli_real_escape_string($connection, $friend_id);
		
		$query = "SELECT * FROM  friends ";
		$query .= "WHERE user_id = {$safe_user_id} ";
		$query .= "AND friend_id = {$safe_friend_id} ";
		
		$result = mysqli_query($connection, $query);
		if(mysqli_affected_rows($connection) > 0) {
			return true;
		}else {
			return false;
		}
	
	}
	
	function add_friend($user_id, $friend_id) {
		global $connection;
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		$safe_friend_id = mysqli_real_escape_string($connection, $friend_id);
		
		$query = "INSERT INTO friends ( ";
		$query .= "user_id, friend_id ";
		$query .= ") VALUES ( " ;
		$query .= " {$safe_user_id}, {$safe_friend_id} ";
		$query .= " ) ";
		
		$result = mysqli_query($connection, $query);
		if (!($result)) {
			echo ("Friend not added. ");
		}
	
	}
	
	function delete_friend($user_id, $friend_id) {
		global $connection;
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		$safe_friend_id = mysqli_real_escape_string($connection, $friend_id);		
		
		$query = "DELETE FROM friends ";
		$query .= "WHERE user_id = {$safe_user_id} ";
		$query .= "AND friend_id = {$safe_friend_id} ";
		$query .= "LIMIT 1";
		
		$result =  mysqli_query($connection, $query);
		// Test if there was a query error 
		if ($result && mysqli_affected_rows($connection) == 1){
			return true;
		}else {
			return false;
		}
	}		
	
	function show_error_old($error_string) {
		include_once("layouts/header.php");
		
		$output = "<div class=\"row\"> ";
		$output .= "<div class =\"large-12 columns\"> ";
		$output .= "<div class=\"panel\"> ";
		$output .= " <h4> Error </h4> ";
		$output .= $error_string; 
		$output .= "</div> ";
		$output .= "</div> ";
		$output .= "</div> ";
		
		echo $output;
		include_once("../includes/layouts/footer.php");
		die();
	}
	
	function show_message($message_string) {
		include_once("layouts/header.php");
		
		$output = "<div class=\"row\"> ";
		$output .= "<div class =\"large-12 columns\"> ";
		$output .= "<div class=\"panel\"> ";
		$output .= " <h4> Message </h4> ";
		$output .= $message_string; 
		$output .= "</div> ";
		$output .= "</div> ";
		$output .= "</div> ";
		
		echo $output;
		include_once("../includes/layouts/footer.php");
		die();
	}	
	
	function make_profile_friends_table($friend_set, $user_id) {
		$total_friends = mysqli_num_rows($friend_set);
		$output = "<ul class=\"medium-block-grid-4\"> ";
		$count = 1; 
		$friend = mysqli_fetch_assoc($friend_set);

		while ($friend && ($count <= 8)) {
			if ($_SESSION["user_id"] == $user_id){
				$output .= make_friend_cell($friend["friend_id"], make_delete_friend_checkbox($friend["friend_id"]));
			}else {
				$output .= make_friend_cell($friend["friend_id"]);
			}
			$count++;
			$friend = mysqli_fetch_assoc($friend_set);
		}

		$output .= "<ul>";
		if ($total_friends > 8) {
			$output .= "<a href=\"friends.php?user= " . $user_id . "\" id=\"see_more_page_link\">See Friends</a>";
		}
		
		if ($_SESSION["user_id"] == $user_id){
			$output .= "&nbsp;&nbsp;<a id=\"delete_friends_link\"> Delete Friends </a>";
		}
		return $output;
	}
	
	function make_delete_friend_checkbox($friend_id) {
		$output = "<span class=\"friend_delete_checkbox\">";
		$output .= "<input  type=\"checkbox\" name=\"delete_friend_{$friend_id}\" value=\"{$friend_id}\"> Delete Friend ";
		$output .= "</span>";
		return $output;
	}
	
	function make_friend_cell($friend_id, $html_under_cell="") {
		$user = find_user_by_id($friend_id);
		
		$output = "<li>";
		$output .= "<div id=\"\" >";
		$output .= "<a href=\"user.php?user=" . $user["id"] . "\" > ";
		$output .= "<img src=\"" . get_user_avatar($user["id"])["file_path"] . "\" >";
		$output .= "</a>";
		$output .= "<div id=\"friend_username\">";
		$output .= "<a href=\"user.php?user=" . $user["id"] . "\" > ";
		$output .= $user["username"];
		$output .= "</a>";
		$output .= "</div>";
		$output .= "</div>";
		$output .= $html_under_cell;
		$output .= "</li>";

		return $output;
	}
	
	function check_existance_by_id($table, $column, $id) {
		global $connection;
		$safe_table = mysqli_real_escape_string($connection, $table);
		$safe_column = mysqli_real_escape_string($connection, $column);
		$safe_id = mysqli_real_escape_string($connection, $id);
		
		$query = "SELECT * FROM {$safe_table} ";
		$query .= "WHERE {$safe_column} = {$safe_id} ";
		$query .= "LIMIT 1 ";

		$result = mysqli_query($connection, $query);
		if (mysqli_affected_rows($connection) > 0) {
			return true;
		}else {
			return false;
		}
	}

	function check_existance_of_item($table, $column, $item) {
		global $connection;
		$safe_table = mysqli_real_escape_string($connection, $table);
		$safe_column = mysqli_real_escape_string($connection, $column);
		$safe_item = mysqli_real_escape_string($connection, $item);
		
		$query = "SELECT * FROM {$safe_table} ";
		$query .= "WHERE {$safe_column} = '{$safe_item}' ";
		$query .= "LIMIT 1 ";
		$result = mysqli_query($connection, $query);
		if (mysqli_affected_rows($connection) > 0) {
			return true;
		}else {
			return false;
		}
	}
	
	function add_favorite($user_id, $episode_id) {
		global $connection;
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		$safe_episode_id = mysqli_real_escape_string($connection, $episode_id);

		$query = "INSERT INTO favorites (";
		$query .= "user_id, episode_id ";
		$query .= ") VALUES (";
		$query .= "{$safe_user_id}, {$safe_episode_id} )";
	
		$result = mysqli_query($connection, $query);
		if (!($result)) {
			return false;
		}else {
			return true;
		}
		
	
	}
	
	function already_favorite($user_id, $episode_id) {
		global $connection;
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		$safe_episode_id = mysqli_real_escape_string($connection, $episode_id);
		
		$query = "SELECT 1 FROM  favorites ";
		$query .= "WHERE user_id = {$safe_user_id} ";
		$query .= "AND episode_id = {$safe_episode_id} ";
		
		$result = mysqli_query($connection, $query);
		if(mysqli_affected_rows($connection) > 0) {
			return true;
		}else {
			return false;
		}
	
	}

	function show_error($error) {
		$_SESSION["error"] = $error;
		redirect_to("error.php");
	}
	
	function delete_favorite($user_id, $episode_id) {
		global $connection;
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		$safe_episode_id = mysqli_real_escape_string($connection, $episode_id);		
		
		$query = "DELETE FROM favorites ";
		$query .= "WHERE user_id = {$safe_user_id} ";
		$query .= "AND episode_id = {$safe_episode_id} ";
		$query .= "LIMIT 1";
		
		$result =  mysqli_query($connection, $query);
		// Test if there was a query error 
		if ($result && mysqli_affected_rows($connection) == 1){
			return true;
		}else {
			return false;
		}
	}
	
	function make_profile_favorites_table($favorite_set, $user_id) {
		$total_favorites = mysqli_num_rows($favorite_set);
		$output = "<ul class=\"medium-block-grid-3\"> ";
		$count = 1; 
		$favorite = mysqli_fetch_assoc($favorite_set);

		while ($favorite && ($count <= 6)) {
			if ($_SESSION["user_id"] == $user_id) {
				$output .= make_episode_cell($favorite["episode_id"], make_delete_favorite_checkbox($favorite["episode_id"]));
			}else {
				$output .= make_episode_cell($favorite["episode_id"]);	
			}
			$count++;
			$favorite = mysqli_fetch_assoc($favorite_set);
		}

		$output .= "</ul>";
		if ($total_favorites > 6) {
			$output .= "<a href=\"favorites.php?user=" . $user_id . "\" id=\"see_more_page_link\">See Favorites</a>";
		}
		if ($_SESSION["user_id"] == $user_id) {
			$output .= "&nbsp;&nbsp;<a id=\"delete_favorites_link\"> Delete Favorites </a>";
		}				
		return $output;
	}
	
	function make_delete_favorite_checkbox($episode_id) {
		$output = "<span class=\"fav_delete_checkbox\">";
		$output .= "<input  type=\"checkbox\" name=\"delete_fav_{$episode_id}\" value=\"{$episode_id}\"> Delete Favorite ";
		$output .= "</span>";
		return $output;
	}
	

	function make_favorites_table($user_id) {
		$favorite_set = get_favorites_by_id($user_id);
		if (!$favorite_set) {
			show_message("User has no favorites. ");
		}

		$favorites_per_page = 16;
		$total_favorites = mysqli_num_rows($favorite_set);
		$pages = (int) ceil($total_favorites / $favorites_per_page);

		$table = "";

		for ($page = 1; $page <= $pages; $page++) {
			$favorite = mysqli_fetch_assoc($favorite_set);
	
			if ($favorite) {
				$table .= "<ul class=\"medium-block-grid-4 episode_search_result_table\" id=\"episode_result_page_" . $page . "\"";
				$table .= ">";
			
				$count = 1; 
				while ($favorite && ($count <= $favorites_per_page)) {
					if ($_SESSION["user_id"] == $user_id){
						$table .= make_episode_cell($favorite["episode_id"],  make_delete_favorite_checkbox($favorite["episode_id"]));
					}else {
						$table .= make_episode_cell($favorite["episode_id"]);
					}
					$count++;
					$favorite = mysqli_fetch_assoc($favorite_set);

				}
						
				$table .= "</ul>";
			}
		}

		mysqli_free_result($favorite_set);
		echo $table;		
	}
	
	function make_friends_table($user_id) {
		$friend_set = get_friends_by_id($user_id);
		if (!$friend_set) {
			show_message("User has no friends. ");
		}

		$friends_per_page = 16;
		$total_friends = mysqli_num_rows($friend_set);
		$pages = (int) ceil($total_friends / $friends_per_page);

		$table = "";

		for ($page = 1; $page <= $pages; $page++) {
			$friend = mysqli_fetch_assoc($friend_set);
	
			if ($friend) {
				$table .= "<ul class=\"medium-block-grid-4 episode_search_result_table\" id=\"episode_result_page_" . $page . "\"";
				$table .= ">";
			
				$count = 1; 
				while ($friend && ($count <= $friends_per_page)) {
					if ($_SESSION["user_id"] == $user_id) {
						$table .= make_friend_cell($friend["friend_id"], make_delete_friend_checkbox($friend["friend_id"]));
					}else {
						$table .= make_friend_cell($friend["friend_id"]);
					}
					$count++;
					$friend = mysqli_fetch_assoc($friend_set);

				}
						
				$table .= "</ul>";
			}
		}

		mysqli_free_result($friend_set);
		echo $table;		
		
	}
	
	function get_related_videos_by_category($category1, $category2) {
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM episodes ";
		$query .= "WHERE (Category1 = {$category1} OR Category2 = {$category1}) ";
		$query .= " OR (Category1 =  {$category2} "; 
		if ($category2 != "0") 
			$query .= "OR Category2 = {$category2}";
		$query .= ")";
		$query .= "LIMIT 2";
		
		$episode_set =  mysqli_query($connection, $query);
		/*
		if(mysqli_affected_rows($connection) > 0) {
			return $episode_set;
		} else {
			return null;
		}*/
		return $episode_set; // i'd rather it return nothing and show a visible error 
		
	}
	
	
	function get_related_videos_by_season($season, $epnum) {
		global $connection;

		$query = "SELECT * ";
		$query .= "FROM episodes ";
		$query .= "WHERE Season = {$season} "; 
		if ($epnum < 26) {
			$query .= "AND EpNum > {$epnum}  ";
		}
		$query .= "LIMIT 2";
		
		$episode_set =  mysqli_query($connection, $query);
		return $episode_set; // i'd rather it return nothing and show a visible error 
	}

	function make_related_videos_table($episode) {
		// two by season, two by category 

		$related_set_season = get_related_videos_by_season($episode["Season"], $episode["EpNum"]);
		$related_set_category = get_related_videos_by_category($episode["Category1"], $episode["Category2"]);
		
		$output = "";
		$count = 1; 
		$next_episode = mysqli_fetch_assoc($related_set_season);

		while ($next_episode && ($count <= 2)) {
			$output .= make_episode_cell($next_episode["EpID"]);
			$count++;
			$next_episode = mysqli_fetch_assoc($related_set_season);
		}
		
		$count = 1; 
		$same_category_episode = mysqli_fetch_assoc($related_set_category);

		while ($same_category_episode && ($count <= 2)) {
			$output .= make_episode_cell($same_category_episode["EpID"]);
			$count++;
			$same_category_episode = mysqli_fetch_assoc($related_set_category);
		}		
		return $output;
	}
	
	/* comments code */
	
	function get_comment_by_id($comment_id) {
		global $connection;
		
		$safe_comment_id = mysqli_real_escape_string($connection, $comment_id);
		
		$query = "SELECT * ";
		$query .= "FROM comments ";
		$query .= "WHERE id = {$safe_comment_id} ";
		
		$result = mysqli_query($connection, $query);
		if ($result) {
			return mysqli_fetch_assoc($result);
		}
	}
	
	function get_votes_by_comment_id($comment_id) {
		global $connection; 
		
		$safe_comment_id = mysqli_real_escape_string($connection, $comment_id);

		
		$query = "SELECT SUM(value) AS total ";
		$query .= "FROM votes ";
		$query .= "WHERE comment_id = {$safe_comment_id} ";
		
		$result = mysqli_query($connection, $query);
		if ($result) {
			return mysqli_fetch_assoc($result)["total"];
		}else {
			return "null";
		}
	}
	
	function format_votes($votes) {
		if ($votes == "null") {
			return "error";
		}else if ((int)$votes > 0) {
			return "+ " . $votes; 
		}else {
			return $votes; 
		}
	}
	
	function submit_comment($user_id, $episode_id, $comment) {
		global $connection;
		
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		$safe_episode_id = mysqli_real_escape_string($connection, $episode_id);
		$safe_comment = mysqli_real_escape_string($connection, $comment);

		$query = "INSERT INTO comments (";
		$query .= "user_id, episode_id, text ";
		$query .= ") VALUES (";
		$query .= "{$safe_user_id}, {$safe_episode_id}, '{$safe_comment}' )";
	
		$result = mysqli_query($connection, $query);
		if (!($result)) {
			return false;
		}else {
			return true;
		}		
		
	}
	
	function get_comments_by_episode_id($episode_id, $offset) {
		global $connection;
		
		$safe_episode_id = mysqli_real_escape_string($connection, $episode_id);

		$query = "SELECT * ";
		$query .= "FROM comments ";
		$query .= "WHERE episode_id = {$safe_episode_id} ";
		$query .= "LIMIT {$offset}, 10 ";
		
		$result = mysqli_query($connection, $query);
		if ($result) {
			return $result;
		}else {
			return null;
		}
	}
	
	function init_comment_votes($comment_id) {
		global $connection;

		$query = "INSERT INTO votes (";
		$query .= "comment_id, user_id, value ";
		$query .= ") VALUES (";
		$query .= "{$comment_id}, 0, 0 )";
		
		$result = mysqli_query($connection, $query);
		if (!($result)) {
			return false;
		}else {
			return true;
		}		

	}
	
	// top votes
	function get_comments_sorted_by_top($episode_id, $offset) {
		global $connection;
		
		$safe_episode_id = mysqli_real_escape_string($connection, $episode_id);
		
		$query = "SELECT DISTINCT comments.* ";
		$query .= "FROM comments ";
		$query .= "JOIN votes ";
		$query .= "ON comments.id  = votes.comment_id ";
		$query .= "WHERE episode_id = {$safe_episode_id} ";
		$query .= "ORDER BY votes.value DESC ";
		$query .= "LIMIT {$offset}, 10 ";

		$result = mysqli_query($connection, $query);
		if ($result) {
			return $result;
		}else {
			return null;
		}

	}
	
	function get_comments_sorted_by_newest($episode_id, $offset) {
		global $connection;
		
		$safe_episode_id = mysqli_real_escape_string($connection, $episode_id);

		$query = "SELECT * ";
		$query .= "FROM comments ";
		$query .= "WHERE episode_id = {$safe_episode_id} ";
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT {$offset}, 10 ";

		$result = mysqli_query($connection, $query);
		if ($result) {
			return $result;
		}else {
			return null;
		}
	
	}
	
	function get_total_comments($episode_id) {
		global $connection;
		
		$safe_episode_id = mysqli_real_escape_string($connection, $episode_id);

		$query = "SELECT COUNT(*) ";
		$query .= "AS total ";
		$query .= "FROM comments ";
		$query .= "WHERE episode_id = {$safe_episode_id} ";
		
		$result = mysqli_query($connection, $query);
		if ($result) {
			return mysqli_fetch_assoc($result)["total"];
		}else {
			return -1;
		}
	}		
	

	
	
	function make_comments_table($episode_id, $offset, $sort="") {
		global $connection;
		
		if ($sort == "newest") {
			$comment_set = get_comments_sorted_by_newest($episode_id, $offset);
		}else if ($sort == "top") {
			$comment_set = get_comments_sorted_by_top($episode_id, $offset);
		}else {
			$comment_set = get_comments_by_episode_id($episode_id, $offset);
		}
		
		$has_comments = mysqli_affected_rows($connection); 
		$total = get_total_comments($episode_id);

		$output = ""; 
		if ($has_comments > 0 ) {
			$output .= "<select id=\"comment_sort\"> ";
			$output .= "<option value=\"top\" ";
			if ($sort == "top") $output .= "selected=\"selected\"";
			$output .= ">Top comments</option>";
			$output .= "<option value=\"newest\" ";
			if ($sort == "newest") $output .= "selected=\"selected\"";
			$output .= ">Newest first</option>";
			$output .= "</select>";
			
			while ($next_comment = mysqli_fetch_assoc($comment_set)) {
				$output .= make_comment_from_id($next_comment["id"]);
			}
			if (($total - $offset) > 10) {
				$output .= "<div id=\"load_comments_button\">Load More Comments </div>";
			}
		} else {
			$output .= "No comments posted. ";
		}
		
		return $output;
	}
	
	function make_comment_from_id($comment_id) {
		
		$comment = get_comment_by_id($comment_id);
		$user = find_user_by_id($comment["user_id"]);
		$votes = get_votes_by_comment_id($comment_id);
		$formatted_votes = format_votes($votes);
		$avatar = get_user_avatar($comment["user_id"])["file_path"];
			
		// bug where time since doesn;'t show, figure it out later (edit, this fixes that)
		$time = format_time_in_words(strtotime($comment["date"]));
		if ($time == "") {
			$time_text = "now";
		}else {
			$time_text = $time . " ago ";
		}
		
		$output = "<div class=\"row comment_output_panel\" data-comment-id=\"{$comment_id}\">";
		$output .= "<div>";
		$output .= "<img class=\"left\" src=\"" . $avatar . "\"/>"; 
		$output .= "</div>";
		$output .= "<div class=\"comment_output\">";
		$output .= "<div ><span class=\"comment_output_info_label\">";
		$output .= "<a href=\"user.php?user=". $comment["user_id"] . "\">" . $user["username"] ."</a>";
		$output .= "</span> ";
		$output .= "<span> " . $time_text . " </span></div>";
		$output .= "<div>"; 
		$output .= $comment["text"];
		$output .= "</div>";
		$output .= "<div class=\"vote_panel\">";
		$output .= "<span class=\"upvote_button  "; 
		
		if (user_logged_in() && already_upvoted($_SESSION["user_id"], $comment_id)) {
			$output .= "upvote_button_clicked";
		}
		
		$output .= "\">";
		$output .= "<i class=\"fi-like\" ></i> Upvote <span class=\"vote_display_box ";
		if ($votes != "null" && (int)$votes > 0) {
			$output .= " positive_votes ";
		}else if ($votes != "null" && (int)$votes < 0) {
			$output .= " negative_votes ";
		}else if ($votes != "null" && (int)$votes == 0) {
			$output .= " zero_votes ";
		}
		
		$output .= "\" >" . $formatted_votes . "</span>"; 
		$output .= "</span>";
		$output .= "<span class=\"downvote_button "; 
		
		if (user_logged_in() && already_downvoted($_SESSION["user_id"], $comment_id)) {
			$output .= "downvote_button_clicked";
		}
		
		$output .= "\">";
		$output .= "<i class=\"fi-dislike\" >   </i>";
		$output .= "</span>";
		$output .= "</div>";
		$output .= "</div>";
		$output .= "</div>";		
		
		return $output;
	}
	
	function already_upvoted($user_id, $comment_id) {
		global $connection;
		
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		$safe_comment_id = mysqli_real_escape_string($connection, $comment_id);

		$query = "SELECT value ";
		$query .= "FROM votes ";
		$query .= "WHERE user_id = {$safe_user_id} ";
		$query .= "AND comment_id = {$safe_comment_id} ";
		
		$result = mysqli_query($connection, $query);
		if ($result) {
			$value = mysqli_fetch_assoc($result)["value"];
			return ($value == "1");
		}else {
			return false;
		}
		
	}
	
	function exists_but_neutral($user_id, $comment_id) {
		global $connection;
		
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		$safe_comment_id = mysqli_real_escape_string($connection, $comment_id);

		$query = "SELECT value ";
		$query .= "FROM votes ";
		$query .= "WHERE user_id = {$safe_user_id} ";
		$query .= "AND comment_id = {$safe_comment_id} ";
		
		$result = mysqli_query($connection, $query);
		if ($result) {
			$value = mysqli_fetch_assoc($result)["value"];
			return ($value == "0");
		}else {
			return false;
		}
		
	}

	
	function already_downvoted($user_id, $comment_id) {
		global $connection;

		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		$safe_comment_id = mysqli_real_escape_string($connection, $comment_id);

		$query = "SELECT value ";
		$query .= "FROM votes ";
		$query .= "WHERE user_id = {$safe_user_id} ";
		$query .= "AND comment_id = {$safe_comment_id} ";
		
		$result = mysqli_query($connection, $query);
		if ($result) {
			$value = mysqli_fetch_assoc($result)["value"];
			return ($value == "-1");
		}else {
			return false;
		}
				
	}
	
	function update_vote($user_id, $comment_id, $vote) {
		global $connection;
		
		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		$safe_comment_id = mysqli_real_escape_string($connection, $comment_id);
		
		$query = "UPDATE votes SET ";
		$query .= "value = {$vote} ";
		$query .= "WHERE user_id = {$safe_user_id} ";
		$query .= "AND comment_id = {$safe_comment_id} ";

		$result = mysqli_query($connection, $query);
		if (!($result)) {
			return false;
		}else {
			return true;
		}
		
	}
	
	function add_vote($user_id, $comment_id, $vote) {
		global $connection;

		$safe_user_id = mysqli_real_escape_string($connection, $user_id);
		$safe_comment_id = mysqli_real_escape_string($connection, $comment_id);

		$query = "INSERT INTO votes (";
		$query .= "comment_id, user_id, value ";
		$query .= ") VALUES (";
		$query .= "{$safe_comment_id}, {$safe_user_id}, {$vote} )";
	
		$result = mysqli_query($connection, $query);
		if (!($result)) {
			return false;
		}else {
			return true;
		}		
	
	}
	
	function get_total_failed_login_attempts($interval = "15 minute") {
		global $connection;
		
		$query = "SELECT COUNT(1) AS failed "; 
		$query .= "FROM  failed_logins "; 
		$query .= "WHERE attempted > DATE_SUB(NOW(), INTERVAL {$interval})";

		$result = mysqli_query($connection, $query);
		if ($result) {
			return mysqli_fetch_assoc($result)["failed"];
		}else {
			return -1;
		}
	}
	
	function get_last_failed_attempt_time() {
		global $connection;
		
		$query = "SELECT MAX(attempted) AS attempted ";
		$query .= "FROM failed_logins ";
		
		$result = mysqli_query($connection, $query);
		if ($result) {
			return mysqli_fetch_assoc($result)["attempted"];
		}else {
			return -1;
		}
	}
	
	
	// we check this on the next page after they try to log in, first
	// if the answer is greater > 3, we just say "Too many failed login attemps, please wait x time 
	function get_failed_login_attempts_by_username($user, $interval = "15 minute") {
		global $connection;
		
		$query = "SELECT COUNT(1) AS failed "; 
		$query .= "FROM  failed_logins "; 
		$query .= "WHERE attempted > DATE_SUB(NOW(), INTERVAL {$interval}) ";
		$query .= "AND username = '{$user}' ";

		$result = mysqli_query($connection, $query);
		if ($result) {
			return mysqli_fetch_assoc($result)["failed"];
		}else {
			return -1;
		}		
	}
	
	function add_failed_attempt($username) {
		global $connection;
		//ip_address,
		// INET_ATON({$_SERVER['REMOTE_ADDR']}),
		$query = "INSERT INTO failed_logins (";
		$query .= "username,  attempted ) ";
		$query .= "VALUES (" ; 
		$query .= "'{$username}' ,  CURRENT_TIMESTAMP )";

		$result = mysqli_query($connection, $query);
		if (!($result)) {
			//return false;
			return mysqli_error($connection);
		}else {
			echo mysqli_error($connection);
			return true;
		}
	}

	function throttle_time_left($throttle) {
		global $connection;

		$query = "SELECT TIMEDIFF(NOW(), MAX(attempted)) AS time_since ";
		$query .= "FROM failed_logins ";
		
		$result = mysqli_query($connection, $query);
		if ($result) {
			//return strtotime(mysqli_fetch_assoc($result)["time_since"]) - $throttle;
			$parsed = date_parse(mysqli_fetch_assoc($result)["time_since"]);
			$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
			return $throttle - $seconds;
		}else {
			return null;
		}			
		
	}
	
	function username_throttle_time_left($username, $throttle) {
		global $connection;

		$query = "SELECT TIMEDIFF(NOW(), MAX(attempted)) AS time_since ";
		$query .= "FROM failed_logins ";
		$query .= "WHERE username = '{$username}' ";
		
		$result = mysqli_query($connection, $query);
		if ($result) {
			//return strtotime(mysqli_fetch_assoc($result)["time_since"]) - $throttle;
			$parsed = date_parse(mysqli_fetch_assoc($result)["time_since"]);
			$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
			return $throttle - $seconds;
		}else {
			return null;
		}			
		
	}
	
	
	function throttle_all_logins() {
		$throttle = array(10 => 1, 20 => 2, 30 => 15);
		$latest_attempt = (int) date('U', strtotime(get_last_failed_attempt_time()));
		
		$failed_attempts = get_total_failed_login_attempts();
        // assume the number of failed attempts was stored in $failed_attempts
        krsort($throttle);
        foreach ($throttle as $attempts => $delay) {
            if ($failed_attempts > $attempts) {
                // we need to throttle based on delay
                if (is_numeric($delay)) {
                    $remaining_delay = throttle_time_left($delay);
                    // output remaining delay
                    show_error('Our servers are being overloaded. You must wait ' . $remaining_delay . ' seconds before your next login attempt ');
                } 
                break;
            }
		}
	}
	
	function check_throttle_all() {
		$throttle = array(10 => 1, 20 => 2, 30 => 15);

		foreach ($throttle as $attempts => $delay) {

			if(get_total_failed_login_attempts() > $attempts) {
				$time_left = throttle_time_left($delay);
				
				if($time_left > 0) {
					$wait_time = format_time_since_in_words($time_left);
					show_error("'Our servers are being overloaded. Please wait {$wait_time} and try again. ");			
				}		
			}		
		}
	}	
?>