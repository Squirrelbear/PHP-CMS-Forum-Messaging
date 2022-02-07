<?php
require_once("config.php");
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");

if($_SESSION["status"] != 1 || $_SESSION["access"] == 1)
{
	echo "<form method='post' action='add.php' class='lrfrm'>";
	echo "<p class='frm'>Enter user name: <input type='text' name='name_'><br><br>";
	echo "Enter password: <input type='password' name='pass'></p><br>";
	echo "<input type='submit' value='Create Account'><br>";
	echo "</form>";
}else {
	echo "You are logged in already! Don't try to register a new account while you are logged in! Logout and start a new session before attempting to do so.";
}

require_once($virtualroot."includes/foot.php");
?>