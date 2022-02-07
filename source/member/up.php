<?php
session_start();
if($_POST["w"] == "t")
{
	$_SESSION["dtp"] = "d";
}else {
	$_SESSION["dtp"] = "t";
	$_SESSION["d"] = $_POST["d"];
}

// debugging code ...
//echo $_SESSION["dtp"];

header("Location: view.php");
?>
