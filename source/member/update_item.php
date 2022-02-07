<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot . "member/config.php");

session_start();

$discussion = $_POST["d"];
$topic = $_POST["t"];
$post = $_POST["p"];

$typ = $_SESSION["dtp"];

$redirect = $virtualroot . "member/view.php";

if($_SESSION["dtp"] == "d")
{
	// therefore caller is editing the discussions table
	$name = $_POST["discussion_name"];
	$description = $_POST['discussion_description'];
	// debugging stuff
	// echo $name;
	// echo $description;
}else if($_SESSION["dtp"] == "t") {
	// therefore caller is editing the topics table
	$name = $_POST["topic_name"];
	$user = $_SESSION["name"];
	$date_ = date();
}else {
	// therfore caller is editing the posts table
	$title = $_POST["post_title"];
	$content = $_POST["post_content"];
}

// setup the sql commands
if($typ == "d")
{
	// discussion
	$sqlupdate = "update discussion set ";
	$sqlupdate .= "name='" . $name . "' ";
	$sqlupdate .= "description='" . $description . "' ";
	$sqlupdate .= "where id='" . $discussion . "' ";
	// run the sql command
	$count = $db->exec($sqlupdate);
	//debugging stuff
	// echo $sqlupdate;
}else if($typ == "t") {
	// topic
	$sqlupdate = "update topic set ";
	$sqlupdate .= "name='" . $name . "' ";
	$sqlupdate .= "last_edit='" . date() . "' ";
	$sqlupdate .= "last_editor='" . $user . "' ";
	$sqlupdate .= "where id='" . $topic . "' ";
	$sqlupdate .= "discussion_id='" . $discussion . "' ";
	// run the sql command
	$count = $db->exec($sqlupdate);
}else {
	// posts
	$sqlupdate = "update posts set ";
	$sqlupdate .= "title='" . $title . "' ";
	$sqlupdate .= "content='" . $content . "' ";
	$sqlupdate .= "where id='" . $post . "' ";
	$sqlupdate .= "topic_id='" . $topic . "' ";
	$sqlupdate .= "discussion_id='" . $discussion . "' ";
	// run the sql command
	$count = $db->exec($sqlupdate);
}

// run the sql command
//$count = $db->exec($sqlupdate);

//debugging stuff
//echo $count;

$db = NULL;

// redirect to appropriate page
header("Location: " . $redirect);
?>
