<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['type'] !== 'customer'){
    header("Location: ../login.php");
    exit();
}
if (isset($_GET['id']) && isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $_GET['id']) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex
            break;
        }
    }
}
header("Location: view_cart.php");
exit();
?>
