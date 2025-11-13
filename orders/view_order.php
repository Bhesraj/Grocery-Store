<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'], $_SESSION['user_type'])) {
    header("Location: login.php");
    exit();
}

$order_id = $_GET['id'] ?? null;
$user_id  = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

if (!$order_id) {
    die("Order ID is missing.");
}

// Fetch order
$sql = "SELECT o.*, u.username FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

// Check if order exists
if (!$order) {
    die("Order not found.");
}

// Customers can only view their own orders
if ($user_type === 'customer' && $order['user_id'] != $user_id) {
    die("You are not authorized to view this order.");
}

// Fetch order items
$sql2 = "SELECT oi.*, p.name FROM order_items oi 
         JOIN products p ON oi.product_id = p.id 
         WHERE oi.order_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $order_id);
$stmt2->execute();
$items_result = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order #<?= $order['id']; ?></title>
<style>
body { font-family: Arial,sans-serif; background:#f8f9fa; padding:40px; }
h1, h2 { color:#2c3e50; }
table { width:100%; border-collapse:collapse; margin-top:20px; }
table, th, td { border:1px solid #ccc; }
th, td { padding:10px; text-align:left; }
th { background:#3498db; color:#fff; }
a.button { padding:5px 10px; text-decoration:none; border-radius:4px; color:#fff; background:#7f8c8d; }
a.button:hover { background:#34495e; }
</style>
</head>
<body>

<h1>Order #<?= $order['id']; ?></h1>
<p><strong>Customer:</strong> <?= htmlspecialchars($order['username']); ?></p>
<p><strong>Status:</strong> <?= htmlspecialchars($order['status']); ?></p>
<p><strong>Total:</strong> <?= htmlspecialchars($order['total_amount']); ?></p>
<p><strong>Date:</strong> <?= htmlspecialchars($order['created_at']); ?></p>

<h2>Items</h2>
<table>
    <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price Each</th>
        <th>Subtotal</th>
    </tr>
    <?php while($item = $items_result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($item['name']); ?></td>
        <td><?= htmlspecialchars($item['quantity']); ?></td>
        <td><?= htmlspecialchars($item['price_each']); ?></td>
        <td><?= htmlspecialchars($item['subtotal']); ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<p><a href="<?= $user_type === 'shopkeeper' ? 'shopkeeper/orders.php' : 'orders/list_orders.php'; ?>" class="button">Back</a></p>

</body>
</html>
        