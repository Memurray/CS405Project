<br>
<footer class="footer">
<?php
// Show basic login page links always at bottom of screen, opposite of current login type
$cookie_name1 = "CS405_Username";
$cookie_name2 = "CS405_Usertype";
$uType = strtolower($_COOKIE[$cookie_name2]);
if($uType == "admin")
    echo '<a href="./employeeLogin.php">Employee Login Page</a>';
if($uType != "customer")
    echo '<a href="./homepage.php">Customer Login Page</a>';
else
    echo '<a href="./employeeLogin.php">Employee Login Page</a>';
?>
</footer>

