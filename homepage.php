<!DOCTYPE html>
<head>
<title>Customer Homepage</title>
<link rel="stylesheet" href="styles.css">
</head>


<body>
<div class="main">
<h1>Customer Login/Registration Page </h1>
<?php
include('dbConnect.php');
$usernameError = "";
$passwordError = "";
$bottomError = "";
$success = "";
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
	    $statement = $connect->prepare($usernameCheck);
	    $statement->execute();
	    $result = $statement->fetchAll();
	    $total_row = $statement->rowCount();
            if ($total_row == 0) {
            	$password = trim($_POST['password']);
            	$query = "INSERT INTO users values ('$username', '$password', 'Customer')";
		$statement = $connect->prepare($query);
		$statement->execute();
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
                $usernameCheck = $usernameCheck . " AND user_type IN('customer','admin');";
                $statement = $connect->prepare($usernameCheck);
                $statement->execute();
                $result = $statement->fetchAll();
                $total_row = $statement->rowCount();
                if ($total_row == 0) {
		    $bottomError = "There is no customer account by this name";
		}
		else{
		    $passwordCheck = "SELECT * FROM users WHERE Name = '" . $username . "'";
		    $passwordCheck = $passwordCheck . " AND Password = '" . $password . "'";
                    $passwordCheck = $passwordCheck . " AND user_type in ('customer','admin');";
	            $statement = $connect->prepare($passwordCheck);
         	    $statement->execute();
            	    $result = $statement->fetchAll();
            	    $total_row = $statement->rowCount();
            	    if ($total_row == 0) {
		    	$bottomError = "Password is not correct";
		    }
		    else{
			setcookie("CS405_Username", $username, time()+3600, '/');
			setcookie("CS405_Usertype", $result[0]['user_type'], time()+3600, '/');
			header("Location: ./storefront.php");
		   }
		}
	}
    }
}
?>

<form class="form-style" id="landing" method="post">
<ul>
<li>
<div class="form-group">
<label for="Username">Username</label>
<input class="field-style field-full align-none" type="text" name="username" id="username" maxlength="50" placeholder="Username" />
<span class="error"> <?php echo $usernameError;?> </span> </div>
</li>

<li>
<div class="form-group">
<label for="password">Password</label>
<input class="field-style field-full align-none" type="password" name="password" id="password" maxlength="50" placeholder="Password"/>
<span class="error"> <?php echo $passwordError;?> </span> </div>
</li>

<li>
<button type="submit" class="b1" name="login" value="1" formaction="./homepage.php">Login</button>
<button type="submit" class="b1" name="register" value="1" formaction="./homepage.php">Register</button>
<span class="error"> <?php echo $bottomError ?> </span>
<?php echo $success ?>
</li>

</ul>
</form>
</div>

<footer class="footer">
<a href="./employeeLogin.php">Employee Login Page</a>
</footer>


</body>
