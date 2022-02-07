<?php
	session_start();
	$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
	require_once($virtualroot . "shopping/config.php");
	
	// only delete the one post
	$sqldelete  = "delete from orders ";
	$sqldelete .= "where Name = '" . $_SESSION['name'] . "' AND IsTmp='true'";
	
	$count = $db->exec($sqldelete);
	
	header("Location: viewcart.php");
?>