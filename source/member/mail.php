<?php
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");
require_once("config_.php");

if($_SESSION["status"] == 1)
{
	// user is logged in so test which area to show
	// but first check if this is the first time entering
	if($_POST["state"] != "mail" || $_POST["state"] != "mailmessage" || $_POST["state"] != "new")
	{
		// do nothing
	}else {
		// set to show discussions
		$_POST["state"] = "mail";
	}

	if($_POST["state"] == "mail")
	{

	}else {
		if($_POST["state"] == "mailmessage")
		{

		}else {
			if($_POST["state"] == "new")
			{

			}else {

			}
		}
	}
}

$db = NULL;

require_once($virtualroot."includes/foot.php");

// function for array sort using date
function my_cmp($a, $b) {
	if(!is_array($a) || !is_array($b)) return 0;
	$date_a = $a[last_edited] ? strtotime($a[last_edited]) : 0;
	$date_b = $b[last_edited] ? strtotime($b[last_edited]) : 0;
	return $date_a - $date_b;
}
?>