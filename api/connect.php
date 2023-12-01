<?php
include 'customFunctions.php';
// Database configuration
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "mis_db";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
