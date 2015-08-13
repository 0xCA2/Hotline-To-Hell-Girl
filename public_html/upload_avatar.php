<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php 
	chmod('images/avatars/', 0777);
	
	$target_dir = "images/avatars/";
	$target_file = $target_dir . basename($_FILES["user_image"]["name"]);
	$uploadOk = 1;
	$image_file_type = pathinfo($target_file,PATHINFO_EXTENSION);


	// Check if image file is a actual image or fake image
	if(isset($_POST["upload"])) {
		$check = getimagesize($_FILES["user_image"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			chmod('images/avatars/', 0755);
			show_error( "File is not an image.");
			$uploadOk = 0;
		}
	}
	
	// Check file size
	if ($_FILES["user_image"]["size"] > 500000) {
		chmod('images/avatars/', 0755);
		show_error( "Sorry, your file is too large.");
		$uploadOk = 0;
	}
	
	// Allow certain file formats
	if($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg"
	&& $image_file_type != "gif" ) {
		chmod('images/avatars/', 0755);
		show_error( "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
		$uploadOk = 0;
	}
	
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $target_file)) {
			update_user_avatar($_SESSION["user_id"], $target_file);
			chmod('images/avatars/', 0755);
			show_message( "The file ". basename( $_FILES["user_image"]["name"]). " has been uploaded.");
		} else {
			chmod('images/avatars/', 0755);
			show_error( "Sorry, there was an error uploading your file.");
		}
	}
	
?>



