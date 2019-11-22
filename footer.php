<br>
<footer class="footer">
<?php
$cookie_name1 = "CS405_Username";
$cookie_name2 = "CS405_Usertype";
$uType = strtolower($_COOKIE[$cookie_name2]);
if($uType == "admin")
    echo '<a href="./employeeLogin.php">Employee Login Page</a>';
if($uType != "customer")
    echo '<a href="./homepage.php">Customer Login Page</a>';
else
    echo '<a href="./employeeLogin.php">Employee Login Page</a>';
if($uType == "admin")
    echo "<a href='./dbQuery.php'>Database Query</a>";
if($uType == "manager" || $uType == "admin")
    echo "<a href='./createEmployee.php'>Employee Account Create</a>";
if($uType != "customer")
    echo "<a href='./inventory.php'>Inventory Management</a>";
if($uType != "customer")
    echo "<a href='./orders.php'>Customer Orders</a>";
?>
</footer>

