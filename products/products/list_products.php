<?php
include("../config/db.php");
session_start();

// fetch products
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include("../navbar.php"); ?> <!-- if you have navbar -->
<h1>Product List</h1>
<a href="add_product.php">+ Add New Product</a>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th><th>Name</th><th>Price</th><th>Quantity</th><th>Actions</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= $row['price'] ?></td>
        <td><?= $row['quantity'] ?></td>
        <td>
            <a href="edit_product.php?id=<?= $row['id'] ?>">Edit</a> |
            <a href="delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
