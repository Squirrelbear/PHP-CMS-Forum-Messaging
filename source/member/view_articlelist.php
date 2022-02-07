<?php
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");
require_once("config_articles.php");

$sql = "SELECT * FROM articles ";
$prepstatement = $db->prepare($sql);
$prepstatement->execute();

$result = $prepstatement->fetchAll();

if(count($result) > 0)
{
	// sort the array using custom sort function by date
	//uasort($result, 'my_cmp');
	
	$article = '<h1>Articles</h1><table width="" border="1">
  		<tr>
    		<th width="100">Options</th>
    		<th width="400">Title</th>
    		<th width="120">Creator</th>
    		<th width="100">Comments</th>';
  	
	if($_SESSION['access'] == 1)
	{
		$article .= '<th>Admin Commands</th>';
	}
	
	$article .=	'</tr>';
	
	foreach($result as $field)
	{
		$article .= "<tr><td><center><form action='view_article.php'><input type='hidden' name='articleid' value='" . $field[articleid] . "' />";
		$article .= "<input type='submit' value='View' />";
		$article .= "</form></center></td><td>" . $field[title] . "</td>";
				
		$article .= "<td><center>" . $field[creator] . "</center></td>";
	
		// Now get a list of comments
		$sql = "SELECT * FROM comments WHERE ";
		$sql .= "articleid='" . $field[articleid] . "' ";
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();

		$res = $prepstatement->fetchAll();
		
		$article .= "<td><center>" . count($res) . "</center></td>";
		
		if($_SESSION['access'] == 1)
		{
			$article .= "<td><table border=0><tr><td><form method='post' action='delete_article.php'><input type='hidden' name='articleid' value='" . $field[articleid] . "'>";
			$article .= "<input type='submit' name='deletet' value='Delete'></form></td>";
			$article .= "<td><form method='post' action='edit_article.php'><input type='hidden' name='articleid' value='" . $field[articleid] . "'>";
			$article .= "<input type='submit' name='deletet' value='Edit'></form></td></tr></table></td>";
		}
		
		$article .= "</tr>";		
	}
	
	$article .= "</table>";
	
	echo $article;
			
}else {
	echo "<p align='center'>There are no articles!</p>";
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