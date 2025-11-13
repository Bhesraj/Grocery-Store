<?php
session_start();

// ✅ Ensure user is logged in and is a shopkeeper
if (!isset($_SESSION['user_id'], $_SESSION['user_type'], $_SESSION['username']) 
    || $_SESSION['user_type'] !== 'shopkeeper') {
    header("Location: ../login.php");
    exit();
}

include('../config/db.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);

    if ($product_name === '' || $price === '' || $stock === '') {
        $message = "⚠️ All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (product_name, price, stock) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $product_name, $price, $stock);
        if ($stmt->execute()) {
            $message = "✅ Product added successfully!";
        } else {
            $message = "❌ Error: " . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
    font-family: Arial, sans-serif;
    background: url('../images/grocery-bg.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #333;
}
.overlay {
    background-color: rgba(255,255,255,0.85);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:40px 20px;
}
.container {
    width:100%;
    max-width:500px;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.2);
}
h2 { color:#2c3e50; margin-bottom:20px; text-align:center; }
input[type=text], input[type=number] {
    width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:8px;
}
button {
    width:100%; padding:10px; background:#27ae60; color:#fff; border:none; border-radius:8px; font-weight:bold; cursor:pointer;
}
button:hover { background:#219150; }
.message { margin-bottom:15px; font-weight:bold; color:red; }
a.back { display:inline-block; margin-top:10px; text-decoration:none; color:#2980b9; font-weight:bold; }
a.back:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="overlay">
<div class="container">
<h2>Add New Product</h2>

<?php if($message): ?>
    <div class="message"><?= htmlspecialchars($message); ?></div>
<?php endif; ?>

<form method="POST">
    <label>Product Name:</label>
    <input type="text" name="product_name" required>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" required>

    <label>Stock:</label>
    <input type="number" name="stock" required>

    <button type="submit">Add Product</button>
</form>

<a href="manage_products.php" class="back">← Back to Manage Products</a>
</div>
</div>

</body>
</html>
