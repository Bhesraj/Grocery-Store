<?php
include('config/db.php');

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $type = $_POST['type']; 

    $sql = "INSERT INTO users (username, password, type) VALUES ('$username', '$password', '$type')";

    if ($conn->query($sql) === TRUE) {
        $message = "<span style='color:green;'>Registration successful! <a href='login.php'>Login here</a></span>";
    } else {
        $message = "<span style='color:red;'>Error: " . $conn->error . "</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Grocery Store - Register</title>
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
        background: rgba(255, 255, 255, 0.92);
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

    .message {
        margin-bottom: 15px;
        font-size: 14px;
    }

    .footer-link {
        font-size: 13px;
        margin-top: 10px;
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

    <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Account Type:</label>
        <select name="type" required>
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
