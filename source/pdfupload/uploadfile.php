<?php
/*
Application: PDF Upload Listing Utility
Author: Peter Mitchell
Version: 1.0
Copyright Peter Mitchell 2012

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
session_start();

require_once('consql.php');

mysql_select_db($mysql_database, $con);

// Get the number of rows, this number becomes the new file id
$result = mysql_query("SELECT * FROM filelibrary");
$num_rows = mysql_num_rows($result);

// get the file extension
$filetype = substr(strrchr($_FILES['fileupload']['name'], '.'), 1);

// get the file name
$fname = str_replace("." . $filetype, "", $_FILES['fileupload']['name']);

// set the temporary folder
$uploadtempdir = "home\\a5716545\\public_html\\pdfupload\\temp\\";//$_ENV["TEMP"]."\\";
ini_set('upload_tmp_dir', $uploadtempdir);

//path to directory to scan
$directory = "files/";

$fname = "blah";
$fileaddon = "";
$filetype = "php";
$count = 0;
$unique = false;

// ensure file name is unique and modify if required
do
{
	if(!file_exists($directory . $fname . $fileaddon . "." . $filetype))
	{
		// file with that name does not exist so success in unique name
		$unique = true;
	}
	else
	{
		// use a number extension to make file unique
		$fileaddon = $count;
		$count++;
	}
} while(!$unique);

// new file path
$fullfilename = $fname . $fileaddon . "." . $filetype;
$filepath = $directory . $fullfilename;
$filetext = $_GET['filetext'];

// attempt to upload the file
if(move_uploaded_file($_FILES['fileupload']['tmp_name'], $uploadfile))
{
	// success

	// insert item into database
	$sql = "INSERT INTO Attachments (fileid, filename, filetext) VALUES("
				. $num_rows . ", '" . $fullfilename . "', '" . $filetext . "')";

	$result = mysql_query($insertquery);

	if(!$result)
	{
		echo "ERROR";
	}
	else
	{
		echo "SUCCESS";
	}
} else {
	// failed to upload
	echo "Upload of file " . $_FILES['fileupload']["name"] . " Failed!!!<br>";
}
?>