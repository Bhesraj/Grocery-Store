<?php
// ✅ Correct way to include your database connection
include(__DIR__ . '/../config/db.php'); // Adjust path if needed

// Run SQL to fetch products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Products | FreshMart 🥦</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1603046891741-944cbca5f1f1') no-repeat center center/cover;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }
        table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        th {
            background-color: #27ae60 !important;
            color: white;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .btn-back:hover {
            background-color: #219150;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🥬 FreshMart Product Catalog</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered table-striped text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price (Rs.)</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']); ?></td>
                        <td><?= htmlspecialchars($row['product_name']); ?></td>
                        <td><?= number_format($row['price'], 2); ?></td>
                        <td><?= htmlspecialchars($row['stock']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning text-center">No products found in the database.</div>
    <?php endif; ?>

    <div class="text-center">
        <a href="../index.php" class="btn-back">⬅ Back to Dashboard</a>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
