<?php
$host = "localhost"; // or your XAMPP server
$user = "root";      // default user for XAMPP
$password = "";      // default password is empty in XAMPP
$dbname = "groceries_db"; // your database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
