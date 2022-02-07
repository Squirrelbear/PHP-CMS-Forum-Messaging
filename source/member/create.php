<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot . "member/config.php");

session_start();

require_once($virtualroot."includes/head.php");

$which = $_POST["w"];

$outlink = $virtualroot . "member/create_item.php";
$title = "You are creating a ";

// create strings for print out
if($which == "p") {
	$discussion = $_POST["d"];
	$topic = $_POST["t"];
	
	$title .= "Post";
	$form = "<p class='create'>Title: <input type='text' name='title'><br><br>Content: <input type='textarea' name='content'><br><input type='submit' name='submit'></p>";
}else if($which == "t") {
	$discussion = $_POST["d"];

	$title .= "Topic";
	$form = "<p class='create'>Title: <input type='text' name='title'><br><input type='submit' name='submit'></p>";
}else {
	$title .= "Discussion";
	$form = "<p class='create'>Discussion Title: <input type='text' name='name_'><br><br>Description: <input type='textarea' name='description'><br><input type='submit' name='submit'></p>";
}

echo $title;
echo "<form method='post' action='" . $outlink . "'>";

// send these varaibles to the create_item.php file for using as references
echo "<input type='hidden' name='ty' value='" . $which  . "'>";
echo "<input type='hidden' name='t' value='" . $_POST["t"] . "'>";
echo "<input type='hidden' name='d' value='" . $_POST["d"] . "'>";

echo $form;
echo "</form>";

$db = NULL;

require_once($virtualroot."includes/foot.php");
?>