<?php
session_start();
imp


if (!isset($_SESSION['username'], $_SESSION['type'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/config/db.php';


$username = $_SESSION['username'];
$type     = $_SESSION['type'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
  
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: url('images/grocery-bg.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #333;
    }

   
    .overlay {
      background-color: rgba(255, 255, 255, 0.85);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 24px;
    }

    .container {
      width: 100%;
      max-width: 800px;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      text-align: center;
    }

    h2 { color: #2c3e50; margin: 0 0 8px; }
    h3 { color: #34495e; margin: 20px 0 10px; }

    ul { list-style: none; padding: 0; margin: 0; }
    ul li { margin: 10px 0; }
    ul li a { text-decoration: none; color: #3498db; font-weight: bold; }
    ul li a:hover { text-decoration: underline; color: #2980b9; }

    .logout-btn {
      background-color: #e74c3c;
      color: #fff;
      padding: 100px 100px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 20px;
    }
    .logout-btn:hover { background-color: #c0392b; }
    .bj_image {
      width: 100%;
      height: 100%;
    }
  </style>
</head>
<body>
  
  <div class="overlay">
    <div>
    <img class="bj_image" src="/images/grocery-bg.jpg" alt="">
    this is image section
  </div>
    <div class="container">
      <h2>Welcome, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>!</h2>
      <p>You are logged in as <strong><?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?></strong>.</p>

      <?php if ($type === 'shopkeeper') { ?>
        <h3>Shopkeeper Panel</h3>
        <ul>
          <li><a href="shopkeeper/manage_products.php">Manage Products</a></li>
          <li><a href="shopkeeper/orders.php">View Orders</a></li>
        </ul>
      <?php } else { ?>
        <h3>Customer Panel</h3>
        <ul>
          <li><a href="products.php">Browse Products</a></li>
          <li><a href="orders/list_orders.php">My Orders</a>
        </ul>
      <?php } ?>

      <form action="logout.php" method="POST">
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </div>
</body>
</html>
