<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot . "shopping/config.php");

session_start();

require_once($virtualroot."includes/head.php");

if($_SESSION['access'] == 1)
{
	$outlink = $virtualroot . "shopping/add_product.php";
	
	echo '<form id="form1" name="form1" method="post" action="' . $outlink . '">
	  <h1>Create New Product</h1>
	  <p>Product name: 
		<input type="text" name="name" id="name" />
	  </p>
	  <p>Description:<br />
		<textarea name="description" id="description" cols="100" rows="10"></textarea>
		<br/><br/>
		Cost: <input name="Cost" type="text" value="1" /> Quantity: <input name="quantity" type="text" value="1" />
		<br />Link to Review of Product: <input name="review" type="text" />
	</p>
	  <p>
		<input type="submit" name="button" id="button" value="Create" />
	  </p>
	</form>';
}else {
	echo "<center>You do not have permission to access this page!</center>";
}
$db = NULL;

require_once($virtualroot."includes/foot.php");
?>