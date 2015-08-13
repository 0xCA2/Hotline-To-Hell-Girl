<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php 
    $_SESSION["user_id"] = null;
    $_SESSION["username"] = null;
    redirect_to("index.php");
?>