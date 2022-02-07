<?php

$dbfilename = "forum.db";
$dbfilepath = $_SERVER["DOCUMENT_ROOT"]."/member/";

$temp = $_ENV["TEMP"]."\\";
if (is_writable($dbfilepath.$dbfilename)) { //is writable
    //use database in current location
}
else { //not writable
    //running from a non writable location so copy to temp directory
    if (file_exists($temp.$dbfilename)) {
       $dbfilepath = $temp; //file already exists use existing file
    }
    else { //file doese not exist
        //copy the file to the temp dir
        if (copy($dbfilepath.$dbfilename, $temp.$dbfilename)) {
           //echo "copy succeeded.\n";
           $dbfilepath = $temp;
        }
        else {
            echo "Copy Failed ";
            exit;
        }
    }
}

//database connection
try {
    //$db = new PDO('sqlite2:example.db'); //sqlite 2
    //$db = new PDO('sqlite::memory:'); //sqlite 3
    $db = new PDO('sqlite:'.$dbfilepath.$dbfilename); //sqlite 3
}
catch (PDOException $error) {
   print "error: " . $error->getMessage() . "<br/>";
   die();
}

/*
function checkstr($strtemp) {
    $strtemp = str_replace ("\'", "''", $strtemp); //escape the single quote
    $strtemp = str_replace ("'", "''", $strtemp); //escape the single quote
	return $strtemp;
}*/
?>
