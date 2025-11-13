<?php
session_start();

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'], $_SESSION['user_type'], $_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username  = $_SESSION['username'];
$user_type = $_SESSION['user_type']; // 'shopkeeper' or 'customer'
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* { box-sizing: border-box; margin:0; padding:0; }
body {
    font-family: Arial, sans-serif;
    background: url('images/grocery-bg.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #333;
    min-height: 100vh;
}

/* Overlay for better readability */
.overlay {
    background-color: rgba(0, 0, 0, 0.5); /* dark semi-transparent overlay */
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 24px;
}

/* Central container */
.container {
    width: 100%;
    max-width: 900px;
    background: rgba(255, 255, 255, 0.95); /* slightly transparent */
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    text-align: center;
    backdrop-filter: blur(5px); /* nice blur effect behind container */
}

h2 { color: #2c3e50; margin-bottom: 10px; }
h3 { color: #34495e; margin: 20px 0 10px; }

ul {
    list-style: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

ul li { margin: 10px; }

a.button {
    display: inline-block;
    padding: 14px 28px;
    text-decoration: none;
    border-radius: 12px;
    font-weight: bold;
    color: #fff;
    transition: transform 0.3s, background 0.3s, box-shadow 0.3s;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    font-size: 16px;
}

/* Shopkeeper buttons */
.shopkeeper { background: #27ae60; }
.shopkeeper:hover {
    background: #219150;
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.3);
}

/* Customer buttons */
.customer { background: #3498db; }
.customer:hover {
    background: #2980b9;
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.3);
}

/* Logout button */
.logout {
    background: #e74c3c;
    margin-top: 20px;
}
.logout:hover {
    background: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.25);
}
</style>
</head>
<body>

<div class="overlay">
    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($username); ?> 👋</h2>
        <p>You are logged in as <strong><?= htmlspecialchars($user_type); ?></strong>.</p>

        <?php if ($user_type === 'shopkeeper'): ?>
            <h3>Shopkeeper Panel</h3>
            <ul>
                <li><a href="shopkeeper/manage_products.php" class="button shopkeeper">Manage Products</a></li>
                <li><a href="shopkeeper/add_product.php" class="button shopkeeper">Add Product</a></li>
                <li><a href="shopkeeper/orders.php" class="button shopkeeper">View Orders</a></li>
            </ul>
        <?php elseif ($user_type === 'customer'): ?>
            <h3>Customer Panel</h3>
            <ul>
                <li><a href="customer/products.php" class="button customer">Browse Products</a></li>
                <li><a href="customer/view_cart.php" class="button customer">🛒 View Cart</a></li>
                <li><a href="orders/list_orders.php" class="button customer">My Orders</a></li>
            </ul>
        <?php else: ?>
            <p style="color:red;">⚠️ Unknown role. Please contact admin.</p>
        <?php endif; ?>

        <form action="logout.php" method="POST">
            <button type="submit" class="button logout">Logout</button>
        </form>
    </div>
</div>

</body>
</html>
