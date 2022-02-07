<?php
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once("config_files.php");

$fileid = $_GET["fileid"];

$sql = "SELECT * FROM files WHERE ";
$sql .= "accessstring='" . $fileid . "' ";
$prepstatement = $db->prepare($sql);
$prepstatement->execute();

$result = $prepstatement->fetchAll();

if(count($result) > 0)
{
	foreach($result as $field)
	{
		$allow = true;
		if($field[accesslevel] != 0 && $_SESSION["access"] != $field[accesslevel])
		{
			$allow = false;
		}
		
		if($allow == true)
		{
			//echo $field[downloads];
			// Increase the number of downloads
			$downloads = $field[downloads] + 1;
			
			//echo $downloads;
			
			// prepare the sql statement
			$sql = "UPDATE files SET downloads='" . $downloads . "' WHERE accessstring='" . $fileid . "'";
	
			// execute and count the results
			$statement = $db->prepare($sql);
			$statement->execute();
			
			//header("Location: " . str_replace("localhost", "", $_SERVER['DOCUMENT_ROOT']) . "uploadedfiles/" . $field[filename]);
			//echo str_replace("localhost", "", $_SERVER['DOCUMENT_ROOT']) . "uploadedfiles/" . $field[filename];
			//$dir="/path/to/file/"; 
			//if (isset($_REQUEST["file"])) { 
				$file=str_replace("localhost", "", $_SERVER['DOCUMENT_ROOT']) . "uploadedfiles/" . $field[filename]; //$dir.$_REQUEST["file"]; 
				header("Content-type: application/force-download"); 
				header("Content-Transfer-Encoding: Binary"); 
			//	header("Content-length: ".filesize($file)); 
				header("Content-disposition: attachment; filename=\"".basename($file)."\""); 
				readfile("$file"); 
			//} else { 
			//	echo "No file selected"; 
			//} 
		}else {
			echo "You do not have permission to access this file!";
		}
	}
}else {
	echo "ERROR NO SUCH FILE";
}
?>