<?php
include('dbConnect.php');

echo '<table border="1"><tr>';
echo '<td width="180px"><b>Product Name</b></td>';
echo '<td width="90px"><b>Items Sold</b></td></tr>';

$filter = $_POST["category"];

if($filter == "All")
    $query = "SELECT product_name, sum(quantity) as total_sales FROM orders, order_items where id=order_id and status != 'Cart' GROUP BY product_name order by total_sales desc;";
else
    $query = "SELECT product_name, sum(quantity) as total_sales FROM orders, order_items where id=order_id and placed_at BETWEEN (NOW() - INTERVAL " . $filter .  " DAY) AND NOW() and status != 'Cart' GROUP BY product_name order by total_sales desc;";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

$i=1;
foreach($result as $row) {
    echo '<tr>';
    $name = $row['product_name'];
    $count = $row['total_sales'];
    echo '<td>' . $name . '</td>';
    echo '<td>' . $count . '</td>';
    echo '</tr>';
}
echo '</table>';

?>
