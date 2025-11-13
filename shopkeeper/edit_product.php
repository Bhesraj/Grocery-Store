<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'shopkeeper') {
    header("Location: ../login.php");
    exit();
}

include('../config/db.php');

$message = '';
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    header("Location: manage_products.php");
    exit();
}

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    $message = "⚠️ Product not found.";
}

// Update product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);

    if ($product_name === '' || $price === '' || $stock === '') {
        $message = "⚠️ All fields are required.";
    } else {
        $stmt = $conn->prepare("UPDATE products SET product_name=?, price=?, stock=? WHERE id=?");
        $stmt->bind_param("sdii", $product_name, $price, $stock, $product_id);
        if ($stmt->execute()) {
            $message = "✅ Product updated successfully!";
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
<title>Edit Product</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
    font-family: Arial, sans-serif;
    background: url('../images/grocery-bg.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #333;
}
.overlay { background-color: rgba(255,255,255,0.85); min-height:100vh; display:flex; justify-content:center; align-items:center; padding:40px 20px; }
.container { width:100%; max-width:500px; background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.2); }
h2 { color:#2c3e50; margin-bottom:20px; text-align:center; }
input[type=text], input[type=number] { width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:8px; }
button { width:100%; padding:10px; background:#2980b9; color:#fff; border:none; border-radius:8px; font-weight:bold; cursor:pointer; }
button:hover { background:#2471a3; }
.message { margin-bottom:15px; font-weight:bold; color:red; }
a.back { display:inline-block; margin-top:10px; text-decoration:none; color:#27ae60; font-weight:bold; }
a.back:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="overlay">
<div class="container">
<h2>Edit Product</h2>

<?php if($message): ?>
    <div class="message"><?= htmlspecialchars($message); ?></div>
<?php endif; ?>

<form method="POST">
    <label>Product Name:</label>
    <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']); ?>" required>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']); ?>" required>

    <label>Stock:</label>
    <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']); ?>" required>

    <button type="submit">Update Product</button>
</form>

<a href="manage_products.php" class="back">← Back to Manage Products</a>
</div>
</div>
</body>
</html>
