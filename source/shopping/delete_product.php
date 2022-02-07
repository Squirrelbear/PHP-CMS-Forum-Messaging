<?php
	session_start();
	$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
	require_once($virtualroot . "shopping/config.php");
	
	if($_SESSION['access'] == 1)
	{
		// only delete the one post
		$sqldelete  = "delete from products ";
		$sqldelete .= "where ProductID = '" . $_POST['productid'] . "'";
	
		$count = $db->exec($sqldelete);
	
		header("Location: view_productlist.php");
	}else {
		echo "<center>You do not have permission to do this action!</center>";
	}
?>