<?php
include('dbConnect.php');
$pName = $_POST["name"];
$quantity = $_POST["quantity"];
$uName = $_COOKIE["CS405_Username"];

// Find cart of user
$query = "SELECT * FROM orders WHERE status = 'cart' AND user_name ='";
$query .= $uName . "';";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

// If there was only 1 cart found, run insert op
if($total_row == 1){
	insertCart($result,$pName,$quantity);
}

// If no open cart, make a cart, run insert op
else if($total_row == 0){
	$query = "INSERT INTO orders(user_name, status, price) values ('" . $uName;
	$query .=  "', 'Cart',0);";
        $statement = $connect->prepare($query);
        $statement->execute();
	$query = "SELECT id FROM orders WHERE user_name = '" .$uName;
	$query = $query . "'";
	$query = $query . " AND status = 'Cart'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	insertCart($result,$pName,$quantity);
}

// If there are more than 1 cart (this should be impossible, but....)
else
	echo "Well, there are multiple carts, everything is on fire apparently.";


function insertCart($result,$pName,$quantity){
        global $connect;
	if($quantity == 0){
	    echo "You can't order 0 items.";
	    return;
	}
	$oID =  $result[0]['id'];

	// Look to see if they already have this item in the cart
       	$query = "SELECT * FROM order_items WHERE order_id = " . $oID . " AND product_name = '" . $pName;
	$query .=  "';";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$total_row = $statement->rowCount();

 	// If they don't, just insert the item and quantity into cart
	if($total_row == 0){
            $query = "INSERT INTO order_items VALUES(" . $oID . ",'" . $pName;
	    $query .=  "', " . $quantity  . ");";
            $statement = $connect->prepare($query);
            if($statement->execute())
        	echo "Successfully added the item to the cart.";
            else
                echo "Item was not added to the cart.";
	}

	// Otherwise, update the quantity to include new items added
	else{
 	    $query = "UPDATE order_items SET quantity=quantity+" . $quantity . " WHERE  order_id = " . $oID . " AND product_name = '" . $pName;
	    $query .= "';";
	    $statement = $connect->prepare($query);
            if($statement->execute())
                echo "Successfully updated the quantity of the product in cart.";
            else
                echo "Item quantity was not successfully updated.";
	}
}
?>
