
<!DOCTYPE html> <head> <title>Logged In</title> </head> <body> 
<?php $cookie_name = "CS405Project";
 if(!isset($_COOKIE[$cookie_name])) {
    echo "<h1>Return to homepage.php to log in again.</h1>";
    echo "Cookie named '" . $cookie_name . "' has expired. (cookie lasts 5 seconds right now)";

} else {
    echo "<h1>Hello " . $_COOKIE[$cookie_name] . ". You have successfully logged in</h1>";
    echo "Cookie '" . $cookie_name . "' is set!<br>";
    echo "Right now cookies last 5 seconds, this allows this page to quickly be tested right now <br>";
}
?>
</body>
