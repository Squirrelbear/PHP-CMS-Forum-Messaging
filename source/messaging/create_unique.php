<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require($virtualroot . "messaging/config.php");

// Prepare preliminary variables:
$unique = false;

// array of the character presets
$chars = array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
$n = 0;
$count = 0;
$str = "";

// generate a 50 character random string
for($i = 0; $i < 50; $i++)
{
	// Random number between 0 and 35
	$tmp = mt_rand(0, 35);

	// Put coinciding string from array in:
	$str .= $chars[$tmp];
}
?>