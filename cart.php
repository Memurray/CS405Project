<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<title>Cart</title>

<link rel="stylesheet" href="styles.css">
</head>

<body>
<div class="main">
<?php
include('header.php');
include('dbConnect.php');
new headerBar("Cart","customer");
$uName = $_COOKIE["CS405_Username"];

$query = 'SELECT id from orders where status="cart" and user_name="' . $uName;
$query .= '";';
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();
if ($total_row == 0)
  echo "<p> There is nothing in your cart </p>";
else if ($total_row >= 2)
  echo "<p> Error: Multiple incomplete orders detected </p>";
else {
  $id = $result[0]['id'];
  echo '<table border="1"><tr>';
  echo '<td width="250px"><b>Product Name</b></td>';
  echo '<td width="80px"><b>Quantity</b></td>';
  echo '<td width="90px"><b>Price ($)</b></td>';
  echo '<td width="110px" class="highlight"><b>Price Total ($)</b></td></tr>';

  $query = 'SELECT * FROM order_items,products where product_name=name and order_id=' . $id;
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $sum = 0;
  $save = 0;
  foreach($result as $row){
    $name = $row['product_name'];
    $quantity = $row['quantity'];
    $price = $row['price'];
    $rate = $row['promotion_rate'];
    $saveItem = floor($price * $rate)/100;
    $realPrice = $price-$saveItem;
    $sum = $sum + $price * $quantity;
    $save = $save + $saveItem * $quantity;
    echo '<tr><td>' . $name . '</td>';
    echo '<td>' . $quantity . '</td>';
    echo '<td>' . number_format($realPrice,2) . '</td>';
    echo '<td class="highlight">' . number_format($realPrice*$quantity,2) . '</td>';
    echo '</tr>';
  }
  echo '</table>';
  echo "<br>Savings: $" . number_format($save,2) . "<br>";
  echo "Order Total: $" . number_format($sum - $save,2) . "<br>";
  echo '<button class="order">Purchase</button>';
}
?>

</div>

<?php require('footer.php'); ?>

<script>
var id = "<?php echo $id ?>";
var price = "<?php echo $sum-$save ?>";
var save = "<?php echo $save ?>";
$(document).ready(function(){
    $('.order').click(function(){
	$.ajax({
            url:"purchaseCart.php",
            method:"POST",
            data:{id:id,price:price,save:save},
            complete: function(data){
		alert("Thanks! Your order has been placed.");
            	window.location = './cart.php';
    	    }
        });

    });
});
</script>
</body>
</html>
