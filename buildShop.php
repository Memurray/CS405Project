<?php
$sort = $_POST["sort"];
echo '
  <table border="1">
  <tr>
  <td><b>Product Name</b></td>
  <td width="80px"><b>Category</b></td>
  <td width="160px"><b>Price ($)</b></td>
  <td width="140px"><b>Purchase Quantity</b></td>
';

$uType = strtolower($_COOKIE["CS405_Usertype"]);


echo '</tr>';

include('dbConnect.php');
$query = "SELECT * FROM products as A LEFT JOIN (SELECT product_name, sum(quantity) as total_sales FROM orders, order_items where id=order_id and status IN ('Pending','Shipped') GROUP BY product_name) AS B ON A.name = B.product_name";
$query .= " ORDER BY " . $sort . ", name asc;";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

$i=1;
foreach($result as $row) {
    echo '<tr>';
    $name = $row['name'];
    $price = $row['price'];
    $rate = $row['promotion_rate'];
    if($rate =="")
	$rate = 0;
    $saveItem = floor($price * $rate)/100;
    $finalPrice = number_format($price-$saveItem,2);
    if($rate == 0)
	$printPrice = $finalPrice;
    else
	$printPrice = $finalPrice . "  ($rate% Off)";
    $cat = $row['category'];
    echo '<td id= n' . $i .'>' . $name . '</td>';
    echo '<td>' . $cat . '</td>';
    echo '<td>' . $printPrice . '</td>';
    echo '<td> <input type="number" style="width: 45px" onkeypress="return event.charCode >= 48" value ="1" min="1" id = t' . $i .  '><button class="buy" val = ' . $i . '>Add to cart</button></td>';
    echo '</tr>';
    $i = $i+1;
}
echo '</table></div>'
?>
