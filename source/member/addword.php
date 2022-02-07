<?php
	session_start();
	require_once("config.php");
	
	if($_SESSION['access'] == 1)
	{
		// create new post
		$sqlinsert = "INSERT INTO badwords (word) VALUES('" . $_REQUEST['word'] . "')";
		
		$prepstatement = $db->prepare($sqlinsert);
		$prepstatement->execute();
		
		//$count = $db->exec($sqlinsert);
		
		header("Location: word_add.php");
	}else {
		echo "<center>You do not have permission to do this action!!!!</center>";
	}
?>