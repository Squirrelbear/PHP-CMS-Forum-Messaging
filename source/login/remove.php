<?php
require_once("config.php");
session_start();

if ($_SESSION["access"]==1) { 
	
	$sql = "delete from users where name='" . $_POST["user"] . "' ";
    $prepstatement = $db->prepare($sql);
    $prepstatement->execute();
	
}

header("Location: admin.php");
?>

