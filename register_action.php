<?php
session_start();
include 'includes/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $type = $_POST['type'] ?? '';

    // Basic validation
    if (empty($username) || empty($password) || empty($type)) {
        $_SESSION['error'] = "⚠️ All fields are required.";
        header("Location: register.php");
        exit();
    }

    // Validate role
    $valid_roles = ['customer', 'shopkeeper'];
    if (!in_array($type, $valid_roles)) {
        $_SESSION['error'] = "⚠️ Invalid account type selected.";
        header("Location: register.php");
        exit();
    }

    // Check if username already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = "⚠️ Username already taken. Please choose another.";
        $check_stmt->close();
        header("Location: register.php");
        exit();
    }
    $check_stmt->close();

    // Hash password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, password, type) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $type);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Registration successful! You can now log in.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "❌ Database error: " . htmlspecialchars($stmt->error);
        header("Location: register.php");
        exit();
    }

    $stmt->close();
}
?>

