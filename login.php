<?php
session_start();
include('config/db.php'); 

$error = ""; // Initialize error message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize user input
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT username, password, type FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the hashed password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['username'] = $user['username'];
            $_SESSION['type'] = $user['type'];

            // Redirect based on user type
            if ($user['type'] === 'shopkeeper') {
                header("Location: shopkeeper/manage_products.php");
            } else {
                header("Location: index.php");
            }
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

<h2>Login</h2>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST" action="">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>
