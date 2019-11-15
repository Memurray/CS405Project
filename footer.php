<footer class="footer">
<?php
$cookie_name1 = "CS405_Username";
$cookie_name2 = "CS405_Usertype";
if(strtolower($_COOKIE[$cookie_name2]) != "customer")
    echo '<a href="./homepage.php">Customer Login Page</a>';
else
    echo '<a href="./employeeLogin.php">Employee Login Page</a>';
if(strtolower($_COOKIE[$cookie_name1]) == "admin")
    echo "<a href='./dbQuery.php'>Database Query</a>";
if(strtolower($_COOKIE[$cookie_name2]) == "manager")
    echo "<a href='./createEmployee.php'>Employee Account Create</a>";
if(strtolower($_COOKIE[$cookie_name2]) != "customer")
    echo "<a href='./inventory.php'>Inventory Management</a>";
if(strtolower($_COOKIE[$cookie_name2]) != "customer")
    echo "<a href='./orders.php'>Customer Orders</a>";
?>
</footer>

