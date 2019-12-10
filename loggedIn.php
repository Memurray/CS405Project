<!DOCTYPE html>
 <head> 
<title>Logged In</title> 
    <link rel="stylesheet" href="styles.css">
</head> 


<body> 
<div class="main">
<?php 

// Very basic page that only employees still see, no real purpose right now other than potentially debugging
include('header.php');
headerBar("Login Welcome","");
$cookie_name1 = "CS405_Username";
$cookie_name2 = "CS405_Usertype";
 if(!isset($_COOKIE[$cookie_name1])) {
    echo "<h2>Login cookie has expired, return to homepage.php to log in again.</h2>";
    echo "Cookie named '" . $cookie_name . "' has expired. (cookie lasts 1 hour right now)";

} else {
    echo "<h2>Hello " . $_COOKIE[$cookie_name1] . ". You have successfully logged in</h2>";
    echo "Your account type is: " . $_COOKIE[$cookie_name2] . ".<br>";
}
?>
</div>
<?php
require('footer.php');
?>

</body>
