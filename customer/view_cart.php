<?php
session_start();
include("../config/db.php");

// Only allow customers
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];
$total = 0;
$products_in_cart = [];

// Fetch product details if cart is not empty
$product_ids = array_filter(array_keys($cart), 'is_numeric');

if (!empty($product_ids)) {
    $ids = implode(',', $product_ids);
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $products_in_cart[$row['id']] = $row;
        }
    } else {
        die("Database query failed: " . $conn->error);
    }
}

// Handle Place Order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    if (empty($cart)) {
        $_SESSION['message'] = "Your cart is empty!";
        header("Location: view_cart.php");
        exit();
    }

    // Calculate total amount
    $total_amount = 0;
    foreach ($cart as $id => $qty) {
        if (!isset($products_in_cart[$id])) continue;
        $total_amount += $products_in_cart[$id]['price'] * $qty;
    }

    $order_date = date('Y-m-d');
    $created_at = date('Y-m-d H:i:s');

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, total_amount, order_date, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $user_id, $username, $total_amount, $order_date, $created_at);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert order items
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_each, subtotal) VALUES (?, ?, ?, ?, ?)");
    foreach ($cart as $id => $qty) {
        if (!isset($products_in_cart[$id])) continue;
        $product = $products_in_cart[$id];
        $price_each = $product['price'];
        $subtotal = $price_each * $qty;
        $stmt_item->bind_param("iiidd", $order_id, $id, $qty, $price_each, $subtotal);
        $stmt_item->execute();
    }

    unset($_SESSION['cart']); // Clear cart
    header("Location: ../orders/list_orders.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Cart - FreshMart</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 bg-white p-4 rounded shadow">
    <h2 class="text-center mb-4">🛒 <?= htmlspecialchars($username); ?>'s Cart</h2>

    <?php if (!empty($cart) && !empty($products_in_cart)): ?>
        <form method="POST">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-success">
                    <tr>
                        <th>Product</th>
                        <th>Price (Rs.)</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($cart as $id => $qty):
                    if (!isset($products_in_cart[$id])) continue;
                    $product = $products_in_cart[$id];
                    $subtotal = $product['price'] * $qty;
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($product['product_name']); ?></td>
                        <td>₹ <?= number_format($product['price'], 2); ?></td>
                        <td><?= $qty; ?></td>
                        <td>₹ <?= number_format($subtotal, 2); ?></td>
                        <td>
                            <a href="remove_cart.php?id=<?= urlencode($id); ?>" class="btn btn-outline-danger btn-sm"
                               onclick="return confirm('Remove this item?');">❌ Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="text-end">
                <h4>Total: ₹ <?= number_format($total, 2); ?></h4>
                <button type="submit" name="place_order" class="btn btn-success mt-3">✅ Place Order</button>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            Your cart is empty! <a href="products.php" class="btn btn-sm btn-primary ms-2">Browse Products</a>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="../dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</div>
</body>
</html>
