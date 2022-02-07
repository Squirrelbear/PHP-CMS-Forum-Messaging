<?php
require_once("config.php");
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");
if ($_SESSION["access"]==1) { 
	// success
	echo "<h1>Users:</h1>";
	
	require($virtualroot . "login/config.php");
	
	$sql = "SELECT * FROM users ";
    $prepstatement = $db->prepare($sql);
    $prepstatement->execute();
	
	$result = $prepstatement->fetchAll();
	
	$tbl = "<form method='post' action='forceloutall.php'>";
	$tbl .= "<input type='submit' value='Logout All Users'></form>";
	
	$tbl .= "<table border=1><tr><th>Username</th><th>Access Level</h><th>Loggedin</th><th>Commands</th></tr>";
	
    foreach($result as $field) {
		$tbl .= "<tr><td>" . $field[name] . "</td><td>";		
		
		if($field[access] == 0)
		{
			$tbl .= "Standard User";
		}else if($field[access] == 1){
			$tbl .= "Administrator";
		}else {
			$tbl .= "Invalid User";
		}
		
		$tbl .= "</td><td>";
		
		if($field[status] == 0)
		{
			$tbl .= "False";
		}else {
			$tbl .= "True";
		}
		
		$tbl .= "</td><td><table border=0><tr><td>";
		
		$tbl .= "<form method='post' action='remove.php'>";
		$tbl .= "<input type='hidden' name='user' value='" . $field[name] . "'>";
		$tbl .= "<input type='submit' value='Delete User'></form></td>";
		
		if($field[status] != 0)
		{
			$tbl .= "<td><form method='post' action='forcelout.php'>";
			$tbl .= "<input type='hidden' name='user' value='" . $field[name] . "'>";
			$tbl .= "<input type='submit' value='Force Logout'></form></td>";
		}
		
		if($field[access] == 0)
		{
			$tbl .= "<td><form method='post' action='upgradeaccess.php'>";
			$tbl .= "<input type='hidden' name='user' value='" . $field[name] . "'>";
			$tbl .= "<input type='submit' value='Upgrade to Administrator'></form></td>";
		}else if($field[access] == 1){
			$tbl .= "<td><form method='post' action='resetaccess.php'>";
			$tbl .= "<input type='hidden' name='user' value='" . $field[name] . "'>";
			$tbl .= "<input type='submit' value='Reset to Standard User'></form></td>";
		}else {
			$tbl .= "<td><form method='post' action='resetaccess.php'>";
			$tbl .= "<input type='hidden' name='user' value='" . $field[name] . "'>";
			$tbl .= "<input type='submit' value='Fix Access'></form></td>";
		}
		
		$tbl .= "</tr></table></td></tr>";
    }
	
	$tbl .= "</table>";
	
	echo $tbl;
}else {
	echo "<h2>Error:</h2>";
	echo "<p>You do not have adminastrator privlidges with the account you are currently using. To access this page you need to be already logged into adminastrator.</p>";
	echo "<p><a href='index.php'>Click Here</a> to go to the login page so you can log into an adminastrator account if you have access to one before returning to this page.</p>";
}
require_once($virtualroot."includes/foot.php");
?>

