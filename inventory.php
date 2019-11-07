<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<title>Inventory</title>

<link rel="stylesheet" href="styles.css">
<style>
.add input {
 width: 99%;}


</style>
</head>
<div class="main">
<h1>Inventory Management</h1>
<div class="add">
<h3> Add New Item </h3>
<table border="1">
<tr>
<td><b>Product Name</b></td>
<td><b>Base Price ($)</b></td>
<td><b>Stock Remaining</b></td>
<td><b>Category</b></td>
</tr>
<tr>
<td><input type="text" size="50" id="inName"></td>
<td><input type="number" onkeypress="return event.charCode >=46" min="0.00" step="0.01" id="inPrice"> </td>
<td><input type="number" onkeypress="return event.charCode >=48" min="0" id="inStock"></td>
<td>
<select id="inCat">
    <option value="Toy">Toy</option>
    <option value="Book">Book</option>
  </select>
</td>
</table>
<button class="addButton" name="Add">Add item</button>

</div>
<br>

<h3>Current Inventory</h3>
<table border="1">
<tr>
<td><b>Product Name</b></td>
<td width="120px"><b>Base Price ($)</b></td>
<td width="130px"><b>Stock Remaining</b></td>
<td width="160px"><b>Current Discount (%)</b></td>
<td width="80px"><b>Category</b></td>
</tr>



<body>
<?php
include('dbConnect.php');
$query = "SELECT * FROM products";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

$i=1;
foreach($result as $row) {
    echo '<tr>';
    $name = $row['name'];
    $price = $row['price'];
    $stock = $row['stock_remaining'];
    $rate = $row['promotion_rate'];
    if($rate == "")
	$rate = 0;
    $cat = $row['category'];
    echo '<td id= n' . $i .'>' . $name . '</td>';
    echo '<td>' . $price . '</td>';
    echo '<td> <input type="number" style="width: 65px" onkeypress="return event.charCode >= 48" value =' . $stock . ' min="0" id = t' . $i .  '><button class="stockEdit" val = ' . $i . '>Edit</button></td>';
    if(strtolower($_COOKIE["CS405_Usertype"]) == "manager")
    	echo '<td> <input type="number" style="width: 45px" onkeypress="return event.charCode >= 48" value =' . $rate . ' min="0" max="100" id = pr' . $i .  '><button class="promoEdit" val = ' . $i . '>Edit</button></td>';
    else
	echo '<td>' . $rate . '</td>';
    echo '<td>' . $cat . '</td>';
    echo '</tr>';
    $i = $i+1;
}
echo '</table>';

?>

<script>
$(document).ready(function(){
    $('.stockEdit').click(function(){
	var clickRow = $(this).attr('val');
	var nameID = "n" + clickRow;
	var textID = "t" + clickRow;
	var name = document.getElementById(nameID).innerHTML;
	var stock = document.getElementById(textID).value;
	$.ajax({
            url:"editStock.php",
            method:"POST",
            data:{stock:stock, name:name},
            complete: function(data){
                window.location.href='./inventory.php';
            }
        });
    });

    $('.promoEdit').click(function(){
        var clickRow = $(this).attr('val');
        var nameID = "n" + clickRow;
        var promoID = "pr" + clickRow;
        var name = document.getElementById(nameID).innerHTML;
        var promo = document.getElementById(promoID).value;
        $.ajax({
            url:"editPromo.php",
            method:"POST",
            data:{promo:promo, name:name},
            complete: function(data){
                window.location.href='./inventory.php';
            }
        });
    });




   $('.addButton').click(function(){
   	var name = document.getElementById("inName").value;
        var price = document.getElementById("inPrice").value;
        var stock = document.getElementById("inStock").value;
        var cat = document.getElementById("inCat").value;
	var valid = 1;
	var errMessage = "";
	if(name == ""){
		errMessage = errMessage + "Product Name missing\n";
		valid = 0;
	}
	if(price == ""){
                errMessage = errMessage + "Price missing\n";
                valid = 0;
        }
	if(stock == ""){
                errMessage = errMessage + "Stock Remaining missing\n";
                valid = 0;
        }
	if(valid){
	    $.ajax({
	    url:"addProduct.php",
            method:"POST",
            data:{name:name, price:price, stock:stock, cat:cat},
            complete: function(data){
                window.location.href='./inventory.php';
            }
            });
	}
	else
		alert(errMessage);
   });


});
</script>

</div>

<?php require('footer.php'); ?>
</body>
</html>
