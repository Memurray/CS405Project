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
headerBar("Customer Orders","staff");
?>
<div class="search-container">
      <input type="text" class="textbox" placeholder="Search.." name="search" id="searchBox">
      <button id="buttonSearchBox"><i class="fa fa-search"></i></button>
  </div>
<br>

<b>Status Filter:</b>
  <input type="radio" name="cat" class="select category" id="radioAllCategories" value="All"  checked > All 
  <input type="radio" name="cat" class="select category" id="radioPending" value="Pending" > Pending
  <input type="radio" name="cat" class="select category" id="radioError" value="Error" >Inventory Issue<br><br>

<label>Sort By: <select class="select" id="sort">
<option value="id desc">Order Number: Desc</option>
<option value="id asc">Order Number: Asc</option>
<option value="placed_at desc">Time (New->Old)</option>
<option value="placed_at asc">Time (Old->New)</option>
<option value="price desc">Price: Desc</option>
<option value="price asc">Price: Asc</option>
</select></label>

<br>
<br>


<div class="filter">
</div>

<script>
var category = $("input[name=cat]:checked").val();
var sort = document.getElementById("sort").value;
var searchInput = document.getElementById("searchBox").value;
$(document).ready(function(){
    filter();
    function filter(){
	$.ajax({
            url:"buildOrderDisplay.php",
            method:"POST",
            data:{category:category,sort:sort, searchInput:searchInput},
            success:function(data){
	     $('.filter').html(data);
            }
        });
	}
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

    $('body').on('click', '.statusEdit', function (){
	var clickRow = $(this).attr('val');
	var idLookup = "n" + clickRow;
	var id = document.getElementById(idLookup).innerHTML;
	$.ajax({
            url:"shipOrder.php",
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
