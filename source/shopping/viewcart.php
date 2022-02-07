<?php
// do a for each loop for all your orders in orders table and as going add up the total cost and show total at the end (remember to multiply by quantity) Will need to reference products table to show product info...
// also split up already purchased and current cart

session_start();
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");
require_once($virtualroot . "shopping/config.php");

$sql = "SELECT * FROM orders WHERE Name='" . $_SESSION['name'] . "' AND IsTmp='true'";
$prepstatement = $db->prepare($sql);
$prepstatement->execute();

$result = $prepstatement->fetchAll();

if(count($result) > 0)
{
	// sort the array using custom sort function by date
	//uasort($result, 'my_cmp');
	
	$content = '<h1>Your Shopping Cart</h1><form method="post" action="deletecart.php"><input type="submit" value="Empty Cart" /></form><table width="" border="1">
  		<tr>
    		<th width="100">Product Name</th>
    		<th width="400">Quantity Requested</th>
    		<th width="120">Price Per Item</th>
			<th width="120">Total Price</th>
    		<th width="100"><center>Options</center></th>';
  	
	/*if($_SESSION['access'] == 1)
	{
		$content .= '<th>Admin Commands</th>';
	}*/
	
	$content .=	'</tr>';
	
	$totalcost = 0;
	
	foreach($result as $field)
	{
		//print_r($field);
		$sql2 = "SELECT * FROM products WHERE ProductID='" . $field[ProductID] . "'";
		$prepstatement2 = $db->prepare($sql2);
		$prepstatement2->execute();

		$prod_result = $prepstatement2->fetchAll();
		
		foreach($prod_result as $prod_field)
		{
			//print_r($prod_field);
			
			$content .= "<tr><td>" . $prod_field['Name'] . "</td><td>" . $field['Quantity'] . "</td><td>(AUS)$" . $prod_field[Cost] . "</td><td>(AUS)$";
			
			$tmpquantity = $field['Quantity']; 
			$tmp_cost = $prod_field['Cost'];
			$tmpcost = $tmpquantity * $tmp_cost;
			//echo $tmpquantity;
			//echo $tmpcost;
			//echo $totalcost;
			$totalcost = $totalcost + $tmpquantity * $tmpcost;
			//echo "<br> " . $totalcost;
			
			
			$content .= $tmpcost . "</td><td><center><form method='get' action='view_product.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "' /><input type='submit' value='View Product' /></form><form method='post' action='deletecart_item.php'><input type='hidden' name='id' value='" . $field[OrderID] . "' /><input type='submit' value='Remove From Cart' /></form></center></td>";
			
			/*if($_SESSION['access'] == 1)
			{
				$content .= "<td><table border=0><tr><td><form method='post' action='delete_product.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'>";
				$content .= "<input type='submit' name='deletet' value='Remove Item'></form></td>";
				$content .= "<td><form method='post' action='edit_product.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'><input type='submit' name='deletet' value='Edit Product'></form></td>";
				$content .= "<td><form method='post' action='edit_stock.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'><input type='textbox' name='stock' value='" . $field[InStock] . "'> <input type='submit' name='deletet' value='Edit Stock'></form></td>";
				$content .= "<td><form method='post' action='edit_cost.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'><input type='textbox' name='cost' value='" . $field[Cost] . "'><input type='submit' name='deletet' value='Edit Price'></form></td>";
				$content .= "</tr></table></td>";
			}*/
			
		}
			
	}
	$content .= "</tr><tr><td colspan=3></td><td>(AUS)$" . $totalcost . "</td><td><form action='confirmpurchase.php'><input type='submit' value='Purchase Items' /></form></td></tr>";
	
	$content .= "</table>";
	
	echo $content;
			
}else {
	echo "<p align='center'>Your Cart is Empty!</p>";
}

// NOW Get all of the already bought items
	
	$sql = "SELECT * FROM orders WHERE Name='" . $_SESSION['name'] . "' AND IsTmp='false'";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();
	
	$result = $prepstatement->fetchAll();
	
	if(count($result) > 0)
	{
		// sort the array using custom sort function by date
		//uasort($result, 'my_cmp');
		
		$content = '<h1>Already Purchased Items</h1><table width="" border="1">
			<tr>
				<th width="100">Product Name</th>
				<th width="400">Quantity Requested</th>
				<th width="120">Price Per Item</th>
				<th width="120">Total Price</th>';
		
		/*if($_SESSION['access'] == 1)
		{
			$content .= '<th>Admin Commands</th>';
		}*/
		
		$content .=	'</tr>';
		
		$totalcost = 0;
		
		foreach($result as $field)
		{
			$sql2 = "SELECT * FROM products WHERE ProductID='" . $field[ProductID] . "'";
			$prepstatement2 = $db->prepare($sql2);
			$prepstatement2->execute();
	
			$prod_result = $prepstatement2->fetchAll();
			
			foreach($prod_result as $prod_field)
			{
				$content .= "<tr><td>" . $prod_field[Name] . "</td><td>" . $field[Quantity] . "</td><td>(AUS)$" . $prod_field[Cost] . "</td><td>(AUS)$";
				
				$tmpquantity = $field['Quantity']; 
				$tmp_cost = $prod_field['Cost'];
				$tmpcost = $tmpquantity * $tmp_cost;
				//echo $tmpquantity;
				//echo $tmpcost;
				//echo $totalcost;
				$totalcost = $totalcost + $tmpquantity * $tmpcost;
				//echo "<br> " . $totalcost;
				
				$content .= $tmpcost . "</td>";
				
				/*if($_SESSION['access'] == 1)
				{
					$content .= "<td><table border=0><tr><td><form method='post' action='delete_product.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'>";
					$content .= "<input type='submit' name='deletet' value='Remove Item'></form></td>";
					$content .= "<td><form method='post' action='edit_product.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'><input type='submit' name='deletet' value='Edit Product'></form></td>";
					$content .= "<td><form method='post' action='edit_stock.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'><input type='textbox' name='stock' value='" . $field[InStock] . "'> <input type='submit' name='deletet' value='Edit Stock'></form></td>";
					$content .= "<td><form method='post' action='edit_cost.php'><input type='hidden' name='productid' value='" . $field[ProductID] . "'><input type='textbox' name='cost' value='" . $field[Cost] . "'><input type='submit' name='deletet' value='Edit Price'></form></td>";
					$content .= "</tr></table></td>";
				}*/
				
					
			}	
		}
		$content .= "</tr><tr><td colspan=3></td><td>(AUS)$" . $totalcost . "</td><td></td></tr>";		
		
		$content .= "</table>";
		
		echo $content;
	}else {
		echo "You have not bought any items yet...";
	}

$db = NULL;

require_once($virtualroot."includes/foot.php");

?>