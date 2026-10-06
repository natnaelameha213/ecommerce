<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'add':
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        $product = getProductById($pdo, $product_id);
        if ($product && $product['stock'] > 0) {
            addToCart($product_id, $quantity);
            $_SESSION['success'] = 'Product added to cart!';
        } else {
            $_SESSION['error'] = 'Product not available.';
        }
        break;

    case 'update':
        if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
            foreach ($_POST['quantities'] as $id => $qty) {
                updateCartQuantity((int)$id, (int)$qty);
            }
            $_SESSION['success'] = 'Cart updated successfully!';
        }
        break;

    case 'remove':
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        removeFromCart($product_id);
        $_SESSION['success'] = 'Product removed from cart.';
        break;

    case 'clear':
        clearCart();
        $_SESSION['success'] = 'Cart cleared.';
        break;
}

// Redirect back
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'cart.php';
header("Location: " . $redirect);
exit;
?>
