<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<title>Customer Orders</title>

<link rel="stylesheet" href="styles.css">
</head>

<body>
<div class="main">
<?php
include('header.php');
new headerBar("Your Orders","customer");
?>

<b>Status Filter:</b>
  <input type="radio" name="cat" class="select category" id="radioAllCategories" value="All"  checked > All Active 
  <input type="radio" name="cat" class="select category" id="radioPending" value="Pending" > Pending
  <input type="radio" name="cat" class="select category" id="radioCancelled" value="Cancelled" > Cancelled<br><br>

<div class="filter">
</div>
</table>

<script>
var category = "All";
$(document).ready(function(){
    filter();
    function filter(){
	$.ajax({
            url:"buildCustomerOrders.php",
            method:"POST",
            data:{category:category},
            success:function(data){
	     $('.filter').html(data);
            }
        });
	}
    $('.select').click(function(){
	var changed = false;
	var tempCat = $("input[name=cat]:checked").val()
	if(tempCat != category){
		category=tempCat;
		changed = true;
	}
	if(changed){
		filter();
	}
    });

    $('body').on('click', '.cancelOrder', function (){
	var clickRow = $(this).attr('val');
	var idLookup = "n" + clickRow;
	var id = document.getElementById(idLookup).innerHTML;
	$.ajax({
            url:"cancelOrder.php",
            method:"POST",
            data:{id:id},
            complete: function(data){
		filter();
            }
        });
    });

    $('body').on('click', '.restoreOrder', function (){
        var clickRow = $(this).attr('val');
        var idLookup = "n" + clickRow;
        var id = document.getElementById(idLookup).innerHTML;
        $.ajax({
            url:"restoreOrder.php",
            method:"POST",
            data:{id:id},
            complete: function(data){
                filter();
            }
        });
    });


});
</script>

</div>

<?php require('footer.php'); ?>
</body>
</html>
