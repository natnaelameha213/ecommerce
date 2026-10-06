<?php
$pageTitle = 'Dashboard';
require_once 'includes/header.php';

// Stats
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalCustomers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn();

// Recent orders
$recentOrders = $pdo->query("SELECT o.*, u.name as customer_name 
                             FROM orders o 
                             LEFT JOIN users u ON o.user_id = u.id 
                             ORDER BY o.created_at DESC LIMIT 8")->fetchAll();
?>

<div class="admin-header">
    <h1>Dashboard</h1>
    <span>Welcome, <?php echo clean($_SESSION['user_name']); ?>!</span>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Products</h3>
        <div class="value"><?php echo $totalProducts; ?></div>
    </div>
    <div class="stat-card">
        <h3>Total Orders</h3>
        <div class="value"><?php echo $totalOrders; ?></div>
    </div>
    <div class="stat-card">
        <h3>Customers</h3>
        <div class="value"><?php echo $totalCustomers; ?></div>
    </div>
    <div class="stat-card">
        <h3>Revenue</h3>
        <div class="value"><?php echo formatPrice($totalRevenue); ?></div>
    </div>
</div>

<h2 style="margin-bottom:16px;">Recent Orders</h2>

<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recentOrders)): ?>
                <tr><td colspan="5" style="text-align:center;">No orders yet</td></tr>
            <?php else: ?>
                <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td><strong><?php echo clean($order['order_number']); ?></strong></td>
                        <td><?php echo clean($order['customer_name'] ?? $order['shipping_name']); ?></td>
                        <td><?php echo formatPrice($order['total_amount']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
