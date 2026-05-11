<?php
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "Priyanshu@123";
$dbName = "login_register";

$connection = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if(!$connection){
    die("Something went wrong.");
}
?>