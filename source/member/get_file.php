<?php
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");
require_once("config_files.php");

$fileid = $_GET["fileid"];

$sql = "SELECT * FROM files WHERE ";
$sql .= "accessstring='" . $fileid . "' ";
$prepstatement = $db->prepare($sql);
$prepstatement->execute();

$result = $prepstatement->fetchAll();

if(count($result) > 0)
{
	// sort the array using custom sort function by date
	//uasort($result, 'my_cmp');
	
	foreach($result as $field)
	{
		$allow = true;
		if($field[accesslevel] != 0 && $_SESSION["access"] != $field[accesslevel])
		{
			$allow = false;
		}
		
		if($allow == true)
		{
			$text = "<h1 style='line-height: 0px;'>" . $field[filename] . "</h1>";
					
			$text .= "Uploaded by: <b>" . $field[uploader] . "</b><br />";
			
			$text .= "Description: " . $field[description] . "<br />";
			
			$text .= "Downloads: " . $field[downloads];
			
			$text .= "<form method='get' action='download_file.php'><input type='hidden' name='fileid' value='" . $field[accessstring] . "'><input type='submit' value='Download' /></form>";
		}else {
			$text = "You do not have permission to access this file!";
		}
	}
	
	echo $text;
}else {
	echo "<p align='center'>There is no such file!</p>";
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