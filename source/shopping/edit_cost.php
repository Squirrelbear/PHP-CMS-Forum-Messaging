<?php
	session_start();
	$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
	require_once($virtualroot . "shopping/config.php");
	
	$sqlupdate = "update products set ";
	$sqlupdate .= "Cost='" . $_POST['cost'] . "' ";
	$sqlupdate .= "where ProductID='" . $_POST['productid'] . "' ";
	
	//echo $_POST['cost'];
	
	
	// run the sql command
	$count = $db->exec($sqlupdate);
	
	header("Location: view_productlist.php");
?>