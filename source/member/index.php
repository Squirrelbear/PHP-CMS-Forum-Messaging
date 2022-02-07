<?php
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");

echo "<h1>Welcome valued member!</h1>";
echo "<p>I hope to have a forum avaliable here to people to have discussions on. But seeing as I need to build it from scratch, it may take me a number of days. But anyway I hope you like my site and any thoughts or ideas about what could be done to make this site better will all be considered. These ideas could make my site a lot better.</p>";

echo "<p><a href='view.php'>Click Here</a>: this temporary button will let you enter the forums.</p>";

echo "<p>There is still some serious linking to do, so do not expect much, but I will happily take suggestions.</p>";

echo "<p>The forum will contain a number of primary discussions: <ul><li>Class work IT related (For requesting help and discussing stuff from class)</li><li>General other school work</li><li>General (NO FOOTBALL though)</li><li>Football (to keep Catherine out of the General forum)</li></ul></p>";

echo "I may decide to lock the Football forum from time to time just for the hell of it... :)";

$db = NULL;

require_once($virtualroot."includes/foot.php");
?>
