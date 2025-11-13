<?php
session_start();
include("../config/db.php");

// Check login session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['is_admin'] ?? false;

// Fetch orders: all for admin, user-specific otherwise
if ($is_admin) {
    $sql = "SELECT o.*, u.name AS user_name 
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            ORDER BY o.id DESC";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT o.*, u.name AS user_name 
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            WHERE o.user_id = ? 
            ORDER BY o.id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders List - FreshMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .container {
            max-width: 900px;
        }
        .table thead {
            background-color: #27ae60;
            color: white;
        }
        .btn-primary {
            background-color: #27ae60;
            border-color: #27ae60;
        }
        .btn-primary:hover {
            background-color: #219150;
            border-color: #219150;
        }
    </style>
</head>
<body>
<div class="container mt-5 bg-white p-4 rounded shadow">
    <h2 class="text-center mb-4">📦 Orders List</h2>
    <table class="table table-bordered text-center align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Total (Rs.)</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['user_name'] ?? '—'); ?></td>
                    <td><?= htmlspecialchars($row['total_amount']); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td><?= htmlspecialchars($row['created_at'] ?? '—'); ?></td>
                    <td>
                        <a href="view_order.php?id=<?= urlencode($row['id']); ?>" class="btn btn-sm btn-primary">
                            View
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No orders found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
