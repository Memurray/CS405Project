<!DOCTYPE html>
<html>
<head>
<style>
.statusEdit{
float: right;
}

</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<title>Customer Orders</title>

<link rel="stylesheet" href="styles.css">
</head>
<div class="main">
<h1>Customer Orders</h1>
<h2>Ship it does not currently validate inventory counts</h2>
<table border="1">
<tr>
<td width="130px"><b>Order Number</b></td>
<td width="160px"><b>User name</b></td>
<td width="160px"><b>Status</b></td>
<td width="170px"><b>Time</b></td>
<td width="200px"><b>Order Contents</b></td>
<td width="160px"><b>Price ($)</b></td>
</tr>



<body>
<?php
include('dbConnect.php');
$query = "SELECT * FROM orders WHERE status != 'Cart'";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

$i=1;
foreach($result as $row) {
    echo '<tr>';
    $id = $row['id'];
    $name = $row['user_name'];
    $status = $row['status'];
    $time = $row['placed_at'];
    $price = $row['price'];

    $query = "SELECT * FROM order_items WHERE order_id = '" . $id;
    $query = $query . "';";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result2 = $statement->fetchAll();

    echo '<td id= n' . $i .'>' . $id . '</td>';
    echo '<td>' . $name . '</td>';
    if($status == "Pending")
	echo '<td>' . $status . ' <button class="statusEdit" val = ' . $i . '>Ship It</button></td>';
    else
	echo '<td>' . $status . '</td>';
    echo '<td>' . $time . '</td>';
    echo '<td>';
    foreach($result2 as $row2){
	echo $row2['product_name'] . " x" . $row2['quantity'] . "<br>";
    }
    echo '</td>';
    echo '<td>' . $price . '</td>';
    echo '</tr>';
    $i = $i+1;
}
echo '</table>';

?>

<script>
$(document).ready(function(){
    $('.statusEdit').click(function(){
	var clickRow = $(this).attr('val');
	var idLookup = "n" + clickRow;
	var id = document.getElementById(idLookup).innerHTML;
	$.ajax({
            url:"shipOrder.php",
            method:"POST",
            data:{id:id},
            complete: function(data){
                window.location.href='./orders.php';
            }
        });
    });

});
</script>

</div>

<?php require('footer.php'); ?>
</body>
</html>
