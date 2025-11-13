<?php
session_start();
include('config/db.php'); 

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, type FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // ✅ Consistent session variables
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['user_type'] = $user['type']; // 'shopkeeper' or 'customer'

            // Redirect to unified dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FreshMart - Login</title>
<style>
* { box-sizing:border-box; font-family: 'Poppins', sans-serif; }
body {
    margin:0; padding:0; height:100vh;
    background-image:url('https://images.unsplash.com/photo-1582515073490-dc84f2f2d2b3?auto=format&fit=crop&w=1600&q=80');
    background-size: cover; background-position:center; background-attachment:fixed;
    display:flex; justify-content:center; align-items:center;
}
body::before {
    content:""; position:absolute; top:0; left:0; width:100%; height:100%;
    background: rgba(255,255,255,0.4); z-index:0;
}
.login-container {
    position:relative; z-index:1; background: rgba(255,255,255,0.95);
    padding:40px 50px; border-radius:20px; box-shadow:0 8px 25px rgba(0,0,0,0.2);
    text-align:center; width:350px; backdrop-filter: blur(5px);
}
.login-container h1 { margin-bottom:20px; color:#2c3e50; }
.login-container label { display:block; text-align:left; margin-bottom:5px; font-weight:600; }
.login-container input[type="text"],
.login-container input[type="password"] { width:100%; padding:10px; margin-bottom:20px; border:1px solid #ccc; border-radius:8px; font-size:14px; }
.login-container button { width:100%; padding:10px; background-color:#27ae60; border:none; color:white; border-radius:8px; font-size:16px; font-weight:600; cursor:pointer; transition: background 0.3s ease; }
.login-container button:hover { background-color:#219150; }
.error { color:red; margin-bottom:15px; font-size:14px; }
.footer-text { margin-top:10px; font-size:13px; color:#555; }
.footer-link { margin-top:8px; font-size:13px; }
.footer-link a { color:#2c3e50; text-decoration:none; font-weight:600; }
.footer-link a:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="login-container">
    <h1>Welcome to FreshMart 🥦</h1>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" placeholder="Enter your username" required>
        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit">Login</button>
    </form>

    <div class="footer-text">Fresh groceries delivered to your doorstep 🍎</div>
    <div class="footer-link">Don't have an account? <a href="register.php">Register here</a></div>
</div>

</body>
</html>

