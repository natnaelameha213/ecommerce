<?php
$pageTitle = 'Login';
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] === 'admin') {
                redirect('admin/index.php');
            } else {
                redirect('index.php');
            }
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>

<div class="auth-container">
    <div class="form-card">
        <div class="auth-tabs">
            <a href="login.php" class="active">Login</a>
            <a href="register.php">Register</a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required value="<?php echo isset($_POST['email']) ? clean($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">Login</button>
        </form>

        <p style="text-align:center; margin-top:20px; color:var(--gray); font-size:0.9rem;">
            Demo Admin: admin@store.com / admin123
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
