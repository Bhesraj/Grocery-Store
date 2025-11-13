<?php
session_start();
include('config/db.php');

// Only allow logged-in customers
if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch all products
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add to cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    $message = "✅ Product added to cart!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Browse Products - FreshMart</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
    font-family: Arial, sans-serif;
    background: url('images/grocery-bg.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #333;
    margin: 0;
    padding: 0;
}
.overlay {
    background-color: rgba(255,255,255,0.9);
    min-height: 100vh;
    padding: 40px 20px;
}
.container {
    max-width: 1000px;
    margin: 0 auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { border:1px solid #ccc; padding: 10px; text-align:left; }
th { background: #3498db; color: #fff; }
input[type=number] { width:60px; padding:5px; }
button { padding:5px 10px; background:#27ae60; color:#fff; border:none; border-radius:5px; cursor:pointer; }
button:hover { background:#219150; }
.message { margin-bottom: 15px; font-weight:bold; color:green; }
a.back { display:inline-block; margin-top: 15px; text-decoration:none; color:#2980b9; font-weight:bold; }
a.back:hover { text-decoration:underline; }
</style>
</head>
<body>
<div class="overlay">
<div class="container">
<h2>Welcome, <?= htmlspecialchars($username); ?>! Browse Products</h2>

<?php if(!empty($message)): ?>
    <div class="message"><?= htmlspecialchars($message); ?></div>
<?php endif; ?>

<table>
<tr>
<th>ID</th>
<th>Product Name</th>
<th>Price</th>
<th>Stock</th>
<th>Quantity</th>
<th>Add to Cart</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['id']); ?></td>
    <td><?= htmlspecialchars($row['product_name']); ?></td>
    <td>₹ <?= htmlspecialchars($row['price']); ?></td>
    <td><?= htmlspecialchars($row['stock']); ?></td>
    <form method="POST">
    <td>
        <input type="number" name="quantity" value="1" min="1" max="<?= $row['stock']; ?>" required>
        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
    </td>
    <td><button type="submit">Add to Cart</button></td>
    </form>
</tr>
<?php endwhile; ?>

</table>

<a href="dashboard.php" class="back">← Back to Dashboard</a>
</div>
</div>
</body>
</html>
