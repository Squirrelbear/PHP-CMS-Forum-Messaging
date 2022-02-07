<?php
// Set all of the products that are in the cart to not temp
// Display total amount due...
// Then minus from the stock in the products table
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot . "shopping/config.php");

$sqlupdate = "update orders set ";
$sqlupdate .= "IsTmp='false' ";
$sqlupdate .= "where Name='" . $_SESSION['name'] . "' ";
		
// run the sql command
$prepstatement = $db->prepare($sqlupdate);
$prepstatement->execute();

header("Location: viewcart.php");
?>