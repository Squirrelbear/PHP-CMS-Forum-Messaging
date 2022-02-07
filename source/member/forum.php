<?php
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");

echo "<h1>Welcome valued member!</h1>";
echo "<p>I hope to have a forum avaliable here to people to have discussions on. But seeing as I need to build it from scratch, it may take me a number of days. But anyway I hope you like my site and any thoughts or ideas about what could be done to make this site better will all be considered. These ideas could make my site a lot better.</p>";

$db = NULL;

require_once($virtualroot."includes/foot.php");
?>
