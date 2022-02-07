<?php
session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");
require_once($virtualroot . "shopping/config.php");

$sql = "SELECT * FROM products ";
$prepstatement = $db->prepare($sql);
$prepstatement->execute();

$result = $prepstatement->fetchAll();

if(count($result) > 0)
{
	// sort the array using custom sort function by date
	//uasort($result, 'my_cmp');
	
	$content = '<h1>Products</h1><table width="" border="1">
  		<tr>
    		<th width="100">Product Name</th>
    		<th width="400">Number in Stock</th>
    		<th width="120">Price</th>
    		<th width="100"><center>Add to Cart</center></th>';
  	
	if($_SESSION['access'] == 1)
	{
		$content .= '<th>Admin Commands</th>';
	}
	
	$content .=	'</tr>';
	
	foreach($result as $field)
	{
		$content .= "<tr><td>" . $field[Name] . "</td><td>" . $field[InStock] . "</td><td>(AUS)$" . $field[Cost] . "</td><td><center><form method='get' action='view_product.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "' /><input type='submit' value='View Product' /></form><form method='post' action='addtocart.php'>Quantity: <input name='quantity' type='text' value='1' size=2 /><input type='hidden' name='productid' value='" . $field[ProductID] . "' /><input type='submit' value='Add to Cart' /></form></center></td>";
		
		if($_SESSION['access'] == 1)
		{
			$content .= "<td><table border=0><tr><td><form method='post' action='delete_product.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'>";
			$content .= "<input type='submit' name='deletet' value='Remove Item'></form></td>";
			$content .= "<td><form method='post' action='edit_product.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'><input type='submit' name='deletet' value='Edit Product'></form></td>";
			$content .= "<td><form method='post' action='edit_stock.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'><input type='textbox' name='stock' value='" . $field[InStock] . "'> <input type='submit' name='deletet' value='Edit Stock'></form></td>";
			$content .= "<td><form method='post' action='edit_cost.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'><input type='textbox' name='cost' value='" . $field[Cost] . "'><input type='submit' name='deletet' value='Edit Price'></form></td>";
			$content .= "</tr></table></td>";
		}
		
		$content .= "</tr>";		
	}
	
	$content .= "</table>";
	
	echo "<form action='create_product.php'><input type='submit' value='Create New Product' /></form>";
	
	echo $content;
			
}else {
	echo "<p align='center'>There are no products!</p>";
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