<!DOCTYPE HTML>
<html>
<head>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="styles.css">
<title>Sales Graph</title>
<?php
include('dbConnect.php');
if (empty($_GET['name'])){
include('header.php');
headerBar("Product Graph","manager");
   echo "No product requested";
   return;

}

$name = $_GET["name"];

//********************************
// Gather all data to be graphed
//********************************
$query = "SELECT * FROM products;";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$count = $statement->rowCount();

$query = "SELECT sum(quantity) as total_sales FROM orders, order_items where id=order_id and placed_at BETWEEN (NOW() - INTERVAL 7 DAY) AND NOW() and status IN ('Pending','Shipped');";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$all_7 = $result[0]["total_sales"];

$query = "SELECT sum(quantity) as total_sales FROM orders, order_items where id=order_id and placed_at BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW() and status IN ('Pending','Shipped');";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$all_30 = $result[0]["total_sales"];

$query = "SELECT sum(quantity) as total_sales FROM orders, order_items where id=order_id and placed_at BETWEEN (NOW() - INTERVAL 365 DAY) AND NOW() and status IN ('Pending','Shipped');";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$all_365 = $result[0]["total_sales"];

$query = "SELECT product_name, sum(quantity) as total_sales FROM orders, order_items where id=order_id and placed_at BETWEEN (NOW() - INTERVAL 7 DAY) AND NOW() and status IN ('Pending','Shipped') AND product_name = '$name' GROUP BY product_name;";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$product_7 = $result[0]["total_sales"];

$query = "SELECT product_name, sum(quantity) as total_sales FROM orders, order_items where id=order_id and placed_at BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW() and status IN ('Pending','Shipped') AND product_name = '$name' GROUP BY product_name;";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$product_30 = $result[0]["total_sales"];

$query = "SELECT product_name, sum(quantity) as total_sales FROM orders, order_items where id=order_id and placed_at BETWEEN (NOW() - INTERVAL 365 DAY) AND NOW() and status IN ('Pending','Shipped') AND product_name = '$name' GROUP BY product_name;";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$product_365 = $result[0]["total_sales"];
//************************************


// Prevent divide by 0 issues
//Otherwise, calculate relative sales
if($all_7 == 0)
   $out_7 = 0;
else
   $out_7 = $product_7/($all_7/$count);

if($all_30 == 0)
   $out_30 = 0;
else
   $out_30 = $product_30/($all_30/$count);

if($all_365 == 0)
   $out_365 = 0;
else
   $out_365 = $product_365/($all_365/$count);

// Fill array with data to be graphed
$dataPoints = array(
        array("label"=> "Last 7 Days", "y"=> $out_7),
        array("label"=> "Last 30 Days", "y"=> $out_30),
        array("label"=> "Last 365 Days", "y"=> $out_365)
);

$max = max($out_7,$out30,$out_365,1)*1.15;
?>

<script>
// Render graph
window.onload = function () {
var name = "<?php echo $name ?>";
var maxY = "<?php echo $max ?>";
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	exportEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title:{
		text: name + " sales relative to all item average"
	},
	axisY:{
		maximum: maxY,
		title: "Sales Relative to Average",
		titleFontColor: "#6D78AD",
		gridColor: "#6D78AD",
	stripLines:[
            {
                
                value:1,
                thickness:3,
		color:"red",
                label : "Average",
            }
            ]
	},
	data: [{
		type: "column", 
		indexLabel: "{y}", //Shows y value on all Data Points
		indexLabelFontSize: 16,
		indexLabelPlacement: "inside",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
</head>
<body>
<?php
include('header.php');
headerBar("Product Graph","manager");
?>



<div id="chartContainer" style="height: 370px; max-width: 850px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
