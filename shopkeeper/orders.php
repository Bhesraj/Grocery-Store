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
<style>
body {
    font-family: Arial, sans-serif;
    background: url('../images/grocery-bg.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #333;
}
.overlay {
    background-color: rgba(255,255,255,0.85);
    min-height:100vh;
    display:flex;
    justify-content:center;
    padding:40px 20px;
}
.container {
    width:100%;
    max-width:1000px;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.2);
}
h2 { color:#2c3e50; margin-bottom:20px; text-align:center; }
a.button {
    display:inline-block;
    padding:8px 15px;
    margin:5px;
    border-radius:6px;
    text-decoration:none;
    color:#fff;
    font-weight:bold;
}
.view { background:#27ae60; }
.view:hover { background:#219150; }
.back { background:#2980b9; }
.back:hover { background:#2471a3; }
table { width:100%; border-collapse:collapse; margin-top:20px; }
th, td { border:1px solid #ccc; padding:10px; text-align:left; }
th { background:#3498db; color:#fff; }
</style>
</head>
<body>

<div class="overlay">
<div class="container">
<h2>All Orders</h2>

<a href="../dashboard.php" class="button back">🏠 Back to Dashboard</a>

<table>
<tr>
<th>ID</th>
<th>Customer</th>
<th>Total</th>
<th>Status</th>
<th>Date</th>
<th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['id']); ?></td>
    <td><?= htmlspecialchars($row['username']); ?></td>
    <td><?= htmlspecialchars($row['total_amount']); ?></td>
    <td><?= htmlspecialchars($row['status']); ?></td>
    <td><?= htmlspecialchars($row['created_at']); ?></td>
    <td>
        <a href="../view_orders.php?id=<?= $row['id']; ?>" class="button view">View</a>
    </td>
</tr>
<?php endwhile; ?>

</table>
</div>
</div>
</body>
</html>

