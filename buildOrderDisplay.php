<?php
include('dbConnect.php');

echo '<table border="1"><tr>';
echo '<td width="130px"><b>Order Number</b></td>';
echo '<td width="160px"><b>User name</b></td>';
echo '<td width="160px"><b>Status</b></td>';
echo '<td width="170px"><b>Time</b></td>';
echo '<td width="200px"><b>Order Contents</b></td>';
echo '<td width="160px"><b>Price ($)</b></td></tr>';

$filter = $_POST["category"];
$sort = $_POST["sort"];
$searchInput = $_POST["searchInput"];

// Get all orders
if($filter == "All")
	$query = "SELECT * FROM orders WHERE status IN ('Pending','Shipped')";
else //get pending orders
	$query = "SELECT * FROM orders WHERE status = 'Pending'";
$query .= " AND (user_name LIKE '%" . $searchInput . "%'";
$query .= " OR status LIKE '%" . $searchInput . "%')";
$query .= " ORDER BY $sort";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

// For each order, build table row
$i=1;
foreach($result as $row) {
    $id = $row['id'];
    $name = $row['user_name'];
    $status = $row['status'];
    $time = $row['placed_at'];
    $price = $row['price'];

    // get the products in this order
    $query = "SELECT * FROM order_items, products WHERE order_id = " . $id . " AND product_name = name";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result2 = $statement->fetchAll();

    $valid = 1;
    //check if stock is acceptable for all items
    foreach($result2 as $row2){
   	if( $row2['stock_remaining'] < $row2['quantity'])
            $valid = 0;
    }

    // Dont show this order if this is a valid order but user chose error filter
    if($filter == "Error" && $valid)
	continue;

    echo '<tr>';
    echo '<td id= n' . $i .'>' . $id . '</td>';
    echo '<td>' . $name . '</td>';
    if($status == "Pending" and $valid)
	echo '<td>' . $status . ' <button class="statusEdit" val = ' . $i . '>Ship It</button></td>';
    else if($status == "Pending" and !$valid)
        echo '<td class="error">' . $status . ' (Err:Inventory)</td>';
    else
	echo '<td>' . $status . '</td>';
    echo '<td>' . $time . '</td>';
    echo '<td>';
    $valid = 1;

    // Display all products in order, highlighting problematic items (when needed)
    foreach($result2 as $row2){
	if( $row2['stock_remaining'] < $row2['quantity'] and $status == "Pending")
	    echo "<div class='error'> " . $row2['product_name'] . " x" . $row2['quantity'] ." (Left: " . $row2['stock_remaining'] . ")</div>";
	else
	    echo "<div> " . $row2['product_name'] . " x" . $row2['quantity'] ."</div>";
    }
    echo '</td>';
    echo '<td>' . number_format($price,2) . '</td>';
    echo '</tr>';
    $i = $i+1;
}
echo '</table>';

?>
