<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));

session_start();

require_once($virtualroot."includes/head.php");
require_once($virtualroot . "shopping/config.php");

if($_SESSION['access'] == 1)
{
	if($_POST['ccc'] == 1)
	{
		// making changes to database...
	
		// TODO!!!! things are not updating!!!!!
	
		$sqlupdate = "update products set ";
		$sqlupdate .= "Cost='" . $_POST['Cost'] . "' AND ";
		$sqlupdate .= "InStock='" . $_POST['quantity'] . "' AND ";
		$sqlupdate .= "Description='" . $_POST['Description'] . "' AND ";
		$sqlupdate .= "Name='" . $_POST['name'] . "' AND ";
		$sqlupdate .= "Review_ArticleID='" . $_POST['review'] . "' ";
		$sqlupdate .= "where ProductID='" . $_POST['ProductID'] . "' ";
		
		// run the sql command
		$prepstatement = $db->prepare($sqlupdate);
		$prepstatement->execute();
		//$count = $db->exec($sqlupdate);
		
		echo "<center>Edit successful! - <a href='view_productlist.php'>Click Here</a> to go to product list.</center>";
		
	}else {
		$productid = $_POST["productid"];

		$sql = "SELECT * FROM products WHERE ";
		$sql .= "ProductID='" . $productid . "' ";
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();
		
		$result = $prepstatement->fetchAll();
		
		if(count($result) > 0)
		{			
			foreach($result as $field)
			{
		
				// set up
				$outlink = $virtualroot . "shopping/edit_product.php";
				
				echo '<form id="form1" name="form1" method="post" action="' . $outlink . '">
				  <h1>Edit Product</h1>
				  <input type="hidden" name="ProductID" value="' . $field[ProductID] . '" />
				  <input type="hidden" name="ccc" value="1" />
				  <p>Product name: 
					<input type="text" name="name" id="name" value="' . $field[Name] . '" />
				  </p>
				  <p>Description:<br />
					<textarea name="Description" id="Description" cols="100" rows="10">' . $field[Description] . '</textarea>
					<br/><br/>
					Cost: <input name="Cost" type="text" value="' . $field[Cost] . '" /> Quantity: <input name="quantity" type="text" value="' . $field[InStock] . '" />
					<br />Link to Review of Product: <input name="review" type="text" value="' . $field[Review_ArticleID] . '" />
				</p>
				  <p>
					<input type="submit" name="button" id="button" value="Confirm Edit" />
				  </p>
				</form>';
			}
		}else {
			echo "Product Not Found!!!!";
		}
	}
}else {
	echo "<center>You do not have permission to access this page!</center>";
}
$db = NULL;

require_once($virtualroot."includes/foot.php");
?>