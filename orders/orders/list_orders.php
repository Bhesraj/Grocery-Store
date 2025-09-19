<?php
include("../config/db.php");
session_start();

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['is_admin'] ?? false;

if ($is_admin) {
    $sql = "SELECT * FROM orders ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
} else {
    $sql = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html>
<head><title>Orders</title></head>
<body>
<h1>Orders</h1>
<table border="1">
    <tr><th>ID</th><th>User</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['user_id'] ?></td>
        <td><?= $row['total_amount'] ?></td>
        <td><?= $row['status'] ?></td>
        <td><?= $row['created_at'] ?></td>
        <td><a href="view_order.php?id=<?= $row['id'] ?>">View</a></td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
