<?php
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['product_id'] ?? 0);
    if ($id > 0) {
        toggleWishlist($id);
    }
    $redirect = $_POST['redirect'] ?? 'index.php';
    // Prevent open redirect
    if (strpos($redirect, 'http') === 0) $redirect = 'index.php';
    header('Location: ' . $redirect);
    exit;
}
header('Location: index.php');
exit;
