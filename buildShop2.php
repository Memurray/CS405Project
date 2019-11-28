<style>
#wrapper {
  display: flex;
  flex-wrap: wrap;  
}

.card h2 {
font-size: 20px;
padding-top: 10px;
height: 58px;
margin-top: 0px;
margin-bottom: 10px;
background: #435e89;
color: white;
border-radius:20px 20px 0px 0px;

}

.card {
  font-family: verdana;
  background: white;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 200px;
  min-width: 200px;
  text-align: center;
  margin: 10px;
  padding-top: 0px;
  border-radius: 25px;
border: 3px solid black;
}

.card:hover {
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}


.card button {
  height: 27px;
  border: none;
  outline: 0;
  color: white;
  background-color: #323232;
  text-align: center;
  cursor: pointer;
  box-sizing: border-box;
  font-size: 18px;
}

.card button:hover {
  background-color: #808080;
}

.cartIn {

height: 19px;
width: 45px;
padding-top:3px;
}

img {
  width: 100px;
  height: auto;
}

</style>

<?php
$sort = $_POST["sort"];
$uType = strtolower($_COOKIE["CS405_Usertype"]);

include('dbConnect.php');
$query = "SELECT * FROM products as A LEFT JOIN (SELECT product_name, sum(quantity) as total_sales FROM orders, order_items where id=order_id and status IN ('Pending','Shipped') GROUP BY product_name) AS B ON A.name = B.product_name";
$query .= " ORDER BY " . $sort . ", name asc;";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

echo '<div id="wrapper" class="filter">';

$i=1;
foreach($result as $row) {
    echo '<div class="card">';
    $name = $row['name'];
    $cat = $row['category'];
    $price = $row['price'];
    $rate = $row['promotion_rate'];
    $image = "./images/" . $cat . ".png";
    if($rate =="")
	$rate = 0;
    $saveItem = floor($price * $rate)/100;
    $finalPrice = number_format($price-$saveItem,2);
    if($rate == 0)
	$printPrice = $finalPrice;
    else
	$printPrice = $finalPrice . "  ($rate% Off)";
    echo '<h2 id= n' . $i .'>' . $name . '</h2>';
    echo "<img src=$image>";
    echo "<p><b>$$printPrice</b></p>";
    echo '<p> <input type="number" class="cartIn" onkeypress="return event.charCode >= 48" value ="1" min="1" id = t' . $i .  '><button class="buy" val = ' . $i . '>Add to cart</button></p>';
    $i = $i+1;
    echo "</div>";
}

echo "</div>";
?>
