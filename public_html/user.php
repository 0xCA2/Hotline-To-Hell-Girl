<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
	if (!isset($_GET["user"])) {
		set_error_output("No user selected");
	}else {
		$user = find_user_by_id($_GET["user"]);
		if (!$user) {
			set_error_output("User not found. ");
		}else {
			if ($user["id"] == $_SESSION["user_id"]) {
				$users_profile = true;
			}else {
				$users_profile = false;
			}
		}
	}
?>
<?php include("../includes/layouts/header.php"); ?>
<div class="row">
	
	<div class="large-4 columns">
		<div class="panel">
			<h2><?php echo $user["username"]; ?></h2>
			<img src="<?php echo get_user_avatar($user["id"])["file_path"]; ?>"
			width="" height="">
			<div>
				<div><span>Member since:</span> <?php echo substr($user["activation_date"], 0, 10); ?> </div>
				<div><span>Last login:</span> <?php	echo format_time_in_words(strtotime($user["last_login"])) . " ago"; ?> </div>
			</div>
		
		</div>
		
		<div class="panel">
			<?php 
				if (!$users_profile) {
					echo "<div class=\"text-red-1\"> <a href=\"add_friend.php?user=" . $user["id"] . "\">";
					echo "<i class=\"fi-heart\" id=\"user_add_friend_icon\"></i>";
					echo " Add to Friends";
					echo "</div>";
				}else {
					include("user_cp.php");
				}
			?>
		</div>
	</div>
	<div class="large-8 columns">
		<div class="panel">
			<h2> Favorites </h2>
			<?php
				if ($favorites_set = get_favorites_by_id($user["id"])){
					echo make_profile_favorites_table($favorites_set, $user["id"]);
				} else {
					echo "User has no favorites. ";
				}
			?>
		</div>
		
		<div class="panel">
			<h2> Friends </h2>
			<?php
				if ($friend_set = get_friends_by_id($user["id"])) {
					echo make_profile_friends_table($friend_set, $user["id"]);					
				} else {
					echo "User has no friends. ";
				}

			?>
		</div>
	</div>
</div>
	
<?php include("../includes/layouts/footer.php"); ?>
