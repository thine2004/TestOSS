<?php
require_once 'config.php';

// If user is already logged in, go to Dashboard
if (isLoggedIn()) {
    redirect(isAdmin() ? 'admin/index.php' : 'dashboard.php');
}

// Handle Login/Register
$action = $_GET['action'] ?? 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($action === 'login') {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Check if blocked
                if (isset($user['status']) && $user['status'] === 'blocked') {
                    setFlash('error', 'Tài khoản đã bị khóa.');
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        redirect('admin/index.php');
                    } else {
                        redirect('dashboard.php');
                    }
                }
            } else {
                setFlash('error', 'Tài khoản hoặc mật khẩu không đúng');
            }
        } else {
            setFlash('error', 'Tài khoản không tồn tại');
        }
    } else {
        // Register
        $fullname = $_POST['fullname'];
        $passHash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, plain_password, fullname, role) VALUES (?, ?, ?, ?, 'user')");
            $stmt->execute([$username, $passHash, $password, $fullname]);
            setFlash('success', 'Account created! Please login.');
            redirect('login.php');
        } catch (PDOException $e) {
            setFlash('error', 'Username already exists.');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | English Mastery</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-box">
            <h2 style="text-align: center; margin-bottom: 2rem; color: var(--primary);">
                <?php echo $action === 'login' ? 'Welcome Back' : 'Join Us'; ?>
            </h2>

            <?php if ($f = getFlash()): ?>
                <div style="background: <?php echo $f['type'] == 'error' ? '#fee2e2' : '#d1fae5'; ?>; color: <?php echo $f['type'] == 'error' ? '#dc2626' : '#059669'; ?>; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;">
                    <?php echo $f['message']; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="?action=<?php echo $action; ?>">
                <?php if ($action === 'register'): ?>
                    <div style="margin-bottom: 1rem;">
                        <label style="display:block; margin-bottom:0.5rem; font-weight:500;">Full Name</label>
                        <input type="text" name="fullname" required style="width: 100%; padding: 0.8rem; border: 1px solid #e5e7eb; border-radius: 8px;">
                    </div>
                <?php endif; ?>

                <div style="margin-bottom: 1rem;">
                    <label style="display:block; margin-bottom:0.5rem; font-weight:500;">Username</label>
                    <input type="text" name="username" required style="width: 100%; padding: 0.8rem; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display:block; margin-bottom:0.5rem; font-weight:500;">Password</label>
                    <input type="password" name="password" required style="width: 100%; padding: 0.8rem; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>

                <button type="submit" class="btn" style="width: 100%; justify-content: center;">
                    <?php echo $action === 'login' ? 'Sign In' : 'Create Account'; ?>
                </button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="?action=<?php echo $action === 'login' ? 'register' : 'login'; ?>" style="color: var(--primary); font-weight: 600;">
                    <?php echo $action === 'login' ? 'No account? Sign up' : 'Have an account? Login'; ?>
                </a>
                <div style="margin-top: 1rem;">
                    <a href="index.php" style="color: #6b7280; font-size: 0.9rem; text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Quay lại trang chủ
                    </a>
                </div>
            </div>

            <div style="margin-top: 2rem; text-align: center; font-size: 0.8rem; color: #9ca3af;">
                <div>Default User: <b>user</b> / <b>password</b></div>
                <div style="margin-top: 4px;">Default Admin: <b>admin</b> / <b>123123</b></div>
            </div>
        </div>
    </div>
</body>

</html>