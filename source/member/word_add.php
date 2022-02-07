<?php
	session_start();
	$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
	require_once($virtualroot."includes/head.php");
	require_once("config.php");
	
	if($_SESSION['access'] == 1)
	{
		echo "<form action='addword.php' method='get'>Enter a word to block: <input type='text' name='word' /> <input type='submit' value='Add Word' /></form><br />";
		
		$sql = "SELECT * FROM badwords ";
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();

		$result = $prepstatement->fetchAll();

		if(count($result) > 0)
		{
			echo "<table border=1>";
			echo "<tr><th>Bad Words:</th></tr>";
			foreach($result as $field)
			{
				echo "<tr><td>" . $field[word] . "</td></tr>";	
			}
			echo "</table>";
		}
	}else {
		echo "<center>You can't do anything here unless you are an admin...</center>";
	}
	
	require_once($virtualroot."includes/foot.php");
?>