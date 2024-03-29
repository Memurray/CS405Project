<?php
$sort = $_POST["sort"];
$time = $_POST["timescale"];
$filter = $_POST["filterType"];
$searchInput = $_POST["searchInput"];
echo '
  <table border="1">
  <tr>
  <td width="200px"><b>Product Name</b></td>
  <td width="120px"><b>Base Price ($)</b></td>
  <td width="130px"><b>Stock Remaining</b></td>
  <td width="160px"><b>Current Discount (%)</b></td>
  <td width="80px"><b>Category</b></td>
';

$uType = strtolower($_COOKIE["CS405_Usertype"]);

if($uType == "manager" || $uType == "admin"){
        echo '<td width="60px"><b>Sales</b></td>';
	echo '<td width="90px"><b>Sales Graph</b></td>';
}
echo '</tr>';

include('dbConnect.php');

// Retrive all data that will be used when rendering the page, filtering sums based on input sales window
if($time == "All")
    $query = "SELECT * FROM products as A LEFT JOIN (SELECT product_name, sum(quantity) as total_sales FROM orders, order_items where id=order_id and status IN ('Pending','Shipped') GROUP BY product_name) AS B ON A.name = B.product_name";
else
    $query = "SELECT * FROM products as A LEFT JOIN (SELECT product_name, sum(quantity) as total_sales FROM orders, order_items where id=order_id and placed_at BETWEEN (NOW() - INTERVAL " . $time .  " DAY) AND NOW() and status IN ('Pending','Shipped') GROUP BY product_name) AS B ON A.name = B.product_name";

// Adds sum per product of items currently in pending orders
$query .= '  Left Join (select product_name, sum(quantity) as pending from order_items,orders where status = "Pending" and id=order_id group by product_name) AS C on A.name = C.product_name';

// Adds search bar input filtering
$query .= " WHERE (name LIKE '%" . $searchInput . "%'";
$query .= " OR category LIKE '%" . $searchInput . "%')";

// Adds category filtering
if($filter != "All"){
    $query .= " AND category = '" . $filter;
    $query .= "'";
}

// Order by user selection
$query .= " ORDER BY " . $sort . ", name asc;";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

// For each product build table row
$i=1;
foreach($result as $row) {
    echo '<tr>';
    $name = $row['name'];
    $price = $row['price'];
    $stock = $row['stock_remaining'];
    $rate = $row['promotion_rate'];
    $sales = $row['total_sales'];
    $pending = $row['pending'];
    if($sales == "")
        $sales = 0;
    if($rate == "")
        $rate = 0;
    $cat = $row['category'];
    echo '<td id= n' . $i .'>' . $name . '</td>';
    echo '<td>' . $price . '</td>';
    echo '<td> <input type="number" class="stockBox" style="width: 65px" onkeypress="return event.charCode >= 48" value =' . $stock . ' min="0" id = t' . $i .  '><button class="stockEdit" val = ' . $i . '>Edit</button>';
    if($stock < $pending){
  	echo '<div class="tooltip">!
  	  <span class="tooltiptext"><b>' . $pending . '</b> total items required to fulfill pending orders.</span>
	  </div> ';
    }
    echo '</td>';

    // Only display for manager/admin
    if($uType == "manager" || $uType == "admin")
        echo '<td> <input type="number" class="promoBox" style="width: 45px" onkeypress="return event.charCode >= 48" value =' . $rate . ' min="0" max="100" id = pr' . $i .  '><button class="promoEdit" val = ' . $i . '>Edit</button></td>';
    else
        echo '<td>' . $rate . '</td>';
    echo '<td>' . $cat . '</td>';

    // Only display for manager/admin
    if($uType == "manager" || $uType == "admin"){
        echo '<td>' . $sales . '</td>';
        echo "<td><a href='./chart.php?name=" . $name;
	echo "'>Link</a></td>";
    }
    echo '</tr>';
    $i = $i+1;
}
echo '</table></div>'
?>
