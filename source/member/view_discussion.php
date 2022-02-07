<?php
session_start();
$_SESSION["d"] = $_GET["d"];
$_SESSION["dtp"] = "t";
header("Location: view.php");
?>
