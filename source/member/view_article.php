<?php
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");
require_once("config_articles.php");

$articleid = $_GET["articleid"];

$sql = "SELECT * FROM articles WHERE ";
$sql .= "articleid='" . $articleid . "' ";
$prepstatement = $db->prepare($sql);
$prepstatement->execute();

$result = $prepstatement->fetchAll();

if(count($result) > 0)
{
	// sort the array using custom sort function by date
	//uasort($result, 'my_cmp');
	
	foreach($result as $field)
	{
		$article = "<h1 style='line-height: 0px;'>" . $field[title] . "</h1>";
				
		$article .= "Article by: <b>" . $field[creator] . "</b><br /><br />";
		
		$article .= $field[content] . "<br /><br />";
	}
	
	// Now get a list of comments
	$sql = "SELECT * FROM comments WHERE ";
	$sql .= "articleid='" . $articleid . "' ";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();

	$res = $prepstatement->fetchAll();

	$comments = "<h2>Comments:</h2>";
	$comments .= "<form method='post' action='add_comment.php'><input type=hidden name='articleid' value='" . $articleid . "'>";
	$comments .= "Title: <input type='text' name='title'> Author: <input type='text' name='creator'><br />";
	$comments .= "<textarea name='content' rows=5 cols=44></textarea><br /><input type='submit' value='Add Comment'><br /><br />";

	if(count($res) > 0)
	{
		// sort the array using custom sort function by date
		//uasort($result, 'my_cmp');
	
		$comments .= "<table border=1>";
	
		foreach($res as $fld)
		{
			$comments .= "<tr><td><table border=0><tr><td>Title:<b> " . $fld[title] . "</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				
			$comments .= "<td>Author: " . $fld[creator] . "</td></tr><tr><td colspan=2>";
		
			$comments .= $fld[content] . "</td></tr></table></td></tr>";
		}
		
		$comments .= "</table>";
			
	}else {
		$comments = "<p align='center'>There are currently no comments for this article. Be the first to add one.</p>";
	}
	
	echo $article;
	echo $comments;
			
}else {
	echo "<p align='center'>There is no such article!</p>";
}

$db = NULL;

require_once($virtualroot."includes/foot.php");

// function for array sort using date
function my_cmp($a, $b) {
	if(!is_array($a) || !is_array($b)) return 0;
	$date_a = $a[last_edited] ? strtotime($a[last_edited]) : 0;
	$date_b = $b[last_edited] ? strtotime($b[last_edited]) : 0;
	return $date_a - $date_b;
}
?>