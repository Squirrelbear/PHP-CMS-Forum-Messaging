<?php
require_once("config.php");
session_start();

if ($_SESSION["access"]==1) { 
	
	$sql = "update users set status='0' where name!='" . $_SESSION['name'] . "'";
    $prepstatement = $db->prepare($sql);
    $prepstatement->execute();
	
}

header("Location: admin.php");
?>

