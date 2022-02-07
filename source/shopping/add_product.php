<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");
require_once($virtualroot . "shopping/config.php");

$sqlinsert = "insert into products ";
$sqlinsert .= "(";
$sqlinsert .= "Name, ";
$sqlinsert .= "Cost, ";
$sqlinsert .= "InStock, ";
$sqlinsert .= "Description, ";
$sqlinsert .= "Review_ArticleID";
$sqlinsert .= ") ";
$sqlinsert .= "values";
$sqlinsert .= "(";	
$sqlinsert .= "'" . $_POST["name"] . "', ";
$sqlinsert .= "'" . $_POST["Cost"] . "', ";
$sqlinsert .= "'" . $_POST["quantity"] . "', ";
$sqlinsert .= "'" . $_POST["description"] . "', ";
$sqlinsert .= "'" . $_POST["review"] . "'";
$sqlinsert .= ")";

$prepstatement = $db->prepare($sqlinsert);
$prepstatement->execute();

//$count = $db->exec($sqlinsert); //returns affected rows

echo "Product has now been added.";

$db = NULL;

require_once($virtualroot."includes/foot.php");
?>