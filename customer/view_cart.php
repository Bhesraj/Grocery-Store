<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['type'] !== 'customer'){
    header("Location: ../login.php");
    exit();
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;

// When user places order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $customer_name = $_SESSION['username'] ?? 'Guest';

    // Create new order
    $conn->query("INSERT INTO orders (customer_name) VALUES ('$customer_name')");
    $order_id = $conn->insert_id;

    // Save items
    foreach ($cart as $id => $item) {
        $quantity = $item['quantity'];
        $price_each = $item['price'];
        $subtotal = $quantity * $price_each;

        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price_each, subtotal)
                      VALUES ('$order_id', '$id', '$quantity', '$price_each', '$subtotal')");
    }

    unset($_SESSION['cart']); // clear cart
    header("Location: ../orders/list_orders.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 bg-white p-4 rounded shadow">
    <h2 class="text-center mb-4">🛒 Your Cart</h2>

    <?php if (!empty($cart)): ?>
        <form method="POST">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-success">
                    <tr>
                        <th>Product</th>
                        <th>Price (Rs.)</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th> <!-- ✅ Added column for remove -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $id => $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td><?= number_format($item['price'], 2); ?></td>
                            <td><?= $item['quantity']; ?></td>
                            <td><?= number_format($subtotal, 2); ?></td>
                            <td>
                                <!-- ✅ Remove button -->
                                <a href="remove_cart.php?id=<?= urlencode($id); ?>" 
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to remove this item?');">
                                    ❌ Remove
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="text-end">
                <h4>Total: Rs. <?= number_format($total, 2); ?></h4>
                <button type="submit" name="place_order" class="btn btn-success mt-3">✅ Place Order</button>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-warning text-center">Your cart is empty!</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="products.php" class="btn btn-secondary">⬅ Continue Shopping</a>
    </div>
</div>
</body>
</html>
