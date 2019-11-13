<?php
include('dbConnect.php');
$id = $_POST["id"];

$query = "UPDATE orders SET status ='Cancelled' WHERE id = '";
$query = $query . $id . "';";

$statement = $connect->prepare($query);
$statement->execute();

?>
