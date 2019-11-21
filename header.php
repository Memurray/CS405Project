<?php
function headerBar($title,$permission) {
    $cookie_name1 = "CS405_Username";
    $cookie_name2 = "CS405_Usertype";
    $user_type = strtolower($_COOKIE[$cookie_name2]);
    $uName = $_COOKIE[$cookie_name1];

    if($user_type != "admin"){
    	if($permission == "manager" AND $user_type != "manager")
            header("Location: ./loggedIn.php");
    	if($permission == "staff" AND $user_type == "customer")
            header("Location: ./shop.php");
	if($permission == "customer" AND $user_type != "customer")
            header("Location: ./loggedIn.php");
    }

    if($user_type == "customer" or $user_type == "admin"){
    	echo '<h1><a class="left outer" href="./shop.php">Home</a>';
	echo '<a class="left" href="./cart.php">Cart</a>'; 
   	echo $title;
    	echo '<a class="right outer" href="./homepage.php" onclick="logout()">Logout</a>';
        echo '<a class="right" href="./customerOrders.php">' . $uName . "'s Orders</a></h1>";
    }
    else if($user_type == "manager" or $user_type == "staff"){
        echo '<h1><a class="left" href="./loggedIn.php">Home</a>';
        echo $title;
        echo '<a class="right" href="./employeeLogin.php" onclick="logout()">Logout</a></h1>';
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

