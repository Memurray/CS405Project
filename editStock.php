<?php
include('dbConnect.php');
$name = $_POST["name"];
$stock = $_POST["stock"];

$query = "UPDATE products SET stock_remaining = " . $stock . " WHERE name = '";
$query = $query . $name . "';";

$statement = $connect->prepare($query);
$statement->execute();
?>
