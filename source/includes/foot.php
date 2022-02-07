<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require($virtualroot . "login/config.php");

// display the number of users currently logged in...
$sql = "SELECT * FROM users ";
$prepstatement = $db->prepare($sql);
$prepstatement->execute();

$result = $prepstatement->fetchAll();
$count = count($result);

// and the number of users currently registered...
$sql = "SELECT * FROM users WHERE status='1'";
$query = $db->prepare($sql);
$query->execute();

$result = $query->fetchAll();
$countx = count($result);

// and now print the message
echo "<br /><p align='center'><i>" . $countx . " / " . $count . " </i>users are currently logged on ...<br />";

// This is just here for reference. but I removed it because it was only showing local network ip, so it was useless... :(
//echo "Your current IP is: " . $_SERVER["REMOTE_ADDR"] . "<br>";

echo "<i>Copyright &copy; 2008 Peter Creations</i></p>";
echo '</body>';
echo '</html>';

?>