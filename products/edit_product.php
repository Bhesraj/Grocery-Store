<?php
include("../config/db.php");
session_start();

$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id='$id'";
$res = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($res);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $qty = $_POST['quantity'];

    $sql = "UPDATE products SET name='$name', description='$desc', price='$price', quantity='$qty' WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: list_products.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Product</title></head>
<body>
<h1>Edit Product</h1>
<form method="post">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?= $product['name'] ?>" required><br>
    <label>Description:</label><br>
    <textarea name="description"><?= $product['description'] ?></textarea><br>
    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required><br>
    <label>Quantity:</label><br>
    <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required><br>
    <input type="submit" value="Update">
</form>
</body>
</html>
