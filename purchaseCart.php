<?php
include('dbConnect.php');
$id = $_POST["id"];
$price = $_POST["price"];
$save = $_POST["save"];
$query = "UPDATE orders SET status = 'Pending', placed_at=Now(), price=" . $price . ", money_saved=" . $save . " WHERE id = " . $id;
$statement = $connect->prepare($query);
$statement->execute();
?>
