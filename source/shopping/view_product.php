<?php
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");
require_once($virtualroot . "shopping/config.php");

$productid = $_GET["productid"];

$sql = "SELECT * FROM products WHERE ";
$sql .= "ProductID='" . $productid . "' ";
$prepstatement = $db->prepare($sql);
$prepstatement->execute();

$result = $prepstatement->fetchAll();

if(count($result) > 0)
{
	// sort the array using custom sort function by date
	//uasort($result, 'my_cmp');
	
	foreach($result as $field)
	{
		$content = "<h1 style='line-height: 0px;'>" . $field[Name] . "</h1>";
				
		$content .= "Description: " . $field[Description] . "<br /><br />";
		
		// TODO - add code to test if this should be shown
		$content .= "Review of Product: <a href='" . $field[Review] . "'>Click Here</a><br />";
		
		$content .= "Cost per item: $" . $field[Cost] . "<br />Currently In Stock: " . $field[InStock] . "<br /><br />";
		
		$content .= "<form method='post' action='addtocart.php'>Quantity: <input name='quantity' type='text' value='1' size=2 /><input type='hidden' name='productid' value='" . $field[ProductID] . "' /><input type='submit' value='Add to Cart' /></form></center></td>";
		
		if($_SESSION['access'] == 1)
		{
			$content .= "<form method='post' action='delete_product.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'>";
			$content .= "<input type='submit' name='deletet' value='Remove Item'></form>";
			$content .= "<form method='post' action='edit_product.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'><input type='submit' name='deletet' value='Edit Product'></form>";
			$content .= "<form method='post' action='edit_stock.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'><input type='textbox' name='stock' value='" . $field[InStock] . "'> <input type='submit' name='deletet' value='Edit Stock'></form>";
			$content .= "<form method='post' action='edit_cost.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'><input type='textbox' name='cost' value='" . $field[Cost] . "'><input type='submit' name='deletet' value='Edit Price'></form>";
		}
	}
	
	echo "<a href='view_productlist.php'>Click Here</a> to go to product list.";
	
	echo $content;
			
}else {
	echo "<p align='center'>There is no such product!</p>";
}

$db = NULL;

require_once($virtualroot."includes/foot.php");

// function for array sort using date
function my_cmp($a, $b) {
	if(!is_array($a) || !is_array($b)) return 0;
	$date_a = $a[last_edited] ? strtotime($a[last_edited]) : 0;
	$date_b = $b[last_edited] ? strtotime($b[last_edited]) : 0;
	return $date_a - $date_b;
}
?>