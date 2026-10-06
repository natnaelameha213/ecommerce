<?php
$pageTitle = 'Categories';
require_once 'includes/header.php';

// Add / Edit category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name'] ?? '');
    $description = clean($_POST['description'] ?? '');
    $slug = createSlug($name);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if (!empty($name)) {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE categories SET name=?, slug=?, description=? WHERE id=?");
            $stmt->execute([$name, $slug, $description, $id]);
            $_SESSION['success'] = 'Category updated';
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
            $stmt->execute([$name, $slug, $description]);
            $_SESSION['success'] = 'Category added';
        }
    }
    header("Location: categories.php");
    exit;
}

// Delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    $_SESSION['success'] = 'Category deleted';
    header("Location: categories.php");
    exit;
}

$categories = getCategories($pdo);
$editCategory = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editCategory = $stmt->fetch();
}
?>

<div class="admin-header">
    <h1>Categories</h1>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:30px;">
    <div class="form-card" style="max-width:100%;">
        <h3><?php echo $editCategory ? 'Edit Category' : 'Add Category'; ?></h3>
        <form method="POST">
            <?php if ($editCategory): ?>
                <input type="hidden" name="id" value="<?php echo $editCategory['id']; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required value="<?php echo $editCategory['name'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?php echo $editCategory['description'] ?? ''; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                <?php echo $editCategory ? 'Update' : 'Add Category'; ?>
            </button>
            <?php if ($editCategory): ?>
                <a href="categories.php" class="btn btn-outline">Cancel</a>
            <?php endif; ?>
        </form>
    </div>

    <div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?php echo clean($cat['name']); ?></td>
                        <td><?php echo clean($cat['slug']); ?></td>
                        <td>
                            <a href="categories.php?edit=<?php echo $cat['id']; ?>" class="btn btn-sm btn-outline">Edit</a>
                            <a href="categories.php?delete=<?php echo $cat['id']; ?>" 
                               class="btn btn-sm btn-danger"
                               data-confirm="Delete this category?">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
