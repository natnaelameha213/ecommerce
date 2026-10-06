<?php
$pageTitle = 'Wishlist';
require_once 'includes/header.php';

$ids = getWishlist();
$products = [];
if (!empty($ids)) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id IN ($placeholders) AND p.status='active'");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();
}
?>

<div class="section-title">
    <h2><i class="fas fa-heart" style="color:var(--orange);"></i> Wishlist</h2>
    <span class="count"><?php echo count($products); ?> items</span>
</div>

<?php if (empty($products)): ?>
    <div class="empty-state">
        <i class="far fa-heart"></i>
        <h2>Your wishlist is empty</h2>
        <p>Save items you love by tapping the heart icon.</p>
        <a href="products.php" class="btn btn-primary">Browse Products</a>
    </div>
<?php else: ?>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <?php include 'includes/product-card.php'; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
