<?php
session_start();
$_SESSION["d"] = $_POST["d"];
$_SESSION["t"] = $_POST["t"];
$_SESSION["dtp"] = "p";
header("Location: view.php");
?>
