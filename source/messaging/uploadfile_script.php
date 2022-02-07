<?php
// this is the upload file function...
// it is designed to streamline the process of uploading files and adding the information to the database
// particuarly since all of this code would otherwise just be duplicated numerous times
function uploadfile($fileid, $mailid, $recipient)
{
	session_start();

	// enable and disable debug output with this variable
	$debug = false;
	
	// setup a string that contains a path to the sites root folder
	$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
	
	require($virtualroot . "messaging/config.php");
	
	// Prepare preliminary variables:
	$unique = false;
	
	// array of the character presets
	$chars = array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$n = 0;
	$count = 0;
	$str = "";
	
	do{
		$i = 0;
		$str = "";
	
		// generate a 50 character random string
		for($i = 0; $i < 50; $i++)
		{
			// Random number between 0 and 35
			$tmp = mt_rand(0, 35);
	
			// Put coinciding string from array in:
			$str .= $chars[$tmp];
		}
		
		// prepare the sql statement
		$sql = "SELECT * FROM Attachments WHERE accessstring='" . $str . "'";
	
		// execute and count the results
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		$count = count($result);
		
		if($count == 0)
		{
			$unique = true;
		}
	
		// exit the loop if this is a unique string
	}while(!$unique);
	
	// get the file extension
	$filetype = substr(strrchr($_FILES['file' . $fileid]['name'], '.'), 1);
	
	// get the file name
	$fname = str_replace("." . $filetype, "", $_FILES['file' . $fileid]['name']);
	
	// generate the current directory string
	$currentdir = $_SERVER["DOCUMENT_ROOT"]."/";
	$currentdir = str_replace ("/", "\\", $currentdir);
	$currentdir = str_replace ("www\\localhost\\", "", $currentdir);
	
	// set the temporary folder
	$uploadtempdir = $_ENV["TEMP"]."\\";
	ini_set('upload_tmp_dir', $uploadtempdir);
	
	// set where the file has to be uploaded to
	$uploaddir_tmp = $_SERVER["DOCUMENT_ROOT"]."";
	$uploaddir = str_replace("localhost", "", $uploaddir_tmp);
	
	// make sure the file name is unique
	$unique = false;
	
	$fileaddon = "";
	
	do
	{		
		// prepare the sql statement
		$sql = "SELECT * FROM Attachments WHERE filename='" . $fname . $fileaddon . "." . $filetype . "'";
	
		// execute and count the results
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute(); 
		$result = $prepstatement->fetchAll();
		$count = count($result);
	
		// test if the filename is unique alternate method...
		//if(!file_exists($uploaddir . "attachments/" . $fname . $fileaddon . "." . $filetype))
	
		if($count == 0)
		{
			$unique = true;
		}else {
			// generate a random number that can be added to the filename
			$fileaddon = "_" . mt_rand(1, 999999999);
		}
		
		// exit loop if the filename is unique
	}while(!$unique);
	
	// generate the file path to upload to
	$uploadfile = $uploaddir . "attachments/" . $fname . $fileaddon . "." . $filetype;
	
	if($debug){echo "Filename: " . $_FILES['file' . $fileid]['name'] . "<br>Tmp Location: " . $_FILES['file' . $fileid]['tmp_name'] . "<br> Upload Directory: " . $uploadfile . "<br>";}
	
	// generate the sql statment to enter the attachment into the database
	$sql = "INSERT INTO Attachments (user, accessstring, filename, filetype, MailID) VALUES('" . $recipient . "','" . $str . "','" . $fname . $fileaddon . "." . $filetype . "','" . $filetype . "','" . $mailid . "')";
	
	if($debug){echo "<br>" . $sql;}

	if($_SESSION['file_originals'][$i-1] == NULL)
	{
		// attempt to upload the file
		if(move_uploaded_file($_FILES['file' . $fileid]['tmp_name'], $uploadfile))
		{
			// success
			
			$_SESSION['file_originals'][$i-1] = $fname . $fileaddon . "." . $filetype;
			
			// insert item into database
			require($virtualroot . "messaging/File_DB_Add.php");
			
			// return success
			return 1;
		} else {
			// failed to upload
			echo "Upload of file " . $_FILES['file' . $fileid]["name"] . " Failed!!!<br>";
		}
		// return failure
		return 0;
	}else {
		// copy the existing file
		$original_file = fopen($uploaddir . "attachments/" . $_SESSION['file_originals'][$i-1], 'r') or die("Can't open file");
		$data = fread($original_file, filesize($uploaddir . "attachments/" . $_SESSION['file_originals'][$i-1]));
		fclose($original_file);
		
		// create the new file and paste the contents
		$file_new = fopen($uploadfile, 'w') or die("Can't create new file");
		fwrite($file_new, $data);
		fclose($file_new);

		require($virtualroot . "messaging/File_DB_Add.php");

		return 1;
	}
}
?>