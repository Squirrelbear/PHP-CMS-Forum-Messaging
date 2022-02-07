<?php
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");
require_once("config_files.php");

if($_SESSION["status"] == 1)
{
	$title = "<h2>Public Access Files</h2> <form action='upload.php'><input type='submit' value='Upload a File' /></form>";
	
	$sql = "SELECT * FROM files WHERE public='1' ";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();

	$result = $prepstatement->fetchAll();

	if(count($result) > 0)
	{	
		$tbl = "<table border=1><tr><th>Filename</th><th>Who can Download</th><th>Uploader</th><th>Total Downloads</th><th>Download</th></tr>";
		foreach($result as $field)
		{
			$tbl .= "<tr><td>" . $field[filename] . "</td><td>";
			
			if($field[accesslevel] == 0)
			{
				$tbl .= "Everyone";
			}else {
				$tbl .= "Administrators";
			}
			
			$tbl .= "</td><td>" . $field[uploader] . "</td><td>" . $field[downloads] . "</td>";
			
			$tbl .= "<td><form method='get' action='get_file.php'><input type='hidden' name='fileid' value='" . $field[accessstring] . "'><input type='submit' value='Download' /></form></td></tr>";
		}
		$tbl .= "</table>";
	}else {
		$tbl = "No Files Found!";
	}
	echo $title;
	echo $tbl;
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