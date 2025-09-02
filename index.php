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
        <?php else: ?><!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('images/grocery-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        .overlay {
            background-color: rgba(255, 255, 255, 0.85);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 800px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        h3 {
            color: #34495e;
            margin-top: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            margin: 10px 0;
        }

        ul li a {
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
        }

        ul li a:hover {
            text-decoration: underline;
            color: #2980b9;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="overlay">
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
    </div>
</body>
</html>
