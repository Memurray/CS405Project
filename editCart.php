<?php
include('dbConnect.php');
$name = $_POST["name"];
$quantity = $_POST["quantity"];
$id = $_POST["id"];
if($quantity == 0){
    $query = "DELETE FROM order_items WHERE product_name = '";
    $query = $query . $name . "' AND order_id = " . $id;
}

else{
    $query = "UPDATE order_items SET quantity = " . $quantity . " WHERE product_name = '";
    $query = $query . $name . "' AND order_id = " . $id;
}
$statement = $connect->prepare($query);
$statement->execute();
?>
