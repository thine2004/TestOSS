<?php
require_once '../config.php';
require_once 'includes/header.php';

// --- STATS COUNT ---
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$examCount = $pdo->query("SELECT COUNT(*) FROM exams")->fetchColumn();
$questionCount = $pdo->query("SELECT COUNT(*) FROM questions")->fetchColumn();
$resultCount = $pdo->query("SELECT COUNT(*) FROM results")->fetchColumn();

// New features stats
try {
    $newsCount = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
} catch (Exception $e) {
    $newsCount = 0;
}
try {
    $reviewCount = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
    $msgCount = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status='new'")->fetchColumn();
} catch (Exception $e) {
    $reviewCount = 0;
    $msgCount = 0;
}


// --- RECENT DATA ---
// Recent Users
$latestUsers = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Recent Results
$latestResults = $pdo->query("SELECT r.*, u.username, e.title 
                              FROM results r 
                              JOIN users u ON r.user_id = u.id 
                              JOIN exams e ON r.exam_id = e.id 
                              ORDER BY r.completed_at DESC LIMIT 5")->fetchAll();

?>

<link rel="stylesheet" href="css/dashboard.css">

<div style="margin-bottom: 2rem;">
    <h2 style="font-weight: 800; color: #1f2937; margin: 0;">Tổng quan hệ thống</h2>
    <p style="color: #6b7280;">Chào mừng trở lại, <?php echo $_SESSION['fullname'] ?? 'Admin'; ?>!</p>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">

    <div class="stat-card">
        <div class="stat-info">
            <h3><?php echo $userCount; ?></h3>
            <p>Thành viên</p>
        </div>
        <div class="stat-icon" style="background: #e0e7ff; color: #4338ca;">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3><?php echo $examCount; ?></h3>
            <p>Đề Thi</p>
        </div>
        <div class="stat-icon" style="background: #dcfce7; color: #15803d;">
            <i class="fas fa-book"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3><?php echo $questionCount; ?></h3>
            <p>Câu hỏi</p>
        </div>
        <div class="stat-icon" style="background: #fef3c7; color: #b45309;">
            <i class="fas fa-database"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3><?php echo $resultCount; ?></h3>
            <p>Lượt làm bài</p>
        </div>
        <div class="stat-icon" style="background: #fee2e2; color: #b91c1c;">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>

</div>

<!-- Second Row Stats (Notifications) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    <a href="contacts.php" style="text-decoration: none;">
        <div class="stat-card" style="border-left: 5px solid #ef4444;">
            <div class="stat-info">
                <h3 style="color: #ef4444;"><?php echo $msgCount; ?></h3>
                <p>Tin nhắn mới</p>
            </div>
            <div class="stat-icon" style="background: #fff5f5; color: #ef4444;">
                <i class="fas fa-envelope"></i>
            </div>
        </div>
    </a>

    <a href="news.php" style="text-decoration: none;">
        <div class="stat-card">
            <div class="stat-info">
                <h3><?php echo $newsCount; ?></h3>
                <p>Tin tức</p>
            </div>
            <div class="stat-icon" style="background: #f3f4f6; color: #374151;">
                <i class="fas fa-newspaper"></i>
            </div>
        </div>
    </a>

    <a href="reviews.php" style="text-decoration: none;">
        <div class="stat-card">
            <div class="stat-info">
                <h3><?php echo $reviewCount; ?></h3>
                <p>Đánh giá</p>
            </div>
            <div class="stat-icon" style="background: #fffff0; color: #f59e0b;">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </a>
</div>


<!-- Activity Lists -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">

    <!-- Recent Results -->
    <div class="panel">
        <div class="panel-header">
            <h3 class="panel-title"><i class="fas fa-history" style="color: #6b7280;"></i> Kết quả thi gần đây</h3>
            <a href="results.php" style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 500;">Xem tất cả &rarr;</a>
        </div>
        <div class="panel-body">
            <?php foreach ($latestResults as $lr): ?>
                <div class="list-item">
                    <div>
                        <div style="font-weight: 600; color: #111827;"><?php echo htmlspecialchars($lr['username']); ?></div>
                        <div style="font-size: 0.85rem; color: #6b7280;"><?php echo htmlspecialchars($lr['title']); ?></div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 700; color: <?php echo $lr['score'] >= 50 ? '#10b981' : '#ef4444'; ?>;">
                            <?php echo $lr['score']; ?> pts
                        </div>
                        <div style="font-size: 0.8rem; color: #9ca3af;"><?php echo date('d/m H:i', strtotime($lr['completed_at'])); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($latestResults)): ?>
                <div style="padding: 2rem; text-align: center; color: #9ca3af;">Chưa có dữ liệu.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- New Users & Quick Actions -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        <!-- Quick Actions -->
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Truy cập nhanh</h3>
            </div>
            <div class="panel-body" style="padding: 1rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                <a href="question_bank.php?action=add" class="admin-btn btn-primary" style="text-align: center; justify-content: center;"><i class="fas fa-plus"></i> Thêm câu hỏi</a>
                <a href="exams.php" class="admin-btn btn-success" style="text-align: center; justify-content: center;"><i class="fas fa-plus"></i> Tạo đề thi</a>
                <a href="news.php" class="admin-btn btn-info" style="text-align: center; justify-content: center;"><i class="fas fa-pen"></i> Viết tin tức</a>
                <a href="users.php" class="admin-btn btn-warning" style="text-align: center; justify-content: center;"><i class="fas fa-user-plus"></i> QL Users</a>
            </div>
        </div>

        <!-- New Users -->
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title"><i class="fas fa-user-clock" style="color: #6b7280;"></i> Thành viên mới</h3>
            </div>
            <div class="panel-body">
                <?php foreach ($latestUsers as $lu): ?>
                    <div class="list-item">
                        <div style="display: flex; align-items: center; gap: 0.8rem;">
                            <div style="width: 32px; height: 32px; background: #f3f4f6; border-radius: 50%; display: grid; place-items: center; color: #6b7280;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: 0.95rem;"><?php echo htmlspecialchars($lu['username']); ?></div>
                                <div style="font-size: 0.8rem; color: #9ca3af;"><?php echo date('d/m/Y', strtotime($lu['created_at'])); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</div>

<?php require_once 'includes/footer.php'; ?>