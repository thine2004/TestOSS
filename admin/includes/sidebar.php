<aside class="admin-sidebar">
    <a href="index.php" class="admin-brand"><i class="fas fa-shield-alt"></i> <span>Admin Control</span></a>
    <div class="admin-user-panel">
        <div class="admin-user-img"><i class="fas fa-user"></i></div>
        <div><?php echo $_SESSION['fullname'] ?? 'Admin'; ?></div>
    </div>
    <ul class="sidebar-menu">
        <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"><i class="nav-icon fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
        <li><a href="levels.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'levels.php' ? 'active' : ''; ?>"><i class="nav-icon fas fa-layer-group"></i> <span>Quản lý Levels</span></a></li>
        <li class="nav-item">
            <a href="question_bank.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'question_bank.php' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-database"></i>
                <p>Ngân hàng Câu hỏi</p>
            </a>
        </li>
        <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'thi2004'): ?>
            <li class="nav-item">
                <a href="lab_manager.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'lab_manager.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-flask"></i>
                    <p>Quản lý Labs</p>
                </a>
            </li>
        <?php endif; ?>
        <li><a href="exams.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'exams.php' ? 'active' : ''; ?>"><i class="nav-icon fas fa-book"></i> <span>Quản lý Đề thi</span></a></li>
        <li><a href="users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>"><i class="nav-icon fas fa-users"></i> <span>Quản lý Thành viên</span></a></li>
        <li><a href="results.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'results.php' ? 'active' : ''; ?>"><i class="nav-icon fas fa-chart-bar"></i> <span>Kết quả thi</span></a></li>
        <li><a href="news.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'news.php' ? 'active' : ''; ?>"><i class="nav-icon fas fa-newspaper"></i> <span>Quản lý Tin tức</span></a></li>
        <li><a href="reviews.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reviews.php' ? 'active' : ''; ?>"><i class="nav-icon fas fa-star"></i> <span>Quản lý Đánh giá</span></a></li>
        <li><a href="contacts.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contacts.php' ? 'active' : ''; ?>"><i class="nav-icon fas fa-headset"></i> <span>Quản lý Hỗ trợ</span></a></li>
        <!-- Commented out results until implemented -->

        <li style="margin-top: 2rem; border-top: 1px solid #4b545c; padding-top: 1rem;">
            <a href="../index.php"><i class="nav-icon fas fa-home"></i> <span>Xem Website</span></a>
            <a href="../logout.php"><i class="nav-icon fas fa-sign-out-alt"></i> <span>Đăng xuất</span></a>
        </li>
    </ul>
</aside>