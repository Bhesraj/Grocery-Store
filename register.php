<?php
include('config/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $type = $_POST['type']; 

    $sql = "INSERT INTO users (username, password, type) VALUES ('$username', '$password', '$type')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h2>Register</h2>
<form method="POST" action="">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Account Type:</label><br>
    <select name="type">
        <option value="customer">Customer</option>
        <option value="shopkeeper">Shopkeeper</option>
    </select><br><br>

    <button type="submit">Register</button>
</form>
