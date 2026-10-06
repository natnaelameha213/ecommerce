<?php
$pageTitle = 'Products';
require_once 'includes/header.php';

// Delete product
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = 'Product deleted successfully';
    header("Location: products.php");
    exit;
}

$products = $pdo->query("SELECT p.*, c.name as category_name 
                         FROM products p 
                         LEFT JOIN categories c ON p.category_id = c.id 
                         ORDER BY p.created_at DESC")->fetchAll();
?>

<div class="admin-header">
    <h1>Products</h1>
    <a href="add-product.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</a>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td>
                        <strong><?php echo clean($p['name']); ?></strong>
                        <?php if ($p['featured']): ?>
                            <span class="badge" style="position:static; font-size:0.7rem;">Featured</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo clean($p['category_name']); ?></td>
                    <td><?php echo formatPrice($p['price']); ?></td>
                    <td><?php echo $p['stock']; ?></td>
                    <td>
                        <span class="status-badge status-<?php echo $p['status'] === 'active' ? 'delivered' : 'cancelled'; ?>">
                            <?php echo ucfirst($p['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="add-product.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline">Edit</a>
                        <a href="products.php?delete=<?php echo $p['id']; ?>" 
                           class="btn btn-sm btn-danger"
                           data-confirm="Delete this product?">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
