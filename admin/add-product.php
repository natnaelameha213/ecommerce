<?php
$pageTitle = 'Add Product';
require_once 'includes/header.php';

$categories = getCategories($pdo);
$product = null;
$isEdit = false;

if (isset($_GET['id'])) {
    $isEdit = true;
    $pageTitle = 'Edit Product';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $product = $stmt->fetch();
    if (!$product) {
        header("Location: products.php");
        exit;
    }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $description = clean($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $compare_price = !empty($_POST['compare_price']) ? (float)$_POST['compare_price'] : null;
    $stock = (int)($_POST['stock'] ?? 0);
    $featured = isset($_POST['featured']) ? 1 : 0;
    $status = clean($_POST['status'] ?? 'active');
    $slug = createSlug($name);

    if (empty($name)) $errors[] = 'Product name is required';
    if ($category_id < 1) $errors[] = 'Category is required';
    if ($price <= 0) $errors[] = 'Valid price is required';

    // Handle image upload (simple)
    $image = $product['image'] ?? null;
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['image']['name']);
            $uploadPath = __DIR__ . '/../../assets/images/' . $imageName;
            
            // Create directory if not exists
            if (!is_dir(__DIR__ . '/../../assets/images')) {
                mkdir(__DIR__ . '/../../assets/images', 0755, true);
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image = $imageName;
            }
        } else {
            $errors[] = 'Invalid image format';
        }
    }

    if (empty($errors)) {
        if ($isEdit) {
            $stmt = $pdo->prepare("UPDATE products SET 
                category_id=?, name=?, slug=?, description=?, price=?, compare_price=?, 
                stock=?, image=?, featured=?, status=? WHERE id=?");
            $stmt->execute([
                $category_id, $name, $slug, $description, $price, $compare_price,
                $stock, $image, $featured, $status, $product['id']
            ]);
            $_SESSION['success'] = 'Product updated successfully';
        } else {
            $stmt = $pdo->prepare("INSERT INTO products 
                (category_id, name, slug, description, price, compare_price, stock, image, featured, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $category_id, $name, $slug, $description, $price, $compare_price,
                $stock, $image, $featured, $status
            ]);
            $_SESSION['success'] = 'Product added successfully';
        }
        header("Location: products.php");
        exit;
    }
}
?>

<div class="admin-header">
    <h1><?php echo $isEdit ? 'Edit Product' : 'Add New Product'; ?></h1>
    <a href="products.php" class="btn btn-outline">← Back</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul style="margin:0;padding-left:20px;">
            <?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="form-card" style="max-width:700px;">
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name *</label>
            <input type="text" name="name" required value="<?php echo $product['name'] ?? ($_POST['name'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Category *</label>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" 
                        <?php echo (($product['category_id'] ?? '') == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo clean($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description"><?php echo $product['description'] ?? ($_POST['description'] ?? ''); ?></textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label>Price (ETB) *</label>
                <input type="number" name="price" step="0.01" min="0" required 
                       value="<?php echo $product['price'] ?? ($_POST['price'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Compare Price (old price)</label>
                <input type="number" name="compare_price" step="0.01" min="0" 
                       value="<?php echo $product['compare_price'] ?? ($_POST['compare_price'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Stock Quantity</label>
            <input type="number" name="stock" min="0" value="<?php echo $product['stock'] ?? ($_POST['stock'] ?? 0); ?>">
        </div>

        <div class="form-group">
            <label>Product Image</label>
            <?php if (!empty($product['image'])): ?>
                <p style="margin-bottom:8px;">Current: <?php echo $product['image']; ?></p>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="featured" value="1" 
                    <?php echo (!empty($product['featured'])) ? 'checked' : ''; ?>>
                Featured Product
            </label>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="active" <?php echo ($product['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo ($product['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <?php echo $isEdit ? 'Update Product' : 'Add Product'; ?>
        </button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
