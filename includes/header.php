<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$cartCount = getCartCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-top">
                <a href="<?php echo SITE_URL; ?>/index.php" class="logo">
                    <i class="fas fa-store"></i> <?php echo SITE_NAME; ?>
                </a>

                <form action="<?php echo SITE_URL; ?>/products.php" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search products..." 
                           value="<?php echo isset($_GET['search']) ? clean($_GET['search']) : ''; ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>

                <div class="header-actions">
                    <?php if (isLoggedIn()): ?>
                        <div class="user-menu">
                            <span><i class="fas fa-user"></i> <?php echo clean($_SESSION['user_name']); ?></span>
                            <?php if (isAdmin()): ?>
                                <a href="<?php echo SITE_URL; ?>/admin/index.php" class="btn-admin">Admin</a>
                            <?php endif; ?>
                            <a href="<?php echo SITE_URL; ?>/logout.php">Logout</a>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>/login.php" class="btn-login">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    <?php endif; ?>

                    <a href="<?php echo SITE_URL; ?>/cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if ($cartCount > 0): ?>
                            <span class="cart-count"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <nav class="nav">
                <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
                <a href="<?php echo SITE_URL; ?>/products.php">All Products</a>
                <?php
                $categories = getCategories($pdo);
                foreach ($categories as $cat):
                ?>
                    <a href="<?php echo SITE_URL; ?>/products.php?category=<?php echo $cat['id']; ?>">
                        <?php echo clean($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
