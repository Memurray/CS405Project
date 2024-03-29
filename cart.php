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
headerBar("Cart","customer");
$uName = $_COOKIE["CS405_Username"];

// get the id of the order that is the current users cart
$query = 'SELECT id from orders where status="cart" and user_name="' . $uName;
$query .= '";';
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

// If there is no cart yet
if ($total_row == 0)
  echo "<p> There is nothing in your cart </p>";

// If there are multiple cart (should not be possible, but here for debug)
else if ($total_row >= 2)
  echo "<p> Error: Multiple incomplete orders detected </p>";

// Otherwise, build table
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
  $i=1;

  // For each item in cart display information
  foreach($result as $row){
    $name = $row['product_name'];
    $quantity = $row['quantity'];
    $price = $row['price'];
    $rate = $row['promotion_rate'];
    $saveItem = floor($price * $rate)/100;
    $realPrice = $price-$saveItem;
    $sum = $sum + $price * $quantity;
    $save = $save + $saveItem * $quantity;
    echo '<tr><td id= n' . $i .'>' . $name . '</td>';
    echo '<td> <input type="number" class="cartBox" style="width: 65px" onkeypress="return event.charCode >= 48" value =' . $quantity . ' min="0" id = t' . $i .  '><button class="quantityEdit" val = ' . $i . '>Edit</button></td>';
    echo '<td>' . number_format($realPrice,2) . '</td>';
    echo '<td class="highlight">' . number_format($realPrice*$quantity,2) . '</td>';
    echo '</tr>';
    $i = $i+1;
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
    // If a quantity edit button is clicked, grab vars and call editCart though ajax to update database
    $('.quantityEdit').click(function(){
	var clickRow = $(this).attr('val');
	var nameID = "n" + clickRow;
	var textID = "t" + clickRow;
	var name = document.getElementById(nameID).innerHTML;
	var quantity = document.getElementById(textID).value;
	$.ajax({
            url:"editCart.php",
            method:"POST",
            data:{quantity:quantity, name:name,id:id},
            complete: function(data){
                window.location = './cart.php';
            }
        });
    });

    // If press the enter key inside of the quantity edit field, update cart
    $(".cartBox").keypress(function(){
      if (event.which == 13 ) {
        var enterID = $(this).attr('Id');
        var clickRow = enterID.replace("t", "");
	var nameID = "n" + clickRow;
        var textID = "t" + clickRow;
        var name = document.getElementById(nameID).innerHTML;
        var quantity = document.getElementById(textID).value;
        $.ajax({
            url:"editCart.php",
            method:"POST",
            data:{quantity:quantity, name:name,id:id},
            complete: function(data){
                window.location = './cart.php';
            }
        });
      }
    });


    // If click submit order button, ajax call purchasing code to update database
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
