<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include DB connection if needed
include __DIR__ . '/config/db.php';

// Get user info from session
$username = $_SESSION['username'];
$type = $_SESSION['type'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        .logout-btn {
            background-color: #ff4d4d;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>You are logged in as <strong><?php echo htmlspecialchars($type); ?></strong>.</p>

        <?php if ($type === 'shopkeeper'): ?>
            <h3>Shopkeeper Panel</h3>
            <ul>
                <li><a href="shopkeeper/manage_products.php">Manage Products</a></li>
                <li><a href="shopkeeper/orders.php">View Orders</a></li>
            </ul>
        <?php else: ?>
            <h3>Customer Panel</h3>
            <ul>
                <li><a href="products.php">Browse Products</a></li>
                <li><a href="my_orders.php">My Orders</a></li>
            </ul>
        <?php endif; ?>

        <form action="logout.php" method="POST">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</body>
</html>
 