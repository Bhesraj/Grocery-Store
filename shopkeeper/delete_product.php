<?php
session_start();

// ✅ Ensure user is logged in and is a shopkeeper
if (!isset($_SESSION['user_id'], $_SESSION['user_type'], $_SESSION['username']) 
    || $_SESSION['user_type'] !== 'shopkeeper') {
    header("Location: ../login.php");
    exit();
}

include('../config/db.php');

$product_id = $_GET['id'] ?? null;

if ($product_id) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to manage products
header("Location: manage_products.php");
exit();
