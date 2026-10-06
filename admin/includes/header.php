<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

// Protect admin pages
if (!isLoggedIn() || !isAdmin()) {
    header("Location: " . SITE_URL . "/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - Admin' : 'Admin Panel'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="logo">
            <i class="fas fa-store"></i> <?php echo SITE_NAME; ?>
        </div>
        <nav>
            <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' || basename($_SERVER['PHP_SELF']) == 'add-product.php' ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> Products
            </a>
            <a href="categories.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
                <i class="fas fa-tags"></i> Categories
            </a>
            <a href="orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-bag"></i> Orders
            </a>
            <a href="<?php echo SITE_URL; ?>/index.php" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Store
            </a>
            <a href="<?php echo SITE_URL; ?>/logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </aside>

    <main class="admin-content">
