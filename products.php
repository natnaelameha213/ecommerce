<?php
$pageTitle = 'Products';
require_once 'includes/header.php';

$search = isset($_GET['search']) ? clean($_GET['search']) : '';
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Build query
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'active'";
$params = [];

if ($search) {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category_id) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category_id;
}

$sql .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categoryName = '';
if ($category_id) {
    $catStmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
    $catStmt->execute([$category_id]);
    $categoryName = $catStmt->fetchColumn();
}
?>

<div class="section-title">
    <h2>
        <?php
        if ($search) {
            echo 'Search results for "' . $search . '"';
        } elseif ($categoryName) {
            echo clean($categoryName);
        } else {
            echo 'All Products';
        }
        ?>
    </h2>
    <span><?php echo count($products); ?> products found</span>
</div>

<?php if (empty($products)): ?>
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h2>No products found</h2>
        <p>Try a different search or browse all categories.</p>
        <a href="products.php" class="btn btn-primary">View All Products</a>
    </div>
<?php else: ?>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
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
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>