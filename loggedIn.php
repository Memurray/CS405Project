
<!DOCTYPE html>
 <head> 
<title>Logged In</title> 
    <link rel="stylesheet" href="styles.css">
</head> 


<body> 
<div class="main">
<h1> Login Welcome </h1>
<?php $cookie_name = "CS405Project";
 if(!isset($_COOKIE[$cookie_name])) {
    echo "<h2>Login cookie has expired, return to homepage.php to log in again.</h2>";
    echo "Cookie named '" . $cookie_name . "' has expired. (cookie lasts 5 seconds right now)";

} else {
    echo "<h2>Hello " . $_COOKIE[$cookie_name] . ". You have successfully logged in</h2>";
    echo "Cookie '" . $cookie_name . "' is set!<br>";
    echo "Right now cookies last 5 seconds, this allows this page to quickly be tested right now <br>";
}
?>
</div>

<footer class="footer">
<a href="./homepage.php">Customer Login Page</a>
<a href="./employeeLogin.php">Employee Login Page</a>
<?php  
if(strtolower($_COOKIE[$cookie_name]) == "admin")
echo "<a href='./dbQuery.php'>Database Query</a>";
 ?>
</footer>

</body>
