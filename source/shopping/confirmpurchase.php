<?php
	session_start();
	$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
	require_once($virtualroot."includes/head.php");
	
	echo "Are you sure that you want to commit to buying all the items in your shopping cart? <form action='purchased.php'><input type='submit' value='Commit to buy' /></form><form action='viewcart.php'><input type='submit' value='Changed my mind' /></form>";
	
	require_once($virtualroot."includes/foot.php");
?>