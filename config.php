<?php
$host = 'localhost'; 
$user = 'root';
$pass = '';
$db = 'vimal';

// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);s
$con = mysqli_connect($host, $user, $pass, $db);


// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
