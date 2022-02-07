<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");
require_once($virtualroot . "member/config_files.php");

// Prepare preliminary variables:
$unique = false;

// array of the character presets
$chars = array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
$n = 0;
$count = 0;
$str = "";

do{
	// Prepare for randoms
	//srand(time());

	$i = 0;
	$str = "";
	$n++;

	for($i = 0; $i < 50; $i++)
	{
		// Random number between 0 and 35
		$tmp = mt_rand(0, 35);//(rand()%35);

		// Put coinciding string from array in:
		$str .= $chars[$tmp];
	}

	//echo "Attempt " . $n . ":" . $str . "<br>";
	
	// prepare the sql statement
	$sql = "SELECT * FROM files WHERE accessstring='" . $str . "'";

	// execute and count the results
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$count = count($result);

	if($count == 0)
	{
		$unique = true;
	}

}while(!$unique);

$filetype = substr(strrchr($_FILES['userfile']['name'], '.'), 1);
$fname = str_replace("." . $filetype, "", $_FILES['userfile']['name']);

// make sure the file name is unique
$unique = false;

$fileaddon = "";

do
{
	// prepare the sql statement
	$sql = "SELECT * FROM files WHERE filename='" . $fname . $fileaddon . "." . $filetype . "'";

	// execute and count the results
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$count = count($result);

	if($count == 0)
	{
		$unique = true;
	}else {
		$fileaddon = "_" . mt_rand(1, 999999999);
	}
}while(!$unique);

$currentdir = $_SERVER["DOCUMENT_ROOT"]."/";
$currentdir = str_replace ("/", "\\", $currentdir);
$currentdir = str_replace ("www\\localhost\\", "", $currentdir);

$uploadtempdir = $_ENV["TEMP"]."\\";
ini_set('upload_tmp_dir', $uploadtempdir);

$uploaddir_tmp = $_SERVER["DOCUMENT_ROOT"]."";
$uploaddir = str_replace("localhost", "", $uploaddir_tmp);

$uploadfile = $uploaddir . "uploadedfiles/" . $fname . $fileaddon . "." . $filetype;

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
{
    $sql = "INSERT INTO files (public, description, uploader, accessstring, accesslevel, filename, filetype, downloads) VALUES('" . $_REQUEST["porp"] . "','" . $_REQUEST["desc"] . "','" . $_SESSION[name] . "','" . $str . "','";
	
	if($_SESSION["access"] == 1)
	{
		$sql .= $_REQUEST["aorp"];
	}else {
		$sql .= "0";
	}
	
	$sql .= "','" . $_FILES['userfile']['name'] . "','" . $filetype . "','0')";
	//echo $sql;
	//$insert = $db->prepare($sql);
	$db->exec($sql);

	echo $_FILES['userfile']['name'] ." <br>";
    echo "was successfully uploaded. ";
	echo "as: " . $fname . $fileaddon . "." . $filetype;
    echo "<br><br>";
	
	echo "<form method='get' action='get_file.php'>Link to file: <input type='textbox' value='get_file.php?fileid=" . $str . "' /><input type='hidden' name='fileid' value='" . $str . "'><input type='submit' value='View' /></form>";

}
else
{
    echo "Upload Failed!!!";
    //print "<pre>";
    //print_r($_FILES);
    //print "</pre>";
}

$db = NULL;

require_once($virtualroot."includes/foot.php");
?>