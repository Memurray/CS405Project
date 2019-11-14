<?php
function orderTotal($order_id) {
    include('dbConnect.php');
    $query = 'SELECT name,price,promotion_rate,quantity FROM order_items, products WHERE product_name=name AND order_id = ' . $order_id;
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $sum = 0;
    $save = 0;
    foreach($result as $row) {
	$quantity = $row['quantity'];
     	$price = $row['price'];
    	$rate = $row['promotion_rate'];
	$sum = $sum + floor($price * 100 * $quantity)/100;
	$save = $save + floor($price * $rate * $quantity)/100; 
    }
    $out['price'] = $sum - $save;
    $out['save'] = $save;
    return $out;
}

