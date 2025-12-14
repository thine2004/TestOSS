<?php
require_once 'config.php';

// Helper to keep the nav consistent
function renderNav($isLoggedIn)
{
?>
    <nav style="padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto;">
        <a href="index.php" style="font-size: 1.5rem; font-weight: 800; color: var(--primary); text-decoration: none;"><i class="fas fa-layer-group"></i> Mastery</a>
        <div style="display: flex; gap: 2rem; align-items: center;" class="nav-links">
            <?php $page = basename($_SERVER['PHP_SELF']); ?>

            <a href="index.php" class="<?php echo ($page == 'index.php') ? 'active' : ''; ?>">Giới thiệu</a>
            <a href="news.php" class="<?php echo ($page == 'news.php' || $page == 'news_detail.php') ? 'active' : ''; ?>">Tin tức</a>
            <a href="reviews.php" class="<?php echo ($page == 'reviews.php') ? 'active' : ''; ?>">Đánh giá</a>
            <a href="help.php" class="<?php echo ($page == 'help.php') ? 'active' : ''; ?>">Hỗ trợ</a>
            <a href="contact.php" class="<?php echo ($page == 'contact.php') ? 'active' : ''; ?>">Liên hệ</a>
            <a href="labs.php" class="<?php echo ($page == 'labs.php') ? 'active' : ''; ?>">Lab</a>

            <?php if ($isLoggedIn): ?>
                <?php if (isAdmin()): ?>
                    <a href="admin/index.php" style="border: 1px solid #d1d5db; padding: 0.5rem 1rem; border-radius: 99px; display: flex; align-items: center; gap: 0.5rem; color: #374151;">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                <?php endif; ?>

                <div style="background: #eff6ff; padding: 0.5rem 1rem; border-radius: 99px; display: flex; align-items: center; gap: 0.5rem; color: #374151; border: 1px solid #dbeafe;">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
                </div>

                <a href="logout.php" style="border: 1px solid #d1d5db; width: 40px; height: 40px; border-radius: 50%; display: grid; place-items: center; color: #374151; text-decoration: none; font-size: 0.9rem;" title="Đăng xuất">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            <?php else: ?>
                <a href="login.php" class="<?php echo ($page == 'login.php' && (!isset($_GET['action']) || $_GET['action'] !== 'register')) ? 'active' : ''; ?>">Đăng nhập</a>
                <a href="login.php?action=register" class="<?php echo ($page == 'login.php' && isset($_GET['action']) && $_GET['action'] == 'register') ? 'active' : ''; ?>">Đăng ký</a>
            <?php endif; ?>
        </div>
    </nav>
    <link rel="stylesheet" href="css/navbar.css?v=<?php echo time(); ?>">
<?php
}
?>