<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Store</title>

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
headerBar("Store","customer");
$usertype = strtolower($_COOKIE["CS405_Usertype"]);
?>

<div class="search-container">
      <input type="text" class="textbox" placeholder="Search.." name="search" id="searchBox">
      <button id="buttonSearchBox"><i class="fa fa-search"></i></button>
</div><br>

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

<br>
<br>

<div class="inventory">
</div>

<script>
var sort = "Name asc";
var filterType = $("#filterID :selected").val();
var usertype = "<?php echo $usertype ?>";
var searchInput = document.getElementById("searchBox").value;
$(document).ready(function(){
    filter();
    function filter(){
	$.ajax({
            url:"buildShop2.php",
            method:"POST",
            data:{sort:sort,filterType:filterType,searchInput:searchInput},
            success:function(data){
	     $('.inventory').html(data);
            }
        });
    }

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


    $('.select').click(function(){
	var changed = false;
        var tempSort = $("#sort :selected").val()
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

});
</script>

</div>

<?php require('footer.php'); ?>
</body>
</html>
