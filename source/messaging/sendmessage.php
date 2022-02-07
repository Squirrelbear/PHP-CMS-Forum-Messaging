<?php
// setup a string that contains a path to the sites root folder
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));

// Start the session so that session information can be used
session_start();

// Add the generic header information
require_once($virtualroot."includes/head.php");

if($_SESSION["status"] == 1)
{
	// Load the upload file class incase it is required
	require_once($virtualroot . "messaging/uploadfile_script.php");
	
	// Connect to the Message Database
	require($virtualroot . "messaging/config.php");
	
	// enable and disable debug information with this variable
	$debug = false;
	
	// The code is hardcoded to start with 3, but it is safer to check
	if($_POST["Files"] > 0)
	{
		// filenames of the first uploaded files of each slot
		$num = $_POST["Files"];
		$_SESSION['file_originals'] = array();
		$_SESSION['file_originals'] = array_pad($_SESSION['file_originals'], $num, NULL);
	}
	
	// Test if there is a request to send the message to all the users
	if($_POST["recipient"] != "###ALLUSERS###")
	{
		require_once($virtualroot . "messaging/create_unique.php");
		
		// Generate the SQL command that will add the new message to one user
		$sql = "INSERT INTO message (DateSent, Importance, Message, Recipient, Sender, Title, Viewed, Attachments, unique_code) VALUES ('" . date("d/m/Y") . "', '" . $_POST["importance"] ."', '" . $_POST["message"] . "', '" . $_POST["recipient"] . "', '" .$_SESSION["name"] ."', '" . $_POST["subject"] . "', '0', '0', '" . $str . "')";
	
		if($debug){echo "sql command insert: " . $sql;}
	
		$db->exec($sql);
		
		// Generate an sql statment to find the just submitted data
		$sql = "SELECT * FROM message WHERE unique_code='" . $str . "'";
		
		if($debug){echo "<br>sql command select: " . $sql . "<br>";}
		
		// run the statement
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();
		
		$result = $prepstatement->fetchAll();
		
		if($debug){print_r($prepstatement);}
		
		if($debug){echo "count: " . count($result) . "<br>";}
		
		// get the MailID
		foreach($result as $field)
		{
			$mail_id = $field["MailID"];
			if($debug){echo "result found!";}
		}	
		
		$number = 0;
		
		if($debug){echo "mail id: " . $mail_id . "<br>";}
		
		// Upload files for attachements
		for($i = 1; $i<=$_POST["Files"]; $i++)
		{
			// only try and upload for slots that have something there
			if($_FILES['file' . $i]['name'] != "")
			{
				// Run upload file php command here
				$number += uploadfile($i, $mail_id, $_POST["recipient"]);
			}
		}
		
		// generate and execute sql that adds a marker showing the number of attached files
		$sql = "UPDATE message SET Attachments='" . $number . "' WHERE MailID='" . $mail_id . "'";
		
		// Prepare the sql statment and execute it
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();
	}else {
		require($virtualroot . "login/config.php");
	
		// Prepare the SQL statement that will select all of the users and execute the command
		$sql = "SELECT * FROM users";
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();
		
		// Collect all of the users
		$result = $prepstatement->fetchAll();
		
		require($virtualroot . "messaging/config.php");
		
		// filenames of the first uploaded files of each slot
		// Original spot, but caused errors not using the definition globally...
		//$_SESSION['file_originals'] = array(NULL, NULL, NULL);
		
		// Loop through each returned result and send the message to every user in the database
		foreach($result as $field)
		{
			require($virtualroot . "messaging/create_unique.php");
			
			// Generate the SQL command that will add the new message to one user
			$sql = "INSERT INTO message (DateSent, Importance, Message, Recipient, Sender, Title, Viewed, Attachments, unique_code) VALUES ('" . date("d/m/Y") . "', '" . $_POST["importance"] ."', '" . $_POST["message"] . "', '" . $field["name"] . "', '" .$_SESSION["name"] ."', '" . $_POST["subject"] . "', '0', '0', '" . $str . "')";
	
			if($debug){echo "sql command insert: " . $sql;}
			
			$db->exec($sql);
		
			// Generate an sql statment to find the just submitted data
			$sql = "SELECT * FROM message WHERE unique_code='" . $str . "'";
			
			if($debug){echo "<br>sql command select: " . $sql . "<br>";}
			
			// run the statement
			$prepstatement = $db->prepare($sql);
			$prepstatement->execute();
			
			$_result = $prepstatement->fetchAll();
			
			if($debug){print_r($prepstatment);}
			
			if($debug){echo "count: " . count($result) . "<br>";}
			
			// get the MailID
			foreach($_result as $_field)
			{
				$mail_id = $_field["MailID"];
				if($debug){echo "result found!";}
			}	
			
			$number = 0;
			
			if($debug){echo "mail id: " . $mail_id . "<br>";}
			
			// Upload files for attachements
			for($i = 1; $i<=$_POST["Files"]; $i++)
			{
				// only try and upload for slots that have something there
				if($_FILES['file' . $i]['name'] != "")
				{
					// Run upload file php command here
					$number += uploadfile($i, $mail_id, $field["name"]);
				}
			}
			
			// generate and execute sql that adds a marker showing the number of attached files
			$sql = "UPDATE message SET Attachments='" . $number . "' WHERE MailID='" . $mail_id . "'";
			
			// Prepare the sql statment and execute it
			$prepstatement = $db->prepare($sql);
			$prepstatement->execute();
		}
	}
	
	// Prepare and display a confirmation message
	$content = "Message has been successfully sent to " . $_POST["recipient"] . ". <a href='" . $virtualroot . "messaging/inbox.php'>Click Here</a> to return to your inbox.";
	
	echo $content;
}else {
	echo "You must be logged in to use this action!";
}

// Add the footer to the bottom of the page
require_once($virtualroot."includes/foot.php");
?>