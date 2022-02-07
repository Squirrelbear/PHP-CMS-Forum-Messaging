<?php
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");
require_once("config.php");

if($_SESSION["status"] == 1)
{
	// user is logged in so test which area to show
	// but first check if this is the first time entering
	if($_GET["dtp"] != "p" && $_GET["dtp"] != "t" && $_GET["dtp"] != "d")
	{
		// set to show discussions
		$dtp = "d";
	}else {
		$dtp = $_GET["dtp"];
	}
	
	$discussion = $_GET["d"];
	$topic = $_GET["t"];
	
	//print_r($_SESSION);
	//echo "Debug Discussion ID - Session: " . $_SESSION["disucssion;
	//echo "<br>Debug Discussion ID: " . $_GET["d"] . "            " . $discussion;
	//echo "<br>Debug Topic ID: " . $_GET["t"] . "            " . $topic;
	
	$title = "<h2>You are viewing ";
	
	if($dtp == "p")
	{		
		$title .= "Posts</h2>";
	
		$sql = "SELECT * FROM posts WHERE ";
		$sql .= "discussion_id='" . $discussion . "' AND ";
		$sql .= "topic_id='" . $topic . "' ";
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();

		$result = $prepstatement->fetchAll();

		if(count($result) > 0)
		{	
			foreach($result as $field)
			{
				// create a new table area for each and every single post
				$tbl .= "<table border=1 class='posttable'>";
				
				// first row has title of post and who it was by
				$tbl .= "<tr><td>Title: " . $field[title] . " <b>BY</b> " . $field[creator] . "</td></tr>";
								
				// second row contains the content
				$tbl .= "<tr><td colspan=2>" . $field[content] . "</td></tr>";
				
				$tbl .= "</table><br><br>";
			}
		}else {
			$tbl = "No Posts found!";
		}
		
		// go up a level
		$add = "<table><tr><td><form method='get' action='view.php'>";
		$add .= "<input type='hidden' name='dtp' value='t'>";
		$add .= "<input type='hidden' name='d' value='" . $discussion . "'>";
		$add .= "<input type='hidden' name='t' value='" . $topic . "'>";
		$add .= "<input type='submit' value='Back to Topics'></form></td>";
		
		// create a break between ...
		$add .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		
		// add new
		$add .= "<td><center><form method='post' action='create.php'>";
		$add .= "<input type='hidden' name='d' value='" . $discussion . "'>";
		$add .= "<input type='hidden' name='t' value='" . $topic . "'>";
		$add .= "<input type='hidden' name='w' value='p'>";
		$add .= "<input type='submit' name='createt' value='Create New Post'></form></center></td></tr></table>";
		
		// create the initial part of the message:
		$where = "You are in: ";
		
		// create the item that says which discussion you are in
		$sql = "SELECT * FROM discussion WHERE ";
		$sql .= "id='" . $discussion . "' ";
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();

		$result = $prepstatement->fetchAll();
		
		foreach($result as $field)
		{
			// create the initial part of the message:
			$where = "<table border=0><tr><td>You are in: </td><td>";
			$where .= $field[name] . "</td></tr></table>";
		}
		
		// create the seperator
		$where .= ">";

		// create the item that says which topic you are in
		$sql = "SELECT * FROM topic WHERE ";
		$sql .= "discussion_id='" . $discussion . "' AND ";
		$sql .= "id='" . $topic . "' ";
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();

		$result = $prepstatement->fetchAll();
		
		foreach($result as $field)
		{
			$where .= $field[name];
		}

		// print the completed content to the screen ...
		echo $title;
		echo $where;
		echo $add;
		echo $tbl;
	}else if($dtp == "t") {
		$title .= "Topics</h2>";
	
		$sql = "SELECT * FROM topic WHERE ";
		$sql .= "discussion_id='" . $discussion . "' ";
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();

		$result = $prepstatement->fetchAll();

		if(count($result) > 0)
		{

			$tbl = "<table border=1 class='tbld'>";

			// sort the array using custom sort function by date
			uasort($result, 'my_cmp');
	
			// setup table header 
			$tbl .= "<tr><th>Topic Name</th><th>View Topic</th><th>Creator</th><th>Lasted Editor</th>";
			
			if($_SESSION["access"] == 1)
			{
				$tbl .= "<th>Edit Topic</th><th>Delete Topic</th>";
			}
			
			$tbl .= "</tr>";

			foreach($result as $field)
			{
				$tbl .= "<tr><td>";
				$tbl .= $field[name] . "</td>";
				
					// create view item button
					$tbl .= "<td>";
					$tbl .= "<form method='get' action='view.php'>";
					$tbl .= "<input type='hidden' name='dtp' value='p'>";
					$tbl .= "<input type='hidden' name='d' value='" . $field[discussion_id] . "'>";
					$tbl .= "<input type='hidden' name='t' value='" . $field[id] . "'>";
					$tbl .= "<input type='submit' name='viewt' value='View Topic'></form></td>";
					
				$tbl .= "<td>" . $field[creator] . "</td>";
				$tbl .= "<td>" . $field[last_editor];
			
				if($_SESSION["access"] == 1)
				{
					// create new button
					$tbl .= "<td>";
					$tbl .= "<form method='post' action='change.php'>";
					$tbl .= "<input type='hidden' name='d' value='" . $field[discussion_id] . "'>";
					$tbl .= "<input type='hidden' name='t' value='" . $field[id] . "'>";
					$tbl .= "<input type='hidden' name='dtp' value='t'>";
					$tbl .= "<input type='submit' name='changet' value='Edit Topic'></form></td>";
					
					// create delete button
					$tbl .= "<td>";
					$tbl .= "<form method='post' action='delete_item.php'>";
					$tbl .= "<input type='hidden' name='dtp' value='t'>";
					$tbl .= "<input type='hidden' name='d' value='" . $field[discussion_id] . "'>";
					$tbl .= "<input type='hidden' name='t' value='" . $field[id] . "'>";
					$tbl .= "<input type='submit' name='deletet' value='Delete Topic'></form></td>";
				}
				
				$tbl .= "</tr>";
			}
			
			$tbl .= "</table>";
			
		}else {
			$tbl = "<p align='center'>There are no topics in this discussion yet!</p>";
		}
		
		// go up a level
		$add = "<table><tr><td><form method='get' action='view.php'>";
		$add .= "<input type='hidden' name='dtp' value='d'>";
		$add .= "<input type='submit' value='Back to Discussions'></form></td>";
		
		// create a break between ...
		$add .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		
		// add new
		$add .= "<td><center><form method='post' action='create.php'>";
		$add .= "<input type='hidden' name='d' value='" . $discussion . "'>";
		$add .= "<input type='hidden' name='w' value='t'>";
		$add .= "<input type='submit' name='createt' value='Create New Topic'></form></center></td></tr></table>";
		
		// create the initial part of the message:
		$where = "You are in: ";
		
		// create the item that says which discussion you are in
		$sql = "SELECT * FROM discussion WHERE ";
		$sql .= "id='" . $discussion . "' ";
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();

		$result = $prepstatement->fetchAll();
		
		foreach($result as $field)
		{
			$where .= $field[name];
		}
		
		// print the completed content to the screen ...
		echo $title;
	  	echo $where;
		echo $add;
		echo $tbl;
	}else {
		$title .= "Discussions</h2>";
	
		$sql = "SELECT * FROM discussion ";
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();

		$result = $prepstatement->fetchAll();

		if(count($result) > 0)
		{
			$tbl = "<table border=1 class='tbld'>";
						
			foreach($result as $field)
			{
				$tbl .= "<tr><td>";
				$tbl .= $field[name] . "</td>";
				$tbl .= "<td>" . $field[description] . "</td>";
			
				// create view item button
				$tbl .= "<td>";
				$tbl .= "<form method='get' action='view.php'>";
				$tbl .= "<input type='hidden' name='dtp' value='t'>";
				$tbl .= "<input type='hidden' name='d' value='" . $field[id] . "'>";
				$tbl .= "<input type='submit' name='viewd' value='View Discussion'></form></td>";
				
				if($_SESSION["access"] == 1)
				{
					// create new button
					$tbl .= "<td>";
					$tbl .= "<form method='post' action='change.php'>";
					$tbl .= "<input type='hidden' name='d' value='" . $field[id] . "'>";
					$tbl .= "<input type='submit' name='changed' value='Edit Discussion'></form></td>";
					
					// delete button
					$tbl .= "<td>";
					$tbl .= "<form method='post' action='delete_item.php'>";
					$tbl .= "<input type='hidden' name='dtp' value='d'>";
					$tbl .= "<input type='hidden' name='d' value='" . $field[id] . "'>";
					$tbl .= "<input type='submit' name='deleted' value='Delete Discussion'></form></td>";
				}
			
				$tbl .= "</tr>";
			}
		
			$tbl .= "</table>";
		}else {
			$tbl = "<p align='center'>There are no discussions yet!</p>";
		}
		$add = "";
		if($_SESSION["access"] == 1)
		{
			$add = "<center><form method='post' action='create.php'>";
			$add .= "<input type='hidden' name='w' value='d'>";
			$add .= "<input type='submit' name='created' value='Create New Discussion'></form></center>";
		}
		
		// print the completed content to the screen ...
		echo $title;
		echo $add;
		echo $tbl;
	}
}else {
	echo "<h1>Alert!!!</h1>";
	echo "You are not logged in so are trying to bypass my login system!!! Do not attempt this!!! Go and login and retry!!!";
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