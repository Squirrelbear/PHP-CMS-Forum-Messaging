<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot . "member/config.php");

session_start();

$typ = $_POST["ty"];

$redirect = $virtualroot . "member/view.php";

if($typ == "p")
{
	$discussion = $_POST["d"];
	$topic = $_POST["t"];
	
	/*// count all the posts and add 1
	$sql = "SELECT * FROM posts WHERE ";
	//$sql .= "topic_id = '" . $topic . "' ";
	$sql .= "discussion_id = '" . $discussion . "' ";
    $prepstatement = $db->prepare($sql);
    $prepstatement->execute();
	
	$result = $prepstatement->fetchAll();
	
	$i;
	
    foreach($result as $field) {
		$i++;
    }
	
	$countx = $i + 1;*/
	
	$content = $_POST['content'];
	
	$sql = "SELECT * FROM badwords";
    $prepstatement = $db->prepare($sql);
    $prepstatement->execute();
	
	$result = $prepstatement->fetchAll();
		
    foreach($result as $field) {
		$content = str_replace($field[word], "***", $content);
    }
	
	// create new post
	$sqlinsert = "insert into posts ";
	$sqlinsert .= "(";
	$sqlinsert .= "creator, ";
	$sqlinsert .= "topic_id, ";
	$sqlinsert .= "title, ";
	$sqlinsert .= "discussion_id, ";
	$sqlinsert .= "content ";
	$sqlinsert .= ")";
	$sqlinsert .= "values ";
	$sqlinsert .= "(";
	$sqlinsert .= "'" . $_SESSION['name'] . "', ";
	$sqlinsert .= "'" . $topic . "', ";
	$sqlinsert .= "'" . $_POST['title'] . "', ";
	$sqlinsert .= "'" . $discussion . "', ";
	$sqlinsert .= "'" . $content . "' ";
	$sqlinsert .= ")";
	
	$count = $db->exec($sqlinsert);
	
}else if($typ == "t") {
	
	$discussion = $_POST["d"];

	/*// count all the topics and add 1
	$sql = "SELECT * FROM topic WHERE ";
	$sql .= "discussion_id = '" . $discussion . "' ";
    $prepstatement = $db->prepare($sql);
    $prepstatement->execute();
	
	$result = $prepstatement->fetchAll();
	
	$i;
	
    foreach($result as $field) {
		$i++;
    }
	
	$countx = $i++;*/
	
	$time = time();
	
	// create new topic
	$sqlinsert = "insert into topic ";
	$sqlinsert .= "(";
	$sqlinsert .= "last_edit, ";
	$sqlinsert .= "last_editor, ";
	$sqlinsert .= "creator, ";
	$sqlinsert .= "discussion_id, ";
	$sqlinsert .= "name ";
	$sqlinsert .= ")";
	$sqlinsert .= "values ";
	$sqlinsert .= "(";
	$sqlinsert .= "'" . $time . "', "; // insert date here
	$sqlinsert .= "'" . $_SESSION['name'] . "', ";
	$sqlinsert .= "'" . $_SESSION['name'] . "', ";
	$sqlinsert .= "'" . $discussion . "', ";
	$sqlinsert .= "'" . $_POST['title'] . "'";
	$sqlinsert .= ")";
	
	$count = $db->exec($sqlinsert);
	
}else {
	/*// count all the discussion and add 1
	$sql = "SELECT * FROM discussion ";
    $prepstatement = $db->prepare($sql);
    $prepstatement->execute();
	
	$result = $prepstatement->fetchAll();
	
	$i;
	
    foreach($result as $field) {
		$i++;
    }
	
	$countx = $i + 1;*/
	
	// create new discussion
	$sqlinsert = "insert into discussion ";
	$sqlinsert .= "(";
	$sqlinsert .= "description, ";
	$sqlinsert .= "name ";
	$sqlinsert .= ") ";
	$sqlinsert .= "values ";
	$sqlinsert .= "(";
	$sqlinsert .= "'" . $_POST['description'] . "', ";
	$sqlinsert .= "'" . $_POST['name_'] . "' ";
	$sqlinsert .= ")";
		
	$count = $db->exec($sqlinsert);
}

$db = NULL;

// redirect to appropriate page
header("Location: " . $redirect);
?>