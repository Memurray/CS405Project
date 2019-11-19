<?php
include('dbConnect.php');
echo '<table border="1"><tr>';
echo '<td width="130px"><b>Order Number</b></td>';
echo '<td width="160px"><b>Status</b></td>';
echo '<td width="170px"><b>Time</b></td>';
echo '<td width="300px"><b>Order Contents</b></td>';
echo '<td width="120px"><b>Price ($)</b></td>';
echo '<td width="120px"><b>You Saved ($)</b></td></tr>';

$filter = $_POST["category"];
$uName = $_COOKIE["CS405_Username"];

if($filter == "All"){
        $query = "SELECT * FROM orders AS A LEFT JOIN (SELECT id AS vid, 1 as valid FROM orders where status!='Cart' AND placed_at > now()- INTERVAL 1 DAY) AS B on A.id = B.vid WHERE user_name ='";
        $query = $query . $uName . "' AND status IN ('Shipped','Pending')";
}
else{
	$query = "SELECT * FROM orders AS A LEFT JOIN (SELECT id AS vid, 1 as valid FROM orders where status!='Cart' AND placed_at > now()- INTERVAL 1 DAY) AS B on A.id = B.vid WHERE user_name ='";
	$query = $query . $uName . "' AND status = '" . $filter . "'";
}
$query .= " ORDER BY placed_at desc";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

$i=1;

foreach($result as $row) {
    echo '<tr>';
    $id = $row['id'];
    $status = $row['status'];
    $time = $row['placed_at'];
    $price = $row['price'];
    $saved = $row['money_saved'];
    $valid = $row['valid'];
    if($saved == "")
	$saved = 0;
    $query = "SELECT * FROM order_items, products WHERE order_id = " . $id . " AND product_name = name";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result2 = $statement->fetchAll();
    echo '<td id= n' . $i .'>' . $id . '</td>';
    if($status == "Pending" and $valid)
	echo '<td>' . $status . ' <button class="cancelOrder" val = ' . $i . '>Cancel</button></td>';
    else if($status == "Cancelled")
        echo '<td>' . $status . ' <button class="restoreOrder right" val = ' . $i . '>Restore</button></td>';
    else
	echo '<td>' . $status . '</td>';
    echo '<td>' . $time . '</td>';
    echo '<td>';
    foreach($result2 as $row2){
	if( $row2['stock_remaining'] < $row2['quantity'] and $status == "Pending")
	    echo "<div class='error'> " . $row2['product_name'] . " x" . $row2['quantity'] ." (Left: " . $row2['stock_remaining'] . ")</div>";
	else
	    echo "<div> " . $row2['product_name'] . " x" . $row2['quantity'] ."</div>";
    }
    echo '</td>';
    echo '<td>' . number_format($price,2) . '</td>';
    echo '<td>' . number_format($saved,2) . '</td>';
    echo '</tr>';
    $i = $i+1;
}
echo '</table>';

?>
