<?php

$dbfilename = "messages.db";
$dbfilepath = $_SERVER["DOCUMENT_ROOT"]."/messaging/";

$temp = $_ENV["TEMP"]."\\";
if(!is_writable($dbfilepath.$dbfilename)) { // not writable?
    //running from a non writable location so copy to temp directory
    if(file_exists($temp.$dbfilename)) {
       $dbfilepath = $temp; //file already exists use existing file
    }else { //file doese not exist
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
    $db = NULL;
	$db = new PDO('sqlite:'.$dbfilepath.$dbfilename); // create new database connection
}
catch (PDOException $error) {
   print "error: " . $error->getMessage() . "<br/>";
   die();
}
?>