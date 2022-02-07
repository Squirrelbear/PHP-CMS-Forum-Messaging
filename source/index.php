<?php
session_start();

$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");

echo "<h1>Welcome to Peter Mitchell's site!!!</h1>";
echo "<p>My Server now has a large set of features including; forums, an article area, file sharing, Item Purchasing, and a Personal Messaging system.</p>";

require_once($virtualroot."includes/foot.php");
?>

