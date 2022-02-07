<?php
require_once("config_articles.php");
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");

$creator = checkstr($_SESSION["name"]);//$_POST["creator"]);
$content = checkstr($_POST["content"]);
$title = checkstr($_POST["title"]);

$sqlinsert = "insert into articles ";
$sqlinsert .= "(";
$sqlinsert .= "creator, ";
$sqlinsert .= "content, ";
$sqlinsert .= "title";
$sqlinsert .= ")";
$sqlinsert .= "values ";
$sqlinsert .= "(";	
$sqlinsert .= "'" . $creator . "', ";
$sqlinsert .= "'" . $content . "', ";
$sqlinsert .= "'" . $title . "'";
$sqlinsert .= ")";
$count = $db->exec($sqlinsert); //returns affected rows

echo "Article has now been added.";

$db = NULL;

require_once($virtualroot."includes/foot.php");
?>