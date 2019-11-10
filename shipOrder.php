<?php
include('dbConnect.php');
$id = $_POST["id"];

$query = "UPDATE orders SET status ='Shipped' WHERE id = '";
$query = $query . $id . "';";

$statement = $connect->prepare($query);
$statement->execute();

$query = "SELECT * FROM order_items WHERE order_id = '" . $id;
$query = $query . "';";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

foreach($result as $row){
    $name = $row['product_name'];
    $quantity = $row['quantity'];
    $query = "UPDATE products SET stock_remaining = stock_remaining - $quantity WHERE name = '" . $name;
    $query = $query . "';";
    $statement = $connect->prepare($query);
    $statement->execute();
}
?>
