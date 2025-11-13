<?php
session_start(); // Make sure session is started first
include("../config/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Example: replace this with your actual cart total calculation
$total_amount = 500;

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, 'Pending', NOW())");
$stmt->bind_param("id", $user_id, $total_amount);

if ($stmt->execute()) {
    // Successfully placed order
    header("Location: orders.php");
    exit();
} else {
    echo "Error placing order: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
