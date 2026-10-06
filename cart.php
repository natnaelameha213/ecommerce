<?php
$pageTitle = 'Shopping Cart';
require_once 'includes/header.php';

$cartItems = getCartItems($pdo);
$cartTotal = getCartTotal($pdo);
?>

<div class="section-title">
    <h2>Shopping Cart</h2>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<?php if (empty($cartItems)): ?>
    <div class="empty-state">
        <i class="fas fa-shopping-cart"></i>
        <h2>Your cart is empty</h2>
        <p>Looks like you haven't added any products yet.</p>
        <a href="products.php" class="btn btn-primary">Continue Shopping</a>
    </div>
<?php else: ?>

    <form action="cart-action.php" method="POST">
        <input type="hidden" name="action" value="update">

        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td data-label="Product">
                            <div class="cart-product">
                                <?php if ($item['image'] && file_exists('assets/images/' . $item['image'])): ?>
                                    <img src="assets/images/<?php echo $item['image']; ?>" alt="">
                                <?php else: ?>
                                    <div style="width:70px;height:70px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-image" style="color:#cbd5e1;"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <strong><?php echo clean($item['name']); ?></strong>
                                </div>
                            </div>
                        </td>
                        <td data-label="Price"><?php echo formatPrice($item['price']); ?></td>
                        <td data-label="Quantity" class="cart-qty">
                            <input type="number" name="quantities[<?php echo $item['id']; ?>]" 
                                   value="<?php echo $item['quantity']; ?>" 
                                   min="1" max="<?php echo $item['stock']; ?>">
                        </td>
                        <td data-label="Subtotal"><strong><?php echo formatPrice($item['subtotal']); ?></strong></td>
                        <td data-label="Action">
                            <button type="submit" formaction="cart-action.php" formmethod="POST" 
                                    name="action" value="remove" 
                                    onclick="this.form.product_id.value=<?php echo $item['id']; ?>"
                                    class="btn btn-danger btn-sm"
                                    data-confirm="Remove this item?">
                                <i class="fas fa-trash"></i>
                            </button>
                            <input type="hidden" name="product_id" value="">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px; display: flex; gap: 12px; flex-wrap: wrap;">
            <button type="submit" class="btn btn-outline">
                <i class="fas fa-sync"></i> Update Cart
            </button>
            <a href="products.php" class="btn btn-outline">Continue Shopping</a>
        </div>
    </form>

    <div class="cart-summary">
        <h3>Order Summary</h3>
        <div class="summary-row">
            <span>Subtotal</span>
            <span><?php echo formatPrice($cartTotal); ?></span>
        </div>
        <div class="summary-row">
            <span>Shipping</span>
            <span>Calculated at checkout</span>
        </div>
        <div class="summary-row total">
            <span>Total</span>
            <span><?php echo formatPrice($cartTotal); ?></span>
        </div>

        <a href="checkout.php" class="btn btn-primary" style="width:100%; text-align:center; margin-top:20px; padding:14px;">
            Proceed to Checkout <i class="fas fa-arrow-right"></i>
        </a>
    </div>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
