<?php
session_start();
include("../config/db.php"); // Adjust path if needed

// Allow only customers
if (!isset($_SESSION['user_id'], $_SESSION['user_type'], $_SESSION['username']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    $_SESSION['message'] = "✅ Product added to cart!";
    header("Location: products.php");
    exit();
}

// Fetch all products
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);
if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Browse Products - FreshMart</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
* {
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
body {
    margin: 0;
    background: linear-gradient(to bottom right, #e8f5e9, #f1f8e9);
    color: #333;
}
.container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 20px;
}
h2 {
    text-align: center;
    color: #2e7d32;
    font-size: 2em;
    margin-bottom: 30px;
}
.message {
    text-align: center;
    font-weight: bold;
    color: #388e3c;
    margin-bottom: 20px;
}
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}
.product-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}
.product-image {
    width: 100%;
    height: 180px;
    background: #c8e6c9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2e7d32;
    font-size: 18px;
    font-weight: 600;
}
.product-details {
    padding: 15px;
}
.product-details h3 {
    margin: 0 0 10px;
    font-size: 1.2em;
    color: #2e7d32;
}
.price {
    font-weight: bold;
    color: #388e3c;
    margin-bottom: 8px;
}
.stock {
    font-size: 0.9em;
    color: #555;
    margin-bottom: 12px;
}
form {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
input[type=number] {
    width: 60px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 6px;
}
button {
    background-color: #43a047;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 6px 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
button:hover {
    background-color: #2e7d32;
}
a.back {
    display: block;
    margin-top: 30px;
    text-align: center;
    text-decoration: none;
    color: #1b5e20;
    font-weight: bold;
}
a.back:hover {
    text-decoration: underline;
}
</style>
</head>
<body>
<div class="container">
    <h2>Welcome, <?= htmlspecialchars($username); ?>! Browse Products</h2>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="message"><?= htmlspecialchars($_SESSION['message']); ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="products-grid">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="product-card">
                <div class="product-image">
                    <?= htmlspecialchars($row['product_name'][0]); ?>
                </div>
                <div class="product-details">
                    <h3><?= htmlspecialchars($row['product_name']); ?></h3>
                    <div class="price">₹ <?= htmlspecialchars($row['price']); ?></div>
                    <div class="stock">Stock: <?= htmlspecialchars($row['stock']); ?></div>

                    <form method="POST">
                        <input type="number" name="quantity" value="1" min="1" max="<?= $row['stock']; ?>" required>
                        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

   <a href="../dashboard.php" class="back">← Back to Dashboard</a>
</div>
</body>
</html>
