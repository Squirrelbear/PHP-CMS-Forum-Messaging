<?php
// setup a string that contains a path to the sites root folder
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));

// Start the session so that session information can be used
session_start();

// include the generic header
require_once($virtualroot."includes/head.php");

if($_SESSION["status"] == 1)
{
	// connect to the message database
	require($virtualroot . "messaging/config.php");
	
	// generate and execute sql code that updates the current message to having now been viewed
	$sql = "UPDATE message SET Viewed='1' WHERE MailID='" . $_POST["id"] . "'";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();
	
	// generate and execute sql code that gets the current message
	$sql = "SELECT * FROM message WHERE MailID='" . $_POST["id"] . "'";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();
	
	// collect the result
	$result = $prepstatement->fetchAll();
	
	// loop through all of the results
	foreach($result as $field)
	{
		// generate all the specific information
		$content = "<div style='width: 492px;'><table width='492' border='0' style='border: thin #000000 solid'>
		  <tr>";
		  
		$importance = $viewed = "";
		
		// generate css code
		if($field["Importance"] == "1")
		{
			$importance = "color: #FF0000; ";
			$imp = "High";
		}else {
			$imp = "Normal";
		}
		
		if($field["Viewed"] == "0")
		{
			$viewed = "font-weight: bold;";
		}
		
		$style = " style='" . $importance . $viewed . "'";
		  
		$content .= "<td width='486'><div style='float: left;'><b>Subject:</b> </div> <div" . $style . "> " . $field["Title"] . "</div></td>
		  </tr>
		  <tr>
			<td><strong>From:</strong> " . $field["Sender"] . "</td>
		  </tr>
		  <tr>
			<td>This message was sent on " . $field["DateSent"] . " with " . $imp . " level of importance.
			</td>
		  </tr>";
		
		// display links to attachments if there are any
		if($field["Attachments"] > 0)
		{
			$content .= "<tr><td>";
			
			// generate and execute sql code that gets the list of attachments
			$sql = "SELECT * FROM Attachments WHERE MailID='" . $field["MailID"] . "'";
			$prepstatement = $db->prepare($sql);
			$prepstatement->execute();
			
			// collect the result
			$at_result = $prepstatement->fetchAll();
			
			// loop through all of the results
			foreach($at_result as $att)
			{
				$content .= "<a href='download_file.php?fileid=" . $att["accessstring"] . "'>" . $att["filename"] . "</a> &nbsp;";
			}
			
			$content .= "</td></tr>";
		}
		
		$content .= "<tr>
			<td><div style='border: thin #000000 solid'>" . $field["Message"] . "</div></td>
		  </tr>
		</table><form method='post' action='writemessage.php'>
								<input type=\"hidden\" name=\"id\" value=\"" . $field["MailID"] . "\" />
								<input type=\"submit\" name=\"button6\" id=\"button8\" value=\"Reply\" style='float: right;' />
							</form></div>";
	}
	
	// print the text
	echo $content;
}else {
	echo "You must be logged in to view this page!";
}

// include the footer
require_once($virtualroot."includes/foot.php");
?>