<html>

<head>
<title> Employee Login </title>
<link rel="stylesheet" href="styles.css">
</head>

<body>
<div class="main">
<h1> Employee Login Page </h1> 
<?php
include('dbConnect.php');
$usernameError = "";
$passwordError = "";
$bottomError = "";
$success = "";

// If got to this page by post, check if all fields are filled
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
    // If all fields present
    if($valid) {
 		// Check if username exists
                $username = trim($_POST['username']);
		$password = trim($_POST['password']);
 		$usernameCheck = "SELECT * FROM users WHERE Name = '" . $username . "'";
		$usernameCheck = $usernameCheck ." AND user_type IN ('staff','manager','admin')";
		$statement = $connect->prepare($usernameCheck);
		$statement->execute();
		$result = $statement->fetchAll();
		$total_row = $statement->rowCount();
            	if ($total_row == 0) { //Print error if there is user by this name
 		    $bottomError = "There is no employee account by this name";
		}
		else{  //check if password is correct
		    $passwordCheck = "SELECT * FROM users WHERE Name = '" . $username . "'";
		    $passwordCheck = $passwordCheck . " AND Password = '" . $password . "'";
	            $passwordCheck = $passwordCheck . " AND user_type IN ('staff','manager', 'admin')";
		    $statement = $connect->prepare($passwordCheck);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    $total_row = $statement->rowCount();
                    if ($total_row == 0) { //if wrong password, show error
		    	$bottomError = "Password is not correct";
		    }
		    else{  //if all good, save login to cookies, move user to login confirmation page
			setcookie("CS405_Username", $username, time()+3600, '/');
			setcookie("CS405_Usertype", $result[0]['user_type'], time()+3600, '/');
			header("Location: ./loggedIn.php");
		   }
		}
	}
}
?>

<!-- Build login form -->
<form class="form-style" id="landing" method="post">
<ul>
<li>
<div class="form-group">
<label for="Username">Username</label>
<input class="field-style field-full align-none" type="text" name="username" id="username" maxlength="50" placeholder="Username"/>
<span class="error"> <?php echo $usernameError;?> </span> </div>
</li>

<li>
<div class="form-group">
<label for="password">Password</label>
<input class="field-style field-full align-none" type="password" name="password" id="password" maxlength="50" placeholder="Password" />
<span class="error"> <?php echo $passwordError;?> </span> </div>
</li>

<li>
<button type="submit" class="b1" name="login" value="1" formaction="./employeeLogin.php">Login</button>
<span class="error"> <?php echo $bottomError ?> </span>
<?php echo $success ?>

</li>
</ul>
</form>
</div>

<footer class="footer">
<a href="./homepage.php">Customer Login Page</a>
</footer>

</body>
</html>
