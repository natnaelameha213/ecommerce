<?php
$imgSrc = !empty($product['image'])
    ? ((strpos($product['image'], 'http') === 0) ? $product['image'] : 'assets/images/' . $product['image'])
    : null;
$onSale = !empty($product['compare_price']) && $product['compare_price'] > $product['price'];
$outOfStock = (int)($product['stock'] ?? 0) <= 0;
$wish = function_exists('inWishlist') ? inWishlist($product['id']) : false;
?>
<div class="product-card" style="position:relative;">
    <div class="product-image">
        <a href="product.php?slug=<?php echo htmlspecialchars($product['slug']); ?>">
            <?php if ($onSale): ?><span class="badge">Sale</span><?php endif; ?>
            <?php if ($outOfStock): ?><span class="badge badge-out" style="left:auto;right:.75rem;top:3.2rem;">Sold out</span><?php endif; ?>
            <?php if ($imgSrc): ?>
                <img src="<?php echo $imgSrc; ?>" alt="<?php echo clean($product['name']); ?>">
            <?php else: ?>
                <div class="placeholder"><i class="fas fa-image"></i></div>
            <?php endif; ?>
        </a>
        <form action="wishlist-action.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'index.php'); ?>">
            <button type="submit" class="wishlist-btn <?php echo $wish ? 'active' : ''; ?>" title="Add to wishlist">
                <i class="<?php echo $wish ? 'fas' : 'far'; ?> fa-heart"></i>
            </button>
        </form>
    </div>
    <div class="product-info">
        <div class="product-category"><?php echo clean($product['category_name'] ?? ''); ?></div>
        <a href="product.php?slug=<?php echo htmlspecialchars($product['slug']); ?>">
            <h3 class="product-name"><?php echo clean($product['name']); ?></h3>
        </a>
        <div class="product-price">
            <span class="current-price"><?php echo formatPrice($product['price']); ?></span>
            <?php if (!empty($product['compare_price'])): ?>
                <span class="compare-price"><?php echo formatPrice($product['compare_price']); ?></span>
            <?php endif; ?>
        </div>
        <div class="product-actions">
            <a href="product.php?slug=<?php echo htmlspecialchars($product['slug']); ?>" class="btn btn-outline btn-sm">View</a>
            <?php if (!$outOfStock): ?>
            <form action="cart-action.php" method="POST" style="flex:1;">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                <button type="submit" class="btn btn-primary btn-sm" style="width:100%;">
                    <i class="fas fa-cart-plus"></i> Add
                </button>
            </form>
            <?php else: ?>
                <button class="btn btn-outline btn-sm" style="flex:1;opacity:.5;" disabled>Unavailable</button>
            <?php endif; ?>
        </div>
    </div>
</div>
