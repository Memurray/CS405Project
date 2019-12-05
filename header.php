<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<?php
function headerBar($title,$permission) {
    $cookie_name1 = "CS405_Username";
    $cookie_name2 = "CS405_Usertype";
    $user_type = strtolower($_COOKIE[$cookie_name2]);
    $uName = $_COOKIE[$cookie_name1];

    if($user_type != "admin"){
    	if($permission == "manager" AND $user_type == "staff")
            header("Location: ./loggedIn.php");
	if($permission == "manager" AND $user_type == "customer")
            header("Location: ./storefront.php");
    	if($permission == "staff" AND $user_type == "customer")
            header("Location: ./storefront.php");
	if($permission == "customer" AND $user_type != "customer")
            header("Location: ./loggedIn.php");
    }

    if($user_type == "customer" or $user_type == "admin"){
    	echo '<h1><a class="left outer" href="./storefront.php">Home</a>';
	echo '<a class="left" href="./cart.php">Cart</a>'; 
   	echo $title;
    	echo '<a class="right outer" href="./homepage.php" onclick="logout()">Logout</a>';
        echo '<a class="right" href="./customerOrders.php">' . $uName . "'s Orders</a>";
	if($user_type == "admin"){
	echo '<label class="dropdown">
  		<a class="dropbtn">Staff Options 
      		<i class="fa fa-caret-down"></i></a>
    		<div class="dropdown-content">';
	if($user_type == "admin")
    	  	echo "<a href='./dbQuery.php'>Database Query</a>";
	if($user_type == "manager" || $user_type == "admin")
    		echo "<a href='./createEmployee.php'>Employee Account Create</a>";
	if($user_type != "customer")
    		echo "<a href='./inventory.php'>Inventory Management</a>";
	if($user_type != "customer")
    		echo "<a href='./orders.php'>Customer Orders</a>";
    	echo '</div></label>';
	}
	echo '</h1>';

    }
    else if($user_type == "manager" or $user_type == "staff"){
        echo '<h1><a class="left outer" href="./loggedIn.php">Home</a>';
        echo $title;
        echo '<a class="right outer" href="./employeeLogin.php" onclick="logout()">Logout</a>';
	echo '<label class="dropdown">
                <a class="dropbtn">Staff Options
                <i class="fa fa-caret-down"></i></a>
                <div class="dropdown-content">';
        if($user_type == "admin")
                echo "<a href='./dbQuery.php'>Database Query</a>";
        if($user_type == "manager" || $user_type == "admin")
                echo "<a href='./createEmployee.php'>Employee Account Create</a>";
        if($user_type != "customer")
                echo "<a href='./inventory.php'>Inventory Management</a>";
        if($user_type != "customer")
                echo "<a href='./orders.php'>Customer Orders</a>";
        echo '</div></label>';
        echo '</h1>';
    }
    else
	header("Location: ./homepage.php");
}
?>

<script>
function logout(){
        document.cookie = "CS405_Username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "CS405_Usertype=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }

</script>

