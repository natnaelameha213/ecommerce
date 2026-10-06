<?php
$pageTitle = 'Home';
require_once 'includes/header.php';

$featuredProducts = getProducts($pdo, 8, true);
$allProducts = getProducts($pdo, 8);
?>

<!-- Hero Section -->
<section class="hero">
    <h1>Welcome to <?php echo SITE_NAME; ?></h1>
    <p>Discover quality products at the best prices. Shop now and enjoy fast delivery!</p>
    <a href="products.php" class="btn">Shop Now <i class="fas fa-arrow-right"></i></a>
</section>

<!-- Featured Products -->
<?php if (!empty($featuredProducts)): ?>
<section>
    <div class="section-title">
        <h2>Featured Products</h2>
        <a href="products.php">View All →</a>
    </div>

    <div class="product-grid">
        <?php foreach ($featuredProducts as $product): ?>
            <div class="product-card">
                <a href="product.php?slug=<?php echo $product['slug']; ?>">
                    <div class="product-image">
                        <?php if ($product['compare_price'] && $product['compare_price'] > $product['price']): ?>
                            <span class="badge">Sale</span>
                        <?php endif; ?>
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?php echo (strpos($product['image'], 'http') === 0) ? $product['image'] : 'assets/images/' . $product['image']; ?>" alt="<?php echo clean($product['name']); ?>">
                        <?php else: ?>
                            <div class="placeholder"><i class="fas fa-image"></i></div>
                        <?php endif; ?>
                    </div>
                </a>
                <div class="product-info">
                    <div class="product-category"><?php echo clean($product['category_name']); ?></div>
                    <a href="product.php?slug=<?php echo $product['slug']; ?>">
                        <h3 class="product-name"><?php echo clean($product['name']); ?></h3>
                    </a>
                    <div class="product-price">
                        <span class="current-price"><?php echo formatPrice($product['price']); ?></span>
                        <?php if ($product['compare_price']): ?>
                            <span class="compare-price"><?php echo formatPrice($product['compare_price']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="product-actions">
                        <a href="product.php?slug=<?php echo $product['slug']; ?>" class="btn btn-outline btn-sm">View</a>
                        <form action="cart-action.php" method="POST" style="flex:1;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn btn-primary btn-sm" style="width:100%;">
                                <i class="fas fa-cart-plus"></i> Add
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Latest Products -->
<section>
    <div class="section-title">
        <h2>Latest Products</h2>
        <a href="products.php">View All →</a>
    </div>

    <div class="product-grid">
        <?php foreach ($allProducts as $product): ?>
            <div class="product-card">
                <a href="product.php?slug=<?php echo $product['slug']; ?>">
                    <div class="product-image">
                        <?php if ($product['compare_price'] && $product['compare_price'] > $product['price']): ?>
                            <span class="badge">Sale</span>
                        <?php endif; ?>
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?php echo (strpos($product['image'], 'http') === 0) ? $product['image'] : 'assets/images/' . $product['image']; ?>" alt="<?php echo clean($product['name']); ?>">
                        <?php else: ?>
                            <div class="placeholder"><i class="fas fa-image"></i></div>
                        <?php endif; ?>
                    </div>
                </a>
                <div class="product-info">
                    <div class="product-category"><?php echo clean($product['category_name']); ?></div>
                    <a href="product.php?slug=<?php echo $product['slug']; ?>">
                        <h3 class="product-name"><?php echo clean($product['name']); ?></h3>
                    </a>
                    <div class="product-price">
                        <span class="current-price"><?php echo formatPrice($product['price']); ?></span>
                        <?php if ($product['compare_price']): ?>
                            <span class="compare-price"><?php echo formatPrice($product['compare_price']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="product-actions">
                        <a href="product.php?slug=<?php echo $product['slug']; ?>" class="btn btn-outline btn-sm">View</a>
                        <form action="cart-action.php" method="POST" style="flex:1;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn btn-primary btn-sm" style="width:100%;">
                                <i class="fas fa-cart-plus"></i> Add
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>