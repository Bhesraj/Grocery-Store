<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$type = $_SESSION['type'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FreshMart Dashboard</title>
<style>
    * {
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        margin: 0;
        padding: 0;
        background: url('https://images.unsplash.com/photo-1606787366850-de6330128bfc') no-repeat center center/cover;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .dashboard-container {
        background: rgba(255, 255, 255, 0.95);
        padding: 40px 50px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        text-align: center;
        max-width: 600px;
        width: 90%;
    }

    .dashboard-container h1 {
        color: #2c3e50;
        font-size: 26px;
        margin-bottom: 5px;
    }

    .dashboard-container p {
        color: #555;
        margin-bottom: 10px;
        font-size: 15px;
    }

    .dashboard-container h2 {
        color: #27ae60;
        margin-top: 10px;
        margin-bottom: 25px;
    }

    .actions {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 30px;
    }

    .action-card {
        background: #f9f9f9;
        padding: 20px 10px;
        border-radius: 15px;
        width: 160px;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #333;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .action-card:hover {
        transform: translateY(-5px);
        background: #e8f5e9;
    }

    /* ✅ Make images smaller and consistent */
    .action-card img {
        width: 60px;
        height: 60px;
        object-fit: contain;
        margin-bottom: 8px;
    }

    .action-card span {
        display: block;
        font-weight: 600;
        font-size: 15px;
        text-align: center;
    }

    .logout-btn {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }

    .logout-btn:hover {
        background: #c0392b;
    }

</style>
</head>
<body>

<div class="dashboard-container">
    <h1>Welcome, <?= htmlspecialchars($username); ?> 👋</h1>
    <p>You are logged in as <strong><?= htmlspecialchars($type); ?></strong>.</p>
    
    <h2><?= ucfirst($type); ?> Panel</h2>

    <div class="actions">
       <a href="products/products/products.php" class="action-card">
            <img src="https://cdn-icons-png.flaticon.com/512/3081/3081559.png" alt="Browse Products">
            <span>Browse Products</span>
        </a>

        <a href="my_orders.php" class="action-card">
            <img src="https://cdn-icons-png.flaticon.com/512/1792/1792512.png" alt="My Orders">
            <span>My Orders</span>
        </a>
    </div>

    <form method="POST" action="logout.php">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>

</body>
</html>
