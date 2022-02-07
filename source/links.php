<?php
session_start();

$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");

echo "<h1>Peter's Links!</h1>";
echo "<p>I have not gotten around to adding links yet because I have been working on my forums tool...</p>";

require_once($virtualroot."includes/foot.php");
?>