<?php
include("../config/db.php");
session_start();

$id = $_GET['id'];
$sql = "SELECT * FROM orders WHERE id='$id'";
$res = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($res);

$sql2 = "SELECT oi.*, p.name FROM order_items oi 
         JOIN products p ON oi.product_id = p.id 
         WHERE oi.order_id='$id'";
$items = mysqli_query($conn, $sql2);
?>
<!DOCTYPE html>
<html>
<head><title>Order Details</title></head>
<body>
<h1>Order #<?= $order['id'] ?></h1>
<p>Status: <?= $order['status'] ?></p>
<p>Total: <?= $order['total_amount'] ?></p>
<p>Date: <?= $order['created_at'] ?></p>

<h2>Items</h2>
<table border="1">
    <tr><th>Product</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr>
    <?php while($row = mysqli_fetch_assoc($items)) { ?>
    <tr>
        <td><?= $row['name'] ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= $row['price_each'] ?></td>
        <td><?= $row['subtotal'] ?></td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
