<?php
include('dbConnect.php');
$name = $_POST["name"];
$promo = $_POST["promo"];

$query = "UPDATE products SET promotion_rate = " . $promo . " WHERE name = '";
$query = $query . $name . "';";

$statement = $connect->prepare($query);
$statement->execute();
?>
