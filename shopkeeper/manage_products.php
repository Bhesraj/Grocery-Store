<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'shopkeeper') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Products</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f8f9fa;
    padding: 40px;
}
h1 {
    color: #2c3e50;
}
a {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #27ae60;
    color: white;
    border-radius: 5px;
    text-decoration: none;
}
a:hover {
    background-color: #219150;
}
</style>
</head>
<body>

<h1>Welcome, <?php echo $_SESSION['username']; ?> 👋</h1>
<p>You are logged in as a <strong>Shopkeeper</strong>.</p>

<a href="../logout.php">Logout</a>

</body>
</html>
