<?php
$pageTitle = 'Register';
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';
    if ($password !== $confirm) $errors[] = 'Passwords do not match';

    // Check if email exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email already registered';
        }
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashed, $phone]);

        $success = true;
    }
}
?>

<div class="auth-container">
    <div class="form-card">
        <div class="auth-tabs">
            <a href="login.php">Login</a>
            <a href="register.php" class="active">Register</a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                Registration successful! <a href="login.php" style="font-weight:600; color:var(--primary);">Login here</a>
            </div>
        <?php else: ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul style="margin:0; padding-left:20px;">
                        <?php foreach ($errors as $e): ?>
                            <li><?php echo $e; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required value="<?php echo isset($_POST['name']) ? clean($_POST['name']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required value="<?php echo isset($_POST['email']) ? clean($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Phone (optional)</label>
                    <input type="text" name="phone" value="<?php echo isset($_POST['phone']) ? clean($_POST['phone']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required minlength="6">
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;">Create Account</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
