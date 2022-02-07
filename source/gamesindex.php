<?php
session_start();

$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");

echo "<h1>Peter's games!</h1>";
echo "<p>There is only the one game which isn't finished yet avaliable at the moment, I will be converting Black Jack to php...</p>";
echo "<h2>BlackJack</h2><br><div class='button'><a href='Peter/BlackJack.html' target='_blank'>Click Here</a></div>";

require_once($virtualroot."includes/foot.php");
?>