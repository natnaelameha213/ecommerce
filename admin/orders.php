<?php
$pageTitle = 'Orders';
require_once 'includes/header.php';

// Update order status
if (isset($_POST['update_status'])) {
    $orderId = (int)$_POST['order_id'];
    $status = clean($_POST['status']);
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $orderId]);
    $_SESSION['success'] = 'Order status updated';
    header("Location: orders.php");
    exit;
}

$orders = $pdo->query("SELECT o.*, u.name as customer_name, u.email as customer_email
                       FROM orders o 
                       LEFT JOIN users u ON o.user_id = u.id 
                       ORDER BY o.created_at DESC")->fetchAll();
?>

<div class="admin-header">
    <h1>Orders</h1>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr><td colspan="7" style="text-align:center;">No orders yet</td></tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong><?php echo clean($order['order_number']); ?></strong></td>
                        <td>
                            <?php echo clean($order['customer_name'] ?? $order['shipping_name']); ?><br>
                            <small style="color:var(--gray);"><?php echo clean($order['shipping_phone']); ?></small>
                        </td>
                        <td><?php echo formatPrice($order['total_amount']); ?></td>
                        <td><?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></td>
                        <td>
                            <form method="POST" style="display:flex; gap:6px; align-items:center;">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" style="padding:6px; border-radius:4px; border:1px solid var(--border);">
                                    <option value="pending" <?php echo $order['status']=='pending'?'selected':''; ?>>Pending</option>
                                    <option value="processing" <?php echo $order['status']=='processing'?'selected':''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $order['status']=='shipped'?'selected':''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $order['status']=='delivered'?'selected':''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $order['status']=='cancelled'?'selected':''; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
