<?php
// setup a string that contains a path to the sites root folder
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));

// Start the session so that session information can be used
session_start();

if($_SESSION["status"] == 1)
{
	// connect to the message database
	require_once($virtualroot . "messaging/config.php");
	
	// generate and execute an sql command that deletes the specific message and as a security precaution only do it if the user name is the current users
	$sql = "DELETE FROM message WHERE MailID='" . $_POST["id"] . "' AND Recipient='" . $_SESSION["name"] . "'";
	
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();
	
	// generate and execute sql code that gets the list of attachments
	$sql = "SELECT * FROM Attachments WHERE MailID='" . $_POST["id"] . "'";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();
	
	// collect the result
	$at_result = $prepstatement->fetchAll();
	
	// loop through all of the results
	foreach($at_result as $att)
	{
		// http://www.tizag.com/phpT/filedelete.php
		// Delete file
		if(!unlink(str_replace("localhost", "", $_SERVER['DOCUMENT_ROOT']) . "attachments/" . $att["filename"]))
		{
			echo "File Destory Failed!!<br />";
		}
	}
	
	// generate and execute an sql command that deletes the specific message and as a security precaution only do it if the user name is the current users
	$sql = "DELETE FROM Attachments WHERE MailID='" . $_POST["id"] . "'";
		
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();	
	
	// redirect the browser to the inbox page
	header("location: " . $virtualroot . "messaging/inbox.php");
}else {
	echo "You need to be logged in to use this action!";
}
?>