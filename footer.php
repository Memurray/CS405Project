<footer class="footer">
<a href="./homepage.php">Customer Login Page</a>
<a href="./employeeLogin.php">Employee Login Page</a>
<?php
$cookie_name1 = "CS405_Username";
$cookie_name2 = "CS405_Usertype";
if(strtolower($_COOKIE[$cookie_name1]) == "admin")
echo "<a href='./dbQuery.php'>Database Query</a>";
if(strtolower($_COOKIE[$cookie_name2]) == "manager")
echo "<a href='./createEmployee.php'>Employee Account Create</a>";

 ?>
</footer>

