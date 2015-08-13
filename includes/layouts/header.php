<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php 
	// Need to get page name to find active page. 
	$page_name = $_SERVER['PHP_SELF'];
	
	// Need to see if user is logged in 
	if (user_logged_in()) {
		$context = "logged in";
	}else {
		$context = "default";
	}
?>

<!doctype html>
<html class="no-js" lang="en">
  <head data-user-id="<?php if ($context == "logged in") {echo $_SESSION["user_id"];}
							else {echo "-1";} ?>">
    <meta charset="utf-8" />
    <!--meta name="viewport" content="width=device-width, initial-scale=1.0" /-->
    <title>Hotline To Hell Girl</title>
    <link rel="stylesheet" href="stylesheets/foundation.css" />
	<link rel="stylesheet" href="stylesheets/main.css" />
	<link rel="stylesheet" href="foundation-icons/foundation-icons.css" />
	<link rel="icon" href="images/favicon.ico" />	
    <script src="javascripts/vendor/modernizr.js"></script>

 </head>
  
 <body>

    <nav id="main_bar" class="top-bar" data-topbar >
      <ul class="title-area">
        <li class="name"></li>
        <li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
      </ul>
      <section class="top-bar-section">
        <ul>
		  <li id="topbar_image"><img  src="images/hthg_small2.png"></li>	

		  <li <?php if(strpos($page_name, "index.php") !== false) echo "class=\"active\""?>><a href="index.php">Home</a></li>
		  <li <?php if(strpos($page_name, "faq.php") !== false) echo "class=\"active\""?>><a href="faq.php">FAQ</a></li>
		  <li <?php if(strpos($page_name, "about.php") !== false) echo "class=\"active\""?>><a href="about.php">About</a></li>
		  <?php 
			if ($context == "default") {
				$output = "<li ";
				if(strpos($page_name, "log_in.php") !== false) $output .= "class=\"active\"";
				$output .= "><a href=\"log_in.php\">Login</a> </li> ";
				echo $output;
			} else if ($context == "logged in") {
				$output = "<li><a href=\"log_out.php\">Logout</a> </li> ";
				echo $output;
			}
		  ?>
		  <li id="topbar_search_form"  class="has-form">
		  <form id="topbar_form" action="search.php" method="get">
		  <div class="row collapse">
			<div class="large-8 small-9 columns">
			  <input id="topbar_search_input" type="text" placeholder="Enter a name." name="epname" >
			</div>
			
			<div id="topbar_search_form_button" class="large-4 small-3 columns" >
			  <!--a href="search.php" class="alert button expand">Search</a-->
			  <input type="submit" class="alert button expand" value ="Search">
			</div>
		  </div>
		  </form>
		  </li>
		<?php
		if ($context == "logged in") {
			$output = "<li id=\"topbar_user_section\">";
			$output .= "<a href=\"user.php?user=". $_SESSION["user_id"] . "\">";
			$output .= " <span id =\"topbar_user_image\" > ";
			$output .= "<img  src=\"" .get_user_avatar( $_SESSION["user_id"])["file_path"] . "\" >";
			$output .= "</span>";
			$output .= urlencode($_SESSION["username"]);
			$output .= "</a>";
			$output .= "</li>";	
			echo $output;
		}
		?>
		
		</ul>			
      </section>
    </nav>