<?php
// setup a string that contains a path to the sites root folder
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));

// Start the session so that session information can be used
session_start();

// Include the generic header
require_once($virtualroot."includes/head.php");

if($_SESSION["status"] == 1)
{
	// Generate the javascript code section
	$content = "<script type='text/javascript'>
	function SetSelected()
	{
		// get the number of items in the dropdown list
		len = document.MyForm.selectuser.length
		
		// initialise the variable
		selected = 'none';
		
		// loop through all of the items in the list
		for(i = 0; i<len; i++)
		{
			// test if the item is selected
			if(document.MyForm.selectuser[i].selected)
			{
				// if it is selected then grab the value of dropdown list
				selected = document.MyForm.selectuser[i].value;
				
				// set the recipient text box to the selected value
				document.MyForm.recipient.value = selected;
			}
		}
		return selected;
	}
	
	function Validate(frm)
	{
		// test if the recipient field is empty
		if(frm.recipient.value == '')
		{
			// if it is empty show an error message and return that the form is not valid
			alert('Please enter a recipient!');
			return(false);
		}
		
		// create a new array to hold the list of all users
		var users = new Array();
		
		// give the option for all users
		users[0] = '###ALLUSERS###';
		// now add all the rest of the users to the array
		";
	
	// Loop through all of the users and add them to the array
	
	// initialise the count variable
	$i = 0;
	
	// connect to the users database
	require($virtualroot . "login/config.php");
	
	// create the sql statment that will return all users from the database and execute it
	$sql = "SELECT * FROM users";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();
	
	// collect all of the results
	$result = $prepstatement->fetchAll();
	
	// loop through all of the results
	foreach($result as $field)
	{
		// increase the count
		$i++;
		
		// add another user to the list
		$content .= "users[" . $i . "] = '" . $field[name] . "';";
	}
	
	// finish generating the javascript
	$content .=	" // Set the arraysize for clarification
			var arraysize = " . $i . ";
			
			// loop through all of the items in the array
			for(var i = 0; i<=arraysize; i++)
			{
				// test if the recipient entered is equal to a the current iteration value from the database
				if(frm.recipient.value == users[i])
				{
					// if it is then return true because the recipient is valid
					return(true);
				}
			}
	
		// if the code gets to here then the user name that was entered into the recipient field was not valid so display an error and return false
		alert('Invalid Recipient, because the recipient does not exist!');
		return(false);
	}
	
	function AddField()
	{
		// open the marker object in the html code
		var myMarker = document.getElementById('marker');
		
		// get the number of file fields there already are
		var NumOfFiles = document.MyForm.Files.value;
		// increment the number of files
		NumOfFiles = parseInt(NumOfFiles) + 1;
		// set the number of file fields
		document.MyForm.Files.value = NumOfFiles;
		
		// create the new input object
		var myObj = document.createElement('input');
		// set the attribute so that it is a file field object
		myObj.type = 'file';
		myObj.name = 'file' + NumOfFiles;
		
		// Create some text
		var myText = document.createTextNode('File ' + NumOfFiles + ': ');
		// insert the text into the page
		myMarker.appendChild(myText);
		// insert the file field object into the page
		myMarker.appendChild(myObj);
		
		// create a blank space after the file field
		myText = document.createTextNode(' ');
		// insert the space into the page
		myMarker.appendChild(myText);
		
		// this event occurs for every 2nd insertion
		if((NumOfFiles/2-parseInt(NumOfFiles/2)) == 0)
		{
			// create a break tag
			mybreak = document.createElement('br');
			// insert the break tag
			myMarker.appendChild(mybreak);
		}	
	}
	</script>
	<form name='MyForm' enctype='multipart/form-data' method='post' action='sendmessage.php' onsubmit='Javascript:return(Validate(this))'><input name='Files' id='Files' type='hidden' value='3' /><table><tr><td>
	<p>Recipient: ";
	
	// This is a conditional section based on whether the user is replying or just creating a new message 
	if($_POST["id"] == "new")
	{
		// if this is a new message then display a blank recipient textfield
		$content .= "<input name='recipient' type='text' size='40'>"; 
	}else {
		// if this is a reply then get the name of the user that sent the message
		
		// connect to the message database
		require($virtualroot . "messaging/config.php");
		
		// generate the sql statement that will connect to the database and get the specific message and execute it
		$sql = "SELECT * FROM message WHERE MailID='" . $_POST["id"] . "'";
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();
	
		// collect all the results
		$result = $prepstatement->fetchAll();
		
		// loop through all the results
		foreach($result as $field)
		{
			// set the recipient textbox to have the sender of the previous message
			$content .= "<input name='recipient' type='text' size='40' value='" . $field["Sender"] . "'>";
		}
	} 
	 
	 // Setup the drop down list
	 $content .= " <select name='selectuser' OnChange='Javascript:SetSelected()'>
		<option selected='selected' value=''>Select a User to Send to --&gt;</option>
		<option value='###ALLUSERS###'>All Users</option>";
	
	// connect to the users database
	require($virtualroot . "login/config.php");
	
	// Generate and execute the sql statment that will select all the users from the database
	$sql = "SELECT * FROM users";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();
	
	// collect all of the results
	$result = $prepstatement->fetchAll();
	
	// loop through all of the results
	foreach($result as $field)
	{
		// add each user to the list
		$content .= "<option value='" . $field[name] . "'>" . $field[name] . "</option>";
	}
	
	$content .= "</select>
	  <br />
	Subject: ";
	 
	// This is a conditional section based on whether the user is replying or just creating a new message 
	if($_POST["id"] == "new")
	{
		// if this is a new message then leave the subject field blank
		$content .= "<input name='subject' type='text' size='47'>"; 
	}else {
		// if this is a reply then put the previous subject with an addition
		
		// connect to the messages database
		require($virtualroot . "messaging/config.php");
		
		// Generate and execute the sql statement that will get the previous message
		$sql = "SELECT * FROM message WHERE MailID='" . $_POST["id"] . "'";
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();
	
		// collect all the results
		$result = $prepstatement->fetchAll();
		
		// loop through all of the results
		foreach($result as $field)
		{
			// Set the subject
			$content .= "<input name='subject' type='text' size='47' value='RE: " . $field["Title"] . "'>";
		}
	}  
	
	// generate the rest of the form
	$content .=" <select name='importance'>
		<option selected='selected' value='0'>Normal Importance</option>
		<option value='1'>High Importance</option>
	</select>
	<br /><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"26500000\" />Attach files (Try to only upload files that are as small as possible to prevent errors (or failed message sending), particuarly when sending lots of files make sure they are very small.):<br /> File 1: <input name=\"file1\" type=\"file\" /> File 2: <input name=\"file2\" type=\"file\" /> <br />File 3: <input name=\"file3\" type=\"file\" /> <span id=\"marker\"></span>
<input name=\"Add\" type=\"button\" value=\"Add more files\" onclick=\"Javascript:AddField()\" />
	</p>
	<p>
	  <textarea name='message' cols='75' rows='10'></textarea>
	</p></td></tr><tr><td><input type='submit' style='float: right' value='Send'></td></tr></table></form>";
	
	// send the content
	echo $content;
}else {
	echo "You must be logged in to view this page!";
}

// add the footer
require_once($virtualroot."includes/foot.php");
?>