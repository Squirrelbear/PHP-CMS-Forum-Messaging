<?php
function checkstr($strtemp) {
    $strtemp = str_replace ("\'", "''", $strtemp); //escape the single quote
    $strtemp = str_replace ("'", "''", $strtemp); //escape the single quote
	return $strtemp;
}

$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");

$name = checkstr($_POST["name_"]);
$pass = checkstr($_POST["pass"]);

require("config.php");

if($name != "" && $pass != "")
{
	// do: is name already taken
	$sql = "SELECT * FROM users ";
    $prepstatement = $db->prepare($sql);
    $prepstatement->execute();
	
	$result = $prepstatement->fetchAll();
	$success = bool;
	
    foreach($result as $field) {
    	if($name == $field[name])
		{
			$success = false;
		}
    }
	
	unset($sql, $result);
	
	if($success == false)
	{
	}else {
		$success = true;
	}

	if($success == true)
	{
		$sqlinsert = "insert into users ";
		$sqlinsert .= "(";
		$sqlinsert .= "access, ";
		$sqlinsert .= "name, ";
		$sqlinsert .= "password, ";
		$sqlinsert .= "status  ";
		$sqlinsert .= ")";
		$sqlinsert .= "values ";
		$sqlinsert .= "(";
		$sqlinsert .= "0, ";
		$sqlinsert .= "'$name', ";
		$sqlinsert .= "'" . md5($pass) . "', ";
		$sqlinsert .= "0 ";
		$sqlinsert .= ")";
		$count = $db->exec($sqlinsert); //returns affected rows
	
		echo "<h3>User account creation success!</h3>";
		echo "<p>The User account: <b>" . $name . "</b><br>";
		echo "You can now login and use the member facilities...</p>";
	}else {
		echo "<h3>User account creation failure!</h3>";
		echo "<p>The User account name <b>" . $name . "</b> has already been taken. Please try a different name!<br>";
		echo "<a href='register.php'>Click Here</a> to try creating an account with a different name.</p>";
	}
}else {
	echo "<h3>User account creation failure!</h3>";
	echo "You entered a blank username or password, passwords and usernames can't be blank!<br />";
	echo "<a href='register.php'>Click Here</a> to try creating an account with a different name.";
}
require_once($virtualroot."includes/foot.php");
?>