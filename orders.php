<?php
$pageTitle = 'My Orders';
require_once 'includes/header.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>

<div class="section-title">
    <h2>My Orders</h2>
</div>

<?php if (empty($orders)): ?>
    <div class="empty-state">
        <i class="fas fa-receipt"></i>
        <h2>No orders yet</h2>
        <p>When you place an order, it will show up here.</p>
        <a href="products.php" class="btn btn-primary">Start Shopping</a>
    </div>
<?php else: ?>
    <div class="orders-list">
        <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <div class="order-card-header">
                    <div>
                        <strong><?php echo clean($order['order_number']); ?></strong>
                        <div style="font-size:.85rem;color:var(--text-muted);margin-top:.2rem;">
                            <?php echo date('M j, Y · g:ia', strtotime($order['created_at'])); ?>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-weight:700;font-size:1.1rem;color:var(--orange);">
                            <?php echo formatPrice($order['total_amount']); ?>
                        </div>
                        <span class="status-badge status-<?php echo clean($order['status'] ?? 'pending'); ?>">
                            <?php echo ucfirst($order['status'] ?? 'pending'); ?>
                        </span>
                    </div>
                </div>
                <div style="font-size:.875rem;color:var(--text-muted);">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php echo clean($order['shipping_name']); ?> ·
                    <?php echo clean($order['shipping_city']); ?> ·
                    <?php echo clean($order['payment_method']); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
