<?php
$pageTitle = 'Order Success';
require_once 'includes/header.php';

if (!isset($_SESSION['order_success'])) {
    redirect('index.php');
}

$orderNumber = $_SESSION['order_success'];
unset($_SESSION['order_success']);
?>

<div class="empty-state" style="max-width:600px; margin:40px auto;">
    <i class="fas fa-check-circle" style="color:var(--success); font-size:5rem;"></i>
    <h2>Thank You!</h2>
    <p>Your order has been placed successfully.</p>
    <p style="font-size:1.2rem; margin:20px 0;">
        Order Number: <strong><?php echo clean($orderNumber); ?></strong>
    </p>
    <p style="color:var(--gray);">We will contact you soon for delivery confirmation.</p>
    <div style="margin-top:30px; display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
        <a href="products.php" class="btn btn-outline">Browse Products</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
