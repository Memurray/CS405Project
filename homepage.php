<!DOCTYPE html>
<style>
.error{
color: red;
}

.b1{
margin-top: 10px;
margin-right: 15px;
}

.form-control{
margin-bottom: 5px;
}
</style>

<body>
<h1> CS405 Project Login/Registration Page </h1>
Members: Michael Murray, Craig Scarboro, Thomas Stokes <br><br>
<?php

ini_set('display_errors',1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$servername = 'localhost';
$username = 'memu225';
$password = 'Angrysalad592';
$dbname = 'CS405Project';
$connection = new mysqli($servername, $username, $password, $dbname);
$usernameError = "";
$passwordError = "";
$bottomError = "";
$success = "";
if($connection -> connect_error){
	echo "Error connecting to database - " + $connection->connect_error;
}
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $valid = 1;
    if (empty($_POST['username'])){
        $usernameError = "Username is required";
	$valid = 0;
    }
    if (empty($_POST['password'])) {
        $passwordError = "Password is required";
	$valid = 0;
    }
    if($valid) {
	if(!empty($_POST['register'])){
            $username = trim($_POST['username']);
            $usernameCheck = "SELECT * FROM  users WHERE Name = '" . $username . "'"; 
            $usernameCheckQuery = mysqli_query($connection, $usernameCheck);
            $usernameCheckCount = mysqli_num_rows($usernameCheckQuery);
            if ($usernameCheckCount == 0) {
            	$password = trim($_POST['password']);
            	$query = "INSERT INTO users values ('$username', '$password', 'customer')";
            	$result = mysqli_query($connection, $query);
	    	$success = "Account successfully created";
        	}
            else
            {
            	$bottomError = "That username has already been taken.";
            }
	}
	else{
                $username = trim($_POST['username']);
		$password = trim($_POST['password']);
 		$usernameCheck = "SELECT * FROM users WHERE Name = '" . $username . "'";
            	$usernameCheckQuery = mysqli_query($connection, $usernameCheck);
            	$usernameCheckCount = mysqli_num_rows($usernameCheckQuery);
            	if ($usernameCheckCount == 0) {
		    $bottomError = "There is no account by this name";
		}
		else{
		    $passwordCheck = "SELECT * FROM users WHERE Name = '" . $username . "'";
		    $passwordCheck = $passwordCheck . " AND Password = '" . $password . "'";
	            $passwordCheckQuery = mysqli_query($connection, $passwordCheck);
        	    $passwordCheckCount = mysqli_num_rows($passwordCheckQuery);
           	    if ($passwordCheckCount == 0) {
		    	$bottomError = "Password is not correct";
		    }
		    else{
			setcookie("CS405Project", $username, time()+5);
			header("Location: ./loggedIn.php");
		   }
		}
	}
    }
}

session_start();
?>

<form id="landing" method="post">
<fieldset>
<legend>Registration/Login</legend>
<div class="form-group">
<label for="Username">Username: </label>
<input class="form-control" type="text" name="username" id="username" maxlength="50" />
<span class="error"> <?php echo $usernameError;?> </span> </div>

<div class="form-group">
<label for="password">Password:&nbsp  </label>
<input class="form-control" type="password" name="password" id="password" maxlength="12" />
<span class="error"> <?php echo $passwordError;?> </span> </div>

<button type="submit" class="b1" name="register" value="1" formaction="./homepage.php">Register</button>
<button type="submit" class="b1" name="login" value="1" formaction="./homepage.php">Login</button>
</fieldset>
</form>

<span class="error"> <?php echo $bottomError ?> </span>
<?php echo $success ?>

</body>
</html>
