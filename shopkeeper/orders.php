<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'shopkeeper') {
    header("Location: ../login.php");
    exit();
}

include('../config/db.php');

// Fetch all orders
$sql = "SELECT o.id, o.total_amount, o.status, o.created_at, u.username 
        FROM orders o 
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Shopkeeper Orders</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    font-family: Arial, sans-serif;
    background: url('../images/grocery-bg.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #333;
}
.overlay {
    background-color: rgba(255,255,255,0.9);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    padding: 40px 20px;
}
.container {
    width: 100%;
    max-width: 1200px;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
h2 { color: #2c3e50; text-align: center; margin-bottom: 20px; }
a.button {
    display: inline-block;
    padding: 8px 15px;
    margin: 5px;
    border-radius: 6px;
    text-decoration: none;
    color: #fff;
    font-weight: bold;
}
.back { background: #2980b9; }
.back:hover { background: #2471a3; }
.view { background: #27ae60; }
.view:hover { background: #219150; }
table th { background: #27ae60; color: #fff; }
table td, table th { padding: 10px; text-align: center; }
@media (max-width: 768px) {
    table, thead, tbody, th, td, tr { display: block; }
    tr { margin-bottom: 15px; }
    th { text-align: left; }
    td { text-align: right; padding-left: 50%; position: relative; }
    td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        font-weight: bold;
        text-align: left;
    }
}
</style>
</head>
<body>
<div class="overlay">
<div class="container">
<h2>All Orders</h2>

<a href="../dashboard.php" class="button back">🏠 Back to Dashboard</a>

<table class="table table-bordered table-hover mt-3">
<thead>
<tr>
<th>ID</th>
<th>Customer</th>
<th>Total (Rs.)</th>
<th>Status</th>
<th>Date</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td data-label="ID"><?= htmlspecialchars($row['id']); ?></td>
    <td data-label="Customer"><?= htmlspecialchars($row['username']); ?></td>
    <td data-label="Total"><?= htmlspecialchars($row['total_amount']); ?></td>
    <td data-label="Status"><?= htmlspecialchars($row['status']); ?></td>
    <td data-label="Date"><?= htmlspecialchars($row['created_at']); ?></td>
    <td data-label="Actions">
        <a href="../view_orders.php?id=<?= $row['id']; ?>" class="button view">View</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>
</body>
</html>
