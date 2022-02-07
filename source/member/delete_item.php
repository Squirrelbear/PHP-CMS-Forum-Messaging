<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot . "member/config.php");

session_start();

$redirect = "";

if($_POST["dtp"] == "p")
{
	// collect data from hidden fields ...
	$discussion = $_POST["d"];
	$topic = $_POST["t"];
	$post = $_POST["p"];
	
	// only delete the one post
	$sqldelete  = "delete from posts ";
	$sqldelete .= "where id = '" . $post . "' AND ";
	$sqldelete .= "topic_id = '" . $topic . "' AND ";
	$sqldelete .= "discussion_id = '" . $discussion . "' ";
	
	$count = $db->exec($sqldelete);
	
	$redirect = $virtualroot . "member/view.php";
	
}else if($_POST["dtp"] == "t") {
	// collect data from hidden fields ...
	$discussion = $_POST["d"];
	$topic = $_POST["t"];
	
	// delete all posts
	$sql = "SELECT * FROM posts ";
    $prepstatement = $db->prepare($sql);
    $prepstatement->execute();

    $result = $prepstatement->fetchAll();
    $countx = count($result);
    if ($countx > 0) {
 	   foreach($result as $field) {
			$sqldelete  = "delete from posts ";
			$sqldelete .= "where id = '" . $field[id] . "' AND ";
			$sqldelete .= "topic_id = '" . $topic . "' AND ";
			$sqldelete .= "discussion_id = '" . $discussion . "' ";
	
			$count = $db->exec($sqldelete);
		}
	}
	
	// delete the topic
	$sqldeletex  = "delete from topic ";
	$sqldeletex .= "where id = '" . $topic . "' AND ";
	$sqldeletex .= "discussion_id = '" . $discussion . "' ";
	
	$count = $db->exec($sqldeletex);
	
	$redirect = $virtualroot . "member/view.php";
	
}else {
	// collect data from hidden fields ...
	$discussion = $_POST["d"];
	
	// delete all posts
	$sql = "SELECT * FROM posts ";
    $prepstatement = $db->prepare($sql);
    $prepstatement->execute();

    $result = $prepstatement->fetchAll();
    $countx = count($result);
    if ($countx > 0) {
 	   foreach($result as $field) {
			$sqldelete  = "delete from posts ";
			$sqldelete .= "where id = '" . $field[id] . "' AND ";
			$sqldelete .= "topic_id = '" . $field[topic_id] . "' AND ";
			$sqldelete .= "discussion_id = '" . $discussion . "' ";
	
			$count = $db->exec($sqldelete);
		}
	}
	
	// delete all topics
	$sql = "SELECT * FROM topic ";
    $prepstatement = $db->prepare($sql);
    $prepstatement->execute();

    $result = $prepstatement->fetchAll();
    $countx = count($result);
    if ($countx > 0) {
 	   foreach($result as $field) {
			$sqldelete  = "delete from topic ";
			$sqldelete .= "where id = '" . $field[id] . "' AND ";
			$sqldelete .= "discussion_id = '" . $discussion . "' ";
	
			$count = $db->exec($sqldelete);
		}
	}
	
	// delete the discussion
	$sqldelete  = "delete from discussion ";
	$sqldelete .= "where id = '" . $discussion . "' ";

	$count = $db->exec($sqldelete);
	
	$redirect = $virtualroot . "member/view.php";
	
}

$db = NULL;

header("Location: " . $redirect);
?>