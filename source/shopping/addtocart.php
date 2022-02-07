<?php
// Add item to order setting the tmp to true
// Then go back the product list page

$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
session_start();
require_once($virtualroot . "shopping/config.php");

//echo "Notes: " . $_POST["productid"] . $_POST["quantity"];

$sqlinsert = "insert into orders ";
$sqlinsert .= "(";
$sqlinsert .= "Name, ";
$sqlinsert .= "ProductID, ";
$sqlinsert .= "Quantity, ";
$sqlinsert .= "IsTmp";
$sqlinsert .= ") ";
$sqlinsert .= "values";
$sqlinsert .= "(";	
$sqlinsert .= "'" . $_SESSION['name'] . "', ";
$sqlinsert .= "'" . $_POST["productid"] . "', ";
$sqlinsert .= "'" . $_POST["quantity"] . "', ";
$sqlinsert .= "'true'";
$sqlinsert .= ")";

$prepstatement = $db->prepare($sqlinsert);
$prepstatement->execute();

//$count = $db->exec($sqlinsert); //returns affected rows

header("Location: view_productlist.php");

?>