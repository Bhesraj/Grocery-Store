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
}
.overlay {
    background-color: rgba(255, 255, 255, 0.85);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 24px;
}
.container {
    width: 100%;
    max-width: 900px;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    text-align: center;
}
h2 { color: #2c3e50; margin-bottom: 10px; }
h3 { color: #34495e; margin: 20px 0 10px; }
ul { list-style: none; padding: 0; display: flex; flex-wrap: wrap; justify-content: center; }
ul li { margin: 10px; }
a.button {
    display: inline-block;
    padding: 12px 25px;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    color: #fff;
    transition: background 0.3s;
}
.shopkeeper { background: #27ae60; }
.shopkeeper:hover { background: #219150; }
.customer { background: #3498db; }
.customer:hover { background: #2980b9; }
.logout { background: #e74c3c; margin-top: 20px; }
.logout:hover { background: #c0392b; }
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
                <li><a href="products.php" class="button customer">Browse Products</a></li>
                <li><a href="cart.php" class="button customer">View Cart</a></li>
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
