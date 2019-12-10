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
headerBar("Your Orders","customer");
?>

<!-- Add filter buttons -->
<b>Status Filter:</b>
  <input type="radio" name="cat" class="select category" id="radioAllCategories" value="All"  checked > All Purchased 
  <input type="radio" name="cat" class="select category" id="radioPending" value="Pending" > Pending
  <input type="radio" name="cat" class="select category" id="radioCancelled" value="Cancelled" > Cancelled<br><br>

<!-- Add option dropdown -->
<label>Sort By: <select class="select" id="sort">
<option value="id desc">Order Number: Desc</option>
<option value="id asc">Order Number: Asc</option>
<option value="placed_at desc">Time (New->Old)</option>
<option value="placed_at asc">Time (Old->New)</option>
<option value="price desc">Price: Desc</option>
<option value="price asc">Price: Asc</option>
<option value="money_saved desc">Money Saved: Desc</option>
<option value="money_saved asc">Money Saved: Asc</option>
</select></label>

<br>
<br>
<div class="filter">
</div>

<script>
var category = $("input[name=cat]:checked").val()
var sort = document.getElementById("sort").value;
$(document).ready(function(){
    filter();  //Generate items on page load

    // script called by ajax builds the table displayed in pre-allocated div
    function filter(){
	$.ajax({
            url:"buildCustomerOrders.php",
            method:"POST",
            data:{category:category,sort:sort},
            success:function(data){
	     $('.filter').html(data);
            }
        });
	}

    // If a button is clicked, check to see if it has changed
    $('.select').click(function(){
	var changed = false;

    	var tempSort = $("#sort :selected").val();
    	if(tempSort != sort){
	    sort=tempSort;
            changed = true;
    	}

	var tempCat = $("input[name=cat]:checked").val()
	if(tempCat != category){
		category=tempCat;
		changed = true;
	}
    	// If changed, regen table
	if(changed){
		filter();
	}
    });

    // If cancel order button is clicked, run cancelOrder script
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

    // If a restore button is clicked, run restoreOrder script 
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
