<?php
include('dbConnect.php');
$name = $_POST["name"];
$stock = $_POST["stock"];
$price = $_POST["price"];
$cat = $_POST["cat"];

$query = "INSERT INTO products values('" . $name;
$query = $query  . "', " . $price;
$query = $query  . "," . $stock;
$query = $query  . ",0,'" . $cat;
$query = $query  . "');";

$statement = $connect->prepare($query);
$statement->execute();
?>


