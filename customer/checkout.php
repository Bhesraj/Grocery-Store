<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['type'] !== 'customer'){
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    die("Please log in first.");
}

if (empty($_SESSION['cart'])) {
    die("Your cart is empty.");
}

$user_id = $_SESSION['user_id'];
$total_amount = 0;

// Calculate total
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

// Insert order into existing orders table
$sql_order = "INSERT INTO orders (user_id, total_amount, status, created_at)
              VALUES ('$user_id', '$total_amount', 'Pending', NOW())";

if (mysqli_query($conn, $sql_order)) {
    $order_id = mysqli_insert_id($conn);

    // Insert items into order_items
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['id'];
        $quantity = $item['quantity'];
        $price_each = $item['price'];
        $subtotal = $quantity * $price_each;

        $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, price_each, subtotal)
                     VALUES ('$order_id', '$product_id', '$quantity', '$price_each', '$subtotal')";
        mysqli_query($conn, $sql_item);
    }

    unset($_SESSION['cart']);
    echo "<h2>✅ Order placed successfully!</h2>";
    echo "<a href='../orders/list_orders.php'>View Orders</a>";
} else {
    echo "Error placing order: " . mysqli_error($conn);
}
?>
