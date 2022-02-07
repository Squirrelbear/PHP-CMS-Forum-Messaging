<?php
function checkstr($strtemp) {
    $strtemp = str_replace ("\'", "''", $strtemp); //escape the single quote
    $strtemp = str_replace ("'", "''", $strtemp); //escape the single quote
	return $strtemp;
}

session_start();
require_once("config.php");
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
$namez = checkstr($_POST["name_"]);
$passwordz = checkstr($_POST["pass"]);

$sql = "SELECT * FROM users ";
$prepstatement = $db->prepare($sql);
$prepstatement->execute();

$result = $prepstatement->fetchAll();
$countx = count($result);

$success = bool;
$success = false;

$message = "";

if ($countx > 0) {
	foreach($result as $field)
	{
		if($field[name] == $namez && $field[password] == md5($passwordz))
		{
			// access success
			$success = true;
	
			// create sessions
			$_SESSION["name"] = $namez;
			$_SESSION["access"] = $field[access];
			//$_SESSION["password"] = $passwordz; <-- removed because it could cause a security leak
			$_SESSION["status"] = 1;
	
			// set status to online
			$sqlupdate  = "update users set ";
			$sqlupdate .= "status='1' ";
			$sqlupdate .= "where name='" . $_SESSION["name"] . "'";
			$count = $db->exec($sqlupdate);
	
			// print success to page
			$message = "<h3>User Login Success!</h3><br>";
			$message .= "<p>User <b>" . $namez . "</b> has been logged on...</p>";
			
			// mail("Peter.Mitchell@swiftdsl.com.au", "Subject test", "The message");
		}
	}
}else {
	$message = "<h2>Error</h2><br>";
	$message .= "The user database is empty. <br>Please create a user first.<br>";
	$message .= "<a href='register.php'>Click Here</a> to create a new user.<br>";
}

require_once($virtualroot."includes/head.php");

echo $message;

if($success == true) {
	// do nothing because use got in
}else {
	// invalid login details
	
	echo "<h3>User Login Failure!</h3>";
	echo "<p>The user name or password you supplied was not valid!</p>";
	echo "<p><a href='index.php'>Click Here</a> to re-attempt logging in...</p>";
}

require_once($virtualroot."includes/foot.php");
?>