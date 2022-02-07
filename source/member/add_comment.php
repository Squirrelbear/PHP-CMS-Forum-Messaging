<?php
require_once("config_articles.php");
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));

$creator = $_POST["creator"];
$content = $_POST["content"];
$title = $_POST["title"];
$articleid = $_POST["articleid"];

$sqlinsert = "insert into comments ";
$sqlinsert .= "(";
$sqlinsert .= "creator, ";
$sqlinsert .= "content, ";
$sqlinsert .= "title, ";
$sqlinsert .= "articleid";
$sqlinsert .= ")";
$sqlinsert .= "values ";
$sqlinsert .= "(";	
$sqlinsert .= "'" . $creator . "', ";
$sqlinsert .= "'" . $content . "', ";
$sqlinsert .= "'" . $title . "', ";
$sqlinsert .= "'" . $articleid . "'";
$sqlinsert .= ")";
$count = $db->exec($sqlinsert); //returns affected rows

$db = NULL;

header('Location: view_article.php?articleid=' . $articleid);
?>