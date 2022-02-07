<?php
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot . "member/config.php");

$which = $_POST["dtp"];

$outlink = $virtualroot . "member/update_item.php";
$title = "<h1>You are updating a ";

// create strings for print out
if($which == "p") {
	$discussion = $_POST["d"];
	$topic = $_POST["t"];
	$post = $_POST["p"];
	
	$title .= "Post</h1>";
	
	//get data from the database to put into slots
	$sql = "SELECT * FROM posts WHERE ";
	$sql .= "discussion_id='" . $discussion . "' AND ";
	$sql .= "topic_id='" . $topic . "' AND ";
	$sql .= "id='" . $post . "' ";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();

	$result = $prepstatement->fetchAll();

	foreach($result as $field)
	{
		$form = "<p class='create'>Title: <input type='text' name='post_title' value='";
		$form .= $field[title];
		$form .= "'><br><br>Content: <input type='textarea' name='post_content' value='";
		$form .= $field[content];
		$form .= "></p><center><input type='submit' name='updatep' value='Update fields'></center>";
	}
}else if($which == "t") {
	$discussion = $_POST["d"];
	$topic = $_POST["t"];

	$title .= "Topic</h1>";

	//get data from the database to put into slots
	$sql = "SELECT * FROM topic WHERE ";
	$sql .= "discussion_id='" . $discussion . "' AND ";
	$sql .= "id='" . $topic . "' ";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();

	$result = $prepstatement->fetchAll();

	foreach($result as $field)
	{
		$form = "<p class='create'>Title: <input type='text' name='topic_name' value='";
		$form .= $field[name];
		$form .= "'></p><center><input type='submit' name='updatet' value='Update fields'></center>";
	}
}else {
	$discussion = $_POST["d"];
	
	$title .= "Discussion</h1>";

	//get data from the database to put into slots
	$sql = "SELECT * FROM discussion WHERE ";
	$sql .= "id='" . $discussion . "' ";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();

	$result = $prepstatement->fetchAll();

	foreach($result as $field)
	{
		$form = "<p class='create'>Discussion Title: <input type='text' name='discussion_name' value='";
		$form .= $field[name];
		$form .= "'><br><br>Description: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='textarea' name='discussion_description' value='";
		$form .= $field[description];
		$form .= "'></p><center><input type='submit' name='updated' value='Update fields'></center>";
	}
}

require_once($virtualroot."includes/head.php");

// debugging stuff
//echo $_SESSION["dtp"] . " " . $_POST["d"] . " " . $_POST["t"] . " " . $_POST["p"];

echo $title;
echo "<form method='get' action='" . $outlink . "' class='lrfrm'>";

// send these varaibles to the create_item.php file for using as references
echo "<input type='hidden' name='ty' value='" . $which  . "'>";
echo "<input type='hidden' name='p' value='" . $_POST["p"] . "'>";
echo "<input type='hidden' name='t' value='" . $_POST["t"] . "'>";
echo "<input type='hidden' name='d' value='" . $_POST["d"] . "'>";

echo $form;
echo "</form>";
echo "<br><br><br><br><br><br><br>";

$db = NULL;

require_once($virtualroot."includes/foot.php");
?>