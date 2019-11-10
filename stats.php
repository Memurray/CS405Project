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
<title>Sales Statistics</title>

<link rel="stylesheet" href="styles.css">
</head>

<body>
<div class="main">
<h1>Product Sale Statistics</h1>

<b>Historical Sales:</b>
  <input type="radio" name="cat" class="select category" id="radioAllCategories" value="All"  checked > All time 
  <input type="radio" name="cat" class="select category" id="radio7day" value=7> Last 7 Days
  <input type="radio" name="cat" class="select category" id="radio1month" value=30 > Last Month
  <input type="radio" name="cat" class="select category" id="radio1year" value=365 > Last Year<br><br>

<div class="filter">
</div>
</table>

<script>
var category = "All";
$(document).ready(function(){
    filter();
    function filter(){
	$.ajax({
            url:"buildStats.php",
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
});

</script>

</div>

<?php require('footer.php'); ?>
</body>
</html>
