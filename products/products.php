<?php
session_start();
include(__DIR__ . '/../config/db.php');

// Fetch products from database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Products | FreshMart 🥦</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 p-4 bg-white rounded shadow">
    <h2 class="text-center mb-4">🛒 Browse Products</h2>

    <!-- Cart Shortcut -->
    <div class="text-end mb-3">
        <a href="view_cart.php" class="btn btn-primary">View Cart 🛍️</a>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered text-center align-middle">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Price (Rs.)</th>
                    <th>Stock</th>
                    <th>Add to Cart</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']); ?></td>
                        <td><?= htmlspecialchars($row['product_name']); ?></td>
                        <td><?= number_format($row['price'], 2); ?></td>
                        <td><?= htmlspecialchars($row['stock']); ?></td>
                        <td>
                            <!-- ✅ Add to Cart Form -->
                            <form method="POST" action="add_to_cart.php" class="d-flex flex-column align-items-center">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($row['product_name']); ?>">
                                <input type="hidden" name="price" value="<?= $row['price']; ?>">
                                <input type="number" name="quantity" value="1" min="1"
                                       class="form-control mb-2 text-center" style="width:80px;">
                                <button type="submit" class="btn btn-success btn-sm">🛒 Add</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning text-center">No products found.</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="../index.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
