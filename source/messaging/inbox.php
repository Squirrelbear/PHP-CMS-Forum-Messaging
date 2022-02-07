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
	
	// create the basic layout
	$content = "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"writemessage.php\">
	  <label>
	  <input type=\"hidden\" name=\"id\" value=\"new\" />
	  <input type=\"submit\" name=\"button\" id=\"button\" value=\"New Message\" />
	  </label>
	</form>
	<table width=\"802\" border=\"1\">
	  <tr>
		<td width=\"69\"><div align=\"center\"><strong>Date Sent</strong></div></td>
		<td width=\"117\"><div align=\"center\"><strong>Sender</strong></div></td>
		<td width=\"50\"><div align=\"center\"><strong>Attachments</strong></div></td>
		<td width=\"365\"><div align=\"center\"><strong>Subject</strong></div></td>
		<td width=\"200\"><div align=\"center\"><strong>Options</strong></div></td>
	  </tr>";
	
	// Generate and execute an sql command that finds all of the current users mail items
	$sql = "SELECT * FROM message WHERE Recipient='" . $_SESSION["name"] . "'";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();
	
	// collect all of the results
	$result = $prepstatement->fetchAll();
	
	$tmp_content = "";
	
	// loop through for each of the results
	foreach($result as $field)
	{
		$tmpcontent = "";
		
		// Setup the item specific information
		$tmpcontent .= "<tr>
						<td><div align=\"center\">" . $field["DateSent"] . "</div></td>
						<td><div align=\"center\">" . $field["Sender"] . "</div></td>
						<td><div align=\"center\">" . $field["Attachments"] . "</div></td>";
		
		$importance = $viewed = "";
		
		// generate css scheme for text
		if($field["Importance"] == "1")
		{
			$importance = "color: #FF0000; ";
		}
		
		if($field["Viewed"] == "0")
		{
			$viewed = "font-weight: bold;";
		}
		
		$style = " style='" . $importance . $viewed . "'";
		
		// apply custom scheme if required
		$tmpcontent .= "<td" . $style . ">" . $field["Title"] . "</td>";
		
		// generate remainder of specifc content
		$tmpcontent .= "<td>
					  <div align=\"center\">
						<form method='post' action='viewmessage.php'>
							<input type=\"hidden\" name=\"id\" value=\"" . $field["MailID"] . "\" />
							<input type=\"submit\" name=\"button2\" id=\"button2\" value=\"Open\" />
						</form>
						<form method='post' action='writemessage.php'>
							<input type=\"hidden\" name=\"id\" value=\"" . $field["MailID"] . "\" />
							<input type=\"submit\" name=\"button6\" id=\"button8\" value=\"Reply\" />
						</form>
						<form method='post' action='deletemessage.php'>
							<input type=\"hidden\" name=\"id\" value=\"" . $field["MailID"] . "\" />
							<input type=\"submit\" name=\"button3\" id=\"button3\" value=\"Delete\" />
						</form>
					  </div>
					</td>
				  </tr>";
		
		// code used to flip the order by putting newer messages first
		$tmp_tmp_content = $tmp_content;
		$tmp_content = $tmpcontent . $tmp_tmp_content;
	}
	
	// add the completed flip code to the table
	$content .= $tmp_content;
	
	$content .= "</table>";
	
	// print content to window
	echo $content;
}else {
	echo "You need to be logged into before trying to access this page!";
}

// include the generic footer
require_once($virtualroot."includes/foot.php");
?>