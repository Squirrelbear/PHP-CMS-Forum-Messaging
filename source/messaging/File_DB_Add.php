<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require($virtualroot . "messaging/config.php");

// execute an sql command
$insert = $db->prepare($sql);
$insert->execute();
?>