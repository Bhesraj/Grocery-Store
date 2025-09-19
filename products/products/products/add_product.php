<?php
include("../config/db.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $qty = $_POST['quantity'];

    $sql = "INSERT INTO products (name, description, price, quantity) VALUES ('$name', '$desc', '$price', '$qty')";
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
<head><title>Add Product</title></head>
<body>
<h1>Add New Product</h1>
<form method="post">
    <label>Name:</label><br>
    <input type="text" name="name" required><br>
    <label>Description:</label><br>
    <textarea name="description"></textarea><br>
    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" required><br>
    <label>Quantity:</label><br>
    <input type="number" name="quantity" required><br>
    <input type="submit" value="Add">
</form>
</body>
</html>
