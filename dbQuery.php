<!DOCTYPE html>
<html>
<head>
<title>dbQuery</title>

<link rel="stylesheet" href="styles.css">

</head>
<body>
<div class="main">
<img src="https://cdn.discordapp.com/attachments/539230457070354461/641759099318566953/schema.png" style="width:800px;height:192px;border:0;">

<?php
$val = "SELECT * FROM users;";
if($_SERVER["REQUEST_METHOD"] == "POST") {
if (!empty($_POST['qInput'])) {
$val =  trim($_POST['qInput']);
}
}

?>
<form id="register_form" action="dbQuery.php" method="post">
<label for="qInput">Query Input: </label>
<input class="form-control" type="text" name="qInput" value=" <?php echo $val; ?> " id="qInput" maxlength="200" />
<input class="btn btn-default" type="submit" name="submit" value="Process" />
</form>

<?php
include('dbConnect.php');
if($_SERVER["REQUEST_METHOD"] == "POST") {
if (!empty($_POST['qInput'])) {
$q = trim($_POST['qInput']);
$pattern = "/[Dd][Rr][oO][pP]/";
if(preg_match($pattern, $q)){
    echo "Drop not allowed from this page!";
}
 else{
$query = $q;



$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();
if($total_row > 0) {
echo '<table border="1" width="80%">';
echo '<tr>';
$i = 0;
foreach($result[0] as $key => $value) {
    if($i%2 == 0) {
    echo '<td>';
    echo "<b>".$key . "</b>";
    echo '</td>';}
    $i = $i+1;
}
$i=0;
echo '</tr>';
foreach($result as $row) {
    echo '<tr>';
        foreach($row as $column) {
        if($i%2 == 0) {
        echo '<td>';
        echo $column;
        echo '</td>';}
        $i = $i+1;

    }
    echo '</tr>';
}
echo '</table>';



}
}
}


}
?>
</div>

<?php require('footer.php'); ?>
</body>
</html>
