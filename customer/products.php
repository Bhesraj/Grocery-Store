<?php
session_start();
include("../config/db.php");
include("recommend.php"); // CBF integration

// Allow only customers
if (!isset($_SESSION['user_id'], $_SESSION['user_type']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    header("Location: products.php?added=1");
    exit();
}

// Fetch products
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$sql = "SELECT id, product_name, price, image FROM products WHERE product_name LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// ---- CBF Logic ----
$recommendations = [];
$view_product_id = isset($_GET['view_id']) ? intval($_GET['view_id']) : 0;

if ($view_product_id > 0) {
    $recommendations = getRecommendations($view_product_id, $conn, 4);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Browse Products</title>

<style>
    body {
        margin: 0;
        font-family: Arial;
        background: url('../images/grocery-fresh-bg.jpg') no-repeat center center/cover;
        backdrop-filter: blur(4px);
    }

    .container {
        width: 90%;
        margin: 20px auto;
        background: rgba(255,255,255,0.8);
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    h2 {
        text-align: center;
        margin-bottom: 15px;
        color: #2a7a2e;
        font-size: 30px;
    }

    .search-box {
        text-align: center;
        margin-bottom: 20px;
    }

    .search-box input {
        width: 50%;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #aaa;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 15px;
    }

    .product-card {
        background: white;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        text-align: center;
        transition: 0.3s;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 14px rgba(0,0,0,0.2);
    }

    .product-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 10px;
    }

    .price {
        font-size: 20px;
        font-weight: bold;
        color: #2a7a2e;
        margin-top: 10px;
    }

    .add-btn {
        margin-top: 10px;
        background: #2a7a2e;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
    }

    .add-btn:hover {
        background: #1e5f21;
    }

    .back-btn {
        background: #333;
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        text-decoration: none;
    }

    .alert {
        text-align: center;
        padding: 10px;
        background: #c7f5c8;
        color: #2a7a2e;
        border-radius: 8px;
        margin-bottom: 20px;
    }
</style>
</head>

<body>

<div class="container">

    <a href="../dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

    <h2>Browse Products</h2>

    <?php if (isset($_GET['added'])): ?>
        <div class="alert">✔ Product added to cart!</div>
    <?php endif; ?>

    <div class="search-box">
        <form method="GET">
            <input type="text" name="search" placeholder="Search for products..." value="<?= htmlspecialchars($search); ?>">
            <button class="add-btn" style="width:150px;">Search</button>
        </form>
    </div>

    <!-- CBF Recommendation Section -->
    <?php if (!empty($recommendations)): ?>
        <h3 style="color:#2a7a2e;">Similar Products</h3>

        <div class="product-grid">
            <?php foreach ($recommendations as $item): ?>
                <?php $p = $item['product']; ?>
                <div class="product-card">
                    <img src="../uploads/<?= $p['image']; ?>" alt="Product">
                    <h3><?= htmlspecialchars($p['product_name']); ?></h3>
                    <p class="price">Rs. <?= number_format($p['price']); ?></p>
                    <p style="font-size:14px;color:#666;">
                        Similarity: <?= round($item['similarity'], 1); ?>%
                    </p>
                    <a href="products.php?view_id=<?= $p['id']; ?>" class="add-btn" style="text-decoration:none;">
                        View Similar
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Main Product List -->
    <div class="product-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-card">
                <img src="../uploads/<?= $row['image']; ?>" alt="Product">

                <h3><?= htmlspecialchars($row['product_name']); ?></h3>
                <p class="price">Rs. <?= number_format($row['price']); ?></p>

                <a href="products.php?view_id=<?= $row['id']; ?>" 
                   style="display:block;margin:8px 0;color:#2a7a2e;font-weight:bold;">
                    View Similar Products
                </a>

                <form method="POST">
                    <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                    <input type="number" name="quantity" value="1" min="1" style="width:60px; padding:5px;">
                    <button type="submit" name="add_to_cart" class="add-btn">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

</div>

</body>
</html>
