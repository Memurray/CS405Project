<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Inventory</title>

<link rel="stylesheet" href="styles.css">
<style>
.add input {
 width: 99%;}

.pad{
margin-left: 100px;
}


</style>
</head>
<body>
<div class="main">
<?php
include('header.php');
headerBar("Inventory Management","staff");
$usertype = strtolower($_COOKIE["CS405_Usertype"]);
?>

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
<div class="search-container">
      <input type="text" class="textbox" placeholder="Search.." name="search" id="searchBox">
      <button id="buttonSearchBox"><i class="fa fa-search"></i></button>
  </div>
<br>

<label>Sort By: <select class="select" id="sort">
<option value="name asc">Name: A-Z</option>
<option value="name desc">Name: Z-A</option>
<option value="price asc">Price: Asc</option>
<option value="price desc">Price: Desc</option>
<?php
if($usertype == "manager" or $usertype == "admin"){
    echo '<option value="total_sales asc">Sales: Asc</option>
      <option value="total_sales desc">Sales: Desc</option>
    ';
}
?>
</select></label>

<label class="pad" >Show: <select class="select" id="filterID">
<option value="All">All</option>
<option value="Toy">Only Toys</option>
<option value="Book">Only Books</option>
</select></label>


<?php
if($usertype == "manager" or $usertype == "admin"){
    echo '<label class="pad">Sales Window: <select class="select" id="sales_window">
      <option value="All">All Time</option>
      <option value="7">7 Days</option>
      <option value="30">1 Month</option>
      <option value="365">1 Year</option>
      </select></label>';
}
?>
<br>
<br>

<div class="inventory">
</div>

<script>
var sort = "Name asc";
var timescale = "All";
var usertype = "<?php echo $usertype ?>";
var filterType = "All";
var searchInput = "";
$(document).ready(function(){
    filter();
    function filter(){
        $.ajax({
            url:"buildInventory.php",
            method:"POST",
            data:{sort:sort,timescale:timescale,filterType:filterType,searchInput:searchInput},
            success:function(data){
	     $('.inventory').html(data);
            }
        });
    }

    $('body').on('click', '.stockEdit', function (){
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
                filter();
            }
        });
    });

   $('body').on('keypress', '.stockBox', function (){
     if ( event.which == 13 ) {
	var enterID = $(this).attr('Id');
 	var clickRow = enterID.replace("t", "");
	var nameID = "n" + clickRow;
        var textID = "t" + clickRow;
        var name = document.getElementById(nameID).innerHTML;
        var stock = document.getElementById(textID).value;
        $.ajax({
            url:"editStock.php",
            method:"POST",
            data:{stock:stock, name:name},
            complete: function(data){
                filter();
            }
        });

      }
    });


    $('body').on('click', '.promoEdit', function (){
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
		filter();
            }
        });
    });

    $('body').on('keypress', '.promoBox', function (){
     if ( event.which == 13 ) {
        var enterID = $(this).attr('Id');
        var clickRow = enterID.replace("pr", "");
	var nameID = "n" + clickRow;
        var promoID = "pr" + clickRow;
        var name = document.getElementById(nameID).innerHTML;
        var promo = document.getElementById(promoID).value;
        $.ajax({
            url:"editPromo.php",
            method:"POST",
            data:{promo:promo, name:name},
            complete: function(data){
                filter();
            }
        });
      }
    });



    $('.select').click(function(){
	var changed = false;
	var tempSales = $("#sales_window :selected").val();
	if(tempSales != timescale && (usertype == "manager" || usertype == "admin")){
		timescale=tempSales;
		changed = true;
	}
        var tempSort = $("#sort :selected").val();
        if(tempSort != sort){
	        sort=tempSort;
                changed = true;
        }

        var tempFilter = $("#filterID :selected").val();
        if(tempFilter != filterType){
                filterType = tempFilter;
                changed = true;
        }

	if(changed){
		filter();
	}
    });


    $("#buttonSearchBox").click(function(){
	searchSubmit();
    });

    $("#searchBox").keypress(function(){
  	if ( event.which == 13 ) {
    	    searchSubmit();
        }
    });
    function searchSubmit(){
   	searchInput = document.getElementById("searchBox").value;
	filter();
    }


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
                filter();
		document.getElementById("inName").value = "";
        	document.getElementById("inPrice").value = "";
        	document.getElementById("inStock").value = "";
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
