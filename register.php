<?php
session_start();
include('config/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FreshMart - Register</title>
<style>
    * {
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        margin: 0;
        padding: 0;
        background: url('https://images.unsplash.com/photo-1603046891741-944cbca5f1f1') no-repeat center center/cover;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .register-container {
        background: rgba(255, 255, 255, 0.93);
        padding: 40px 50px;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        text-align: center;
        width: 400px;
    }

    .register-container h1 {
        margin-bottom: 20px;
        color: #2c3e50;
    }

    .message {
        margin-bottom: 15px;
        font-size: 15px;
        font-weight: 500;
        padding: 10px;
        border-radius: 6px;
    }

    .message.success {
        background: #eafaf1;
        color: #2e7d32;
        border: 1px solid #a5d6a7;
    }

    .message.error {
        background: #fdecea;
        color: #c62828;
        border: 1px solid #ef9a9a;
    }

    .register-container label {
        display: block;
        text-align: left;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .register-container input,
    .register-container select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
    }

    .register-container button {
        width: 100%;
        padding: 10px;
        background-color: #27ae60;
        border: none;
        color: white;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .register-container button:hover {
        background-color: #219150;
    }

    .footer-link {
        font-size: 13px;
        margin-top: 15px;
    }

    .footer-link a {
        color: #27ae60;
        text-decoration: none;
        font-weight: 600;
    }

    .footer-link a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="register-container">
    <h1>Create Your FreshMart Account 🥬</h1>

    <!-- ✅ Display Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="message success"><?= $_SESSION['success']; ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error']; ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- ✅ Registration Form -->
    <form method="POST" action="register_action.php">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Account Type:</label>
        <select name="type" required>
            <option value="">Select type</option>
            <option value="customer">Customer</option>
            <option value="shopkeeper">Shopkeeper</option>
        </select>

        <button type="submit">Register</button>
    </form>

    <div class="footer-link">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>

</body>
</html>
