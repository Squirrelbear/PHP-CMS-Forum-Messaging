<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot . "login/config.php");

session_start();

if($_SESSION["status"] == 1)
{
	// logged in
	$stat = "<div class='";
	
	if($_SESSION["access"] == 1)
	{
		$stat .= "statusadmin";
	}else {
		$stat .= "status";
	}
	$stat .= "'>You are logged in as: " . $_SESSION["name"] . "(<a href='" . $virtualroot . "/lout.php'>logout</a>)";
	
	//---------------------------------------------------------------------------------
	// Link to the inbox
	$stat .= "<br /><center><a href='" . $virtualroot . "messaging/inbox.php'>My Mail</a>: (";
	
	// Connect to the messages database
	require($virtualroot . "messaging/config.php");	
	
	// Setup the sql statment
	$sql = "SELECT COUNT(*) as 'count' FROM message WHERE Recipient='" . $_SESSION["name"] . "' AND Viewed='0'";
	
	// prepare and run the statment
	$prepstatment = $db->prepare($sql);
	$prepstatment->execute();
	
	// collate the result
	$result = $prepstatment->fetchAll();
	
	// extract the one result
	foreach($result as $tmp){
		$stat .= $tmp["count"] . ")</center>";
	}
	//----------------------------------------------------------------------------------
		
	$stat .= "<br /><center><a href='" . $virtualroot . "shopping/viewcart.php'>View Cart</a></center>";
	
	$stat .= "</div>";
	
	echo $stat;
}else {
	// not logged in so is classed as Guest
	echo "<div class='status'>You are a Guest at the moment(<a href='" . $virtualroot . "login/'>Login</a>)</div>";
}
?>
