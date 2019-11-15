<?php
include('dbConnect.php');
include('orderTotal.php');
$id = $_POST["id"];
$orderTotal = orderTotal($id); 
$price = $orderTotal['price'];  //saves the array element with the tag 'price' to a variable
$saved = $orderTotal['save'];  //saves the array element with the tag 'save' to a variable
$query = "UPDATE orders SET status ='Pending', placed_at=now(), price=$price, money_saved=$saved  WHERE id = '";
$query = $query . $id . "';";

$statement = $connect->prepare($query);
$statement->execute();

?>
