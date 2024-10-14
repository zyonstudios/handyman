<?php
$servername = "localhost";
$username = "root";  // default MySQL username for XAMPP
$password = "";      // default MySQL password (empty by default in XAMPP)
$dbname = "handyman_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
