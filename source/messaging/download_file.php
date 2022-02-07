<?php
// setup a string that contains a path to the sites root folder
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));

// Start the session so that session information can be used
session_start();

if($_SESSION["status"] == 1)
{
	// connect to the message database
	require($virtualroot . "messaging/config.php");
	
	// get the fileid
	$fileid = $_GET["fileid"];
	
	// use the fileid to execute an sql command that will collect the file
	$sql = "SELECT * FROM Attachments WHERE accessstring='" . $fileid . "' ";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();
	
	// collect the result
	$result = $prepstatement->fetchAll();
	
	// only continue if there are files found
	if(count($result) > 0)
	{
		// loop through results
		foreach($result as $field)
		{
			// default to allowing access to the file
			$allow = true;
			
			// test if the user is who they say they are
			if($field[user] != $_SESSION["name"])
			{
				$allow = false;
			}
			
			// only allow users who are who they say they are
			if($allow == true)
			{
				// generate the filepath
				$file=str_replace("localhost", "", $_SERVER['DOCUMENT_ROOT']) . "attachments/" . $field[filename]; 
				
				// tell the browser to force a download
				header("Content-type: application/force-download"); 
				
				// tell the browser it is a binary file transfer
				header("Content-Transfer-Encoding: Binary"); 
				
				// attach the file
				header("Content-disposition: attachment; filename=\"".basename($file)."\""); 
				
				// open the file and force the download
				readfile("$file");  
			}else {
				echo "You do not have permission to access this file!";
			}
		}
	}else {
		echo "ERROR NO SUCH FILE!!!";
	}
}else {
	echo "You need to be logged in to use this action!";
}
?>