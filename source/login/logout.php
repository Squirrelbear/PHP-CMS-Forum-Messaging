<?php
require_once("config.php");

session_start();

// change users status to inactive
$sqlupdate = "update users set ";
$sqlupdate .= "status='0' ";
$sqlupdate .= "where name='" . $_SESSION["name"] . "' ";

$count = $db->exec($sqlupdate);

// !!! here for reference, but not to be used, because it stuffs the whole program up !!!
// session_destroy();

// this does not fix the problem, but it means the code is safer!!!
$_SESSION["access"] = 0;
$_SESSION["name"] = " ";
//$_SESSION["password"] = " ";
$_SESSION["status"] = 0;

$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");

echo "<p>You have successfully logged out...</p>";
echo "<p>WARNING: YOU HAVE TO CREATE A NEW SESSION BEFORE DOING ANYTHING ELSE ON THIS SITE!!!!!</p>"
?>