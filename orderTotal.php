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
        $saveItem = floor($price * $rate)/100;
        $sum = $sum + $price * $quantity;
    	$save = $save + $saveItem * $quantity;
    }
    $out['price'] = $sum - $save;
    $out['save'] = $save;
    return $out;
}

