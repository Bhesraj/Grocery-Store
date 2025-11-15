<?php
session_start();

// Only allow customers
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

// Remove product if id is passed and exists in cart
if (isset($_GET['id']) && isset($_SESSION['cart'][$_GET['id']])) {
    $product_id = $_GET['id'];
    unset($_SESSION['cart'][$product_id]);
}

// Redirect back to cart page
header("Location: view_cart.php");
exit();
?>
