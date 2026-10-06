<?php
$pageTitle = 'Checkout';
require_once 'includes/header.php';

$cartItems = getCartItems($pdo);
$cartTotal = getCartTotal($pdo);

if (empty($cartItems)) {
    redirect('cart.php');
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $address = clean($_POST['address'] ?? '');
    $city = clean($_POST['city'] ?? '');
    $notes = clean($_POST['notes'] ?? '');
    $payment_method = clean($_POST['payment_method'] ?? 'cash_on_delivery');

    $errors = [];

    if (empty($name)) $errors[] = 'Full name is required';
    if (empty($phone)) $errors[] = 'Phone number is required';
    if (empty($address)) $errors[] = 'Address is required';
    if (empty($city)) $errors[] = 'City is required';

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            $orderNumber = generateOrderNumber();
            $userId = isLoggedIn() ? $_SESSION['user_id'] : 0;

            // If guest, create a temporary user or use 0 (we'll allow guest orders with user_id nullable in future)
            // For simplicity, require login or create guest
            if (!isLoggedIn()) {
                // Create a simple guest order by inserting a temporary user or just use a default
                // Better: make user_id nullable, but for now force login or create guest
                $_SESSION['error'] = 'Please login or register to place an order.';
                redirect('login.php');
            }

            $stmt = $pdo->prepare("INSERT INTO orders 
                (user_id, order_number, total_amount, payment_method, shipping_name, shipping_phone, shipping_address, shipping_city, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $_SESSION['user_id'],
                $orderNumber,
                $cartTotal,
                $payment_method,
                $name,
                $phone,
                $address,
                $city,
                $notes
            ]);

            $orderId = $pdo->lastInsertId();

            // Insert order items & reduce stock
            $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stockStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");

            foreach ($cartItems as $item) {
                $itemStmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
                $stockStmt->execute([$item['quantity'], $item['id'], $item['quantity']]);
            }

            $pdo->commit();
            clearCart();

            $_SESSION['order_success'] = $orderNumber;
            redirect('order-success.php');

        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Order failed. Please try again.';
        }
    }
}
?>

<div class="section-title">
    <h2>Checkout</h2>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul style="margin:0; padding-left:20px;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px;">
    <!-- Shipping Form -->
    <div class="form-card" style="max-width:100%;">
        <h3 style="margin-bottom:20px;">Shipping Information</h3>

        <?php if (!isLoggedIn()): ?>
            <div class="alert alert-info">
                Please <a href="login.php" style="color:var(--primary);font-weight:600;">Login</a> or 
                <a href="register.php" style="color:var(--primary);font-weight:600;">Register</a> to place an order.
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="name" required 
                       value="<?php echo isset($_POST['name']) ? clean($_POST['name']) : (isLoggedIn() ? clean($_SESSION['user_name']) : ''); ?>">
            </div>

            <div class="form-group">
                <label>Phone Number *</label>
                <input type="text" name="phone" required placeholder="09xxxxxxxx"
                       value="<?php echo isset($_POST['phone']) ? clean($_POST['phone']) : ''; ?>">
            </div>

            <div class="form-group">
                <label>City *</label>
                <input type="text" name="city" required 
                       value="<?php echo isset($_POST['city']) ? clean($_POST['city']) : ''; ?>">
            </div>

            <div class="form-group">
                <label>Full Address *</label>
                <textarea name="address" required><?php echo isset($_POST['address']) ? clean($_POST['address']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>Order Notes (optional)</label>
                <textarea name="notes"><?php echo isset($_POST['notes']) ? clean($_POST['notes']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method">
                    <option value="cash_on_delivery">Cash on Delivery</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="telebirr">Telebirr</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; padding:14px;" <?php echo !isLoggedIn() ? 'disabled' : ''; ?>>
                Place Order
            </button>
        </form>
    </div>

    <!-- Order Summary -->
    <div>
        <div class="cart-summary" style="margin-top:0;">
            <h3>Your Order</h3>
            <?php foreach ($cartItems as $item): ?>
                <div class="summary-row">
                    <span><?php echo clean($item['name']); ?> × <?php echo $item['quantity']; ?></span>
                    <span><?php echo formatPrice($item['subtotal']); ?></span>
                </div>
            <?php endforeach; ?>
            <div class="summary-row total">
                <span>Total</span>
                <span><?php echo formatPrice($cartTotal); ?></span>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>
