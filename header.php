<?php

class headerBar {
function __construct($title) {
    $cookie_name1 = "CS405_Username";
    $cookie_name2 = "CS405_Usertype";
    if(strtolower($_COOKIE[$cookie_name2]) == "customer"){
    	echo '<h1><a class="left" href="./loggedIn.php">Home</a>';
    	echo $title;
    	echo '<a class="right" href="./homepage.php">Logout</a></h1>';
    }
    else if(strtolower($_COOKIE[$cookie_name2]) == "manager" or strtolower($_COOKIE[$cookie_name2]) == "staff"){
        echo '<h1><a class="left" href="./loggedIn.php">Home</a>';
        echo $title;
        echo '<a class="right" href="./employeeLogin.php">Logout</a></h1>';
    }
    else
	header("Location: ./homepage.php");

}
}
?>
