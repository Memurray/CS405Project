<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
<title>Shop</title> 
<link rel="stylesheet" href="styles.css"> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>	
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
.button {
cursor: pointer;
}

.pad{
margin-left: 100px;
}
</style>

</head>
<div class="main">
<?php
include('header.php');
headerBar("Store","customer");
?>
<body>
<!-- Build user input options -->
<div class="search-container">
      <input type="text" class="textbox" placeholder="Search.." name="search" id="searchBox">
      <button id="buttonSearchBox"><i class="fa fa-search"></i></button>
  </div>
<br>

<label>Sort By: <select class="select" id="sort">
<option value="name asc">Name: A-Z</option>
<option value="name desc">Name: Z-A</option>
<option value="(price*(100-promotion_rate)) asc">Price: Asc</option>
<option value="(price*(100-promotion_rate)) desc">Price: Desc</option>
<option value="promotion_rate desc">Best deal</option>
</select></label>

<label class="pad" >Show: <select class="select" id="filterID">
<option value="All">All</option>
<option value="Toy">Only Toys</option>
<option value="Book">Only Books</option>
</select></label>
<br><br>

<div class="storefront">
</div>

<script>
var sort = document.getElementById("sort").value;
var filterType = $("#filterID :selected").val();
var searchInput = document.getElementById("searchBox").value;
$(document).ready(function(){
    buildTable();
    function buildTable(){  //builds storefront page based on user input
        $.ajax({
            url:"buildStorefront.php",
            method:"POST",
            data:{sort:sort, filterType:filterType, searchInput:searchInput},
            success:function(data){
	     $('.storefront').html(data);
            }
        });
    }
    // If add to cart button pressed, runs add to cart script
    $('body').on('click', '.buy', function (){
	var clickRow = $(this).attr('val');
	var nameID = "n" + clickRow;
	var textID = "t" + clickRow;
	var name = document.getElementById(nameID).innerHTML;
	var quantity = document.getElementById(textID).value;
	$.ajax({
            url:"addToCart.php",
            method:"POST",
            data:{quantity:quantity, name:name},
            success: function(data){
                alert(data);
            }
        });
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
	buildTable();
}

// If user selection changes, regenerate the storefront
$('.select').click(function(){
    var changed = false;

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
	buildTable();
    }
});
});
</script>
</div>
<?php require('footer.php'); ?>

</body>
</html>
