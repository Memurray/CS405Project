
<html>
<head>
<title>Employee Account Creation</title>
<link rel="stylesheet" href="styles.css">
</head>


<body>
<div class="main">

<?php
include('header.php');
headerBar("Employee Account Creation Page","manager");
include('dbConnect.php');
$usernameError = "";
$passwordError = "";
$bottomError = "";
$success = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
   $valid = 1;
   $uType = $_POST['user_type'];
    if (empty($_POST['username'])){
        $usernameError = "Username is required";
	$valid = 0;
    }
    if (empty($_POST['password'])) {
        $passwordError = "Password is required";
	$valid = 0;
    }
    if($valid) {
            $username = trim($_POST['username']);
            $usernameCheck = "SELECT * FROM  users WHERE Name = '" . $username . "'"; 
	    $statement = $connect->prepare($usernameCheck);
	    $statement->execute();
	    $result = $statement->fetchAll();
	    $total_row = $statement->rowCount();
	    if($total_row == 0){
            	$password = trim($_POST['password']);
            	$query = "INSERT INTO users values ('$username', '$password', '$uType')";
		$statement = $connect->prepare($query);
		$statement->execute();
	    	$success = "Account successfully created";
            }
            else
            	$bottomError = "That username has already been taken.";
    }
}
?>

<form id="input" method="post">
<fieldset>
<legend>New Employee Account Creation</legend>
<div class="form-group">
<label for="Username">Username: </label>
<input class="form-control" type="text" name="username" id="username" maxlength="50" />
<span class="error"> <?php echo $usernameError;?> </span> </div>

<div class="form-group">
<label for="password">Password:&nbsp  </label>
<input class="form-control" type="text" name="password" id="password" maxlength="12" />
<span class="error"> <?php echo $passwordError;?> </span> </div>

Account Type:
<select class="form-control" name="user_type">
    <option value="Staff">Staff</option>
    <option value="Manager">Manager</option>
  </select>
<br>
<button type="submit" class="b1" name="login" value="1" formaction="./createEmployee.php">Create</button>
</fieldset>
</form>

<span class="error"> <?php echo $bottomError ?> </span>
<?php echo $success ?>
</div>

<?php require('footer.php'); ?>

</body>
</html>
