<?php
session_start();

$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");

if($_SESSION["status"] != 1 || $_SESSION["access"] == 1) 
{
	echo "<h1>Login Page</h1>";
	echo "<form method='post' action='login.php' class='lrfrm'>";
	echo "<p class='frm'>Enter user name: <input type='text' name='name_' title='Enter Username Here!' class='tb'><br><br>";
	echo "Enter password: &nbsp;&nbsp;<input type='password' name='pass' title='Enter Password Here!' class='tb'></p>";
	echo "<input type='submit' value='Login' class='sub'><br>";
	echo "</form>";
}else {
	echo "You are already logged in! Please Logout and and start a new session before trying to log into a different account!";
}

// because the float attribute is set break are required!!!

echo "<br><br><br><br><br><br><br>";

require_once($virtualroot."includes/foot.php");
?>
