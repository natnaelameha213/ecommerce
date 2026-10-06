<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$slug = isset($_GET['slug']) ? clean($_GET['slug']) : '';
$product = getProductBySlug($pdo, $slug);

if (!$product) {
    header("Location: products.php");
    exit;
}

$pageTitle = $product['name'];
require_once 'includes/header.php';
?>

<div class="product-detail">
    <div class="product-detail-image">
        <?php if (!empty($product['image'])): ?>
            <img src="<?php echo (strpos($product['image'], 'http') === 0) ? $product['image'] : 'assets/images/' . $product['image']; ?>" alt="<?php echo clean($product['name']); ?>">
        <?php else: ?>
            <div class="placeholder" style="font-size:5rem;color:#cbd5e1;">
                <i class="fas fa-image"></i>
            </div>
        <?php endif; ?>
    </div>

    <div class="product-detail-info">
        <div class="product-meta">
            Category: <strong><?php echo clean($product['category_name']); ?></strong>
        </div>

        <h1><?php echo clean($product['name']); ?></h1>

        <div class="product-price">
            <span class="current-price"><?php echo formatPrice($product['price']); ?></span>
            <?php if ($product['compare_price']): ?>
                <span class="compare-price"><?php echo formatPrice($product['compare_price']); ?></span>
            <?php endif; ?>
        </div>

        <div class="stock-info">
            <?php if ($product['stock'] > 0): ?>
                <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock (<?php echo $product['stock']; ?> available)</span>
            <?php else: ?>
                <span class="out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
            <?php endif; ?>
        </div>

        <p style="margin: 20px 0; color: #475569; line-height: 1.7;">
            <?php echo nl2br(clean($product['description'])); ?>
        </p>

        <?php if ($product['stock'] > 0): ?>
            <form action="cart-action.php" method="POST">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                <div class="quantity-selector">
                    <label>Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="qty-input">
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; padding:14px; font-size:1.1rem;">
                    <i class="fas fa-cart-plus"></i> Add to Cart
                </button>
            </form>
        <?php else: ?>
            <button class="btn" disabled style="width:100%; opacity:0.6;">Out of Stock</button>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>