<?php
require_once 'config.php';
// If logged in, maybe show "Go to Dashboard" or just keep public landing
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>English Mastery - Luyện thi IELTS & TOEIC Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/landing.css?v=<?php echo time(); ?>">
</head>

<body style="display: block; background: white;">

    <!-- NAVIGATION -->
    <?php require_once 'includes/navbar_public.php';
    renderNav(isLoggedIn()); ?>

    <!-- HERO SECTION -->
    <header class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Nền tảng ôn luyện IELTS & TOEIC <br>Dành cho người mới bắt đầu</h1>
            <p class="hero-subtitle">Hệ thống bài thi trắc nghiệm chuẩn quốc tế, lộ trình rõ ràng và thống kê chi tiết giúp bạn chinh phục mọi kỳ thi.</p>
            <div>
                <a href="<?php echo isLoggedIn() ? 'dashboard.php' : 'login.php'; ?>" class="hero-btn hero-btn-primary"><i class="fas fa-rocket"></i> Bắt đầu ngay</a>
            </div>
        </div>
    </header>

    <!-- STATS SECTION -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-number">5,000+</div>
            <div class="stat-label">Học viên</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
            <div class="stat-number">1,200+</div>
            <div class="stat-label">Đề thi</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">98%</div>
            <div class="stat-label">Hài lòng</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-certificate"></i></div>
            <div class="stat-number">500+</div>
            <div class="stat-label">Chứng chỉ</div>
        </div>
    </div>

    <!-- FEATURES -->
    <section id="features" class="section">
        <div class="section-header">
            <h2 class="section-title">Tại sao chọn English Mastery?</h2>
            <p class="section-desc">Chúng tôi tập trung vào trải nghiệm làm bài thi thực tế với giao diện hiện đại và báo cáo chi tiết.</p>
        </div>
        <div class="grid">
            <div class="feature-box">
                <div class="feature-icon"><i class="fas fa-laptop-code"></i></div>
                <h3>Thi thử như thật</h3>
                <p style="color: #6b7280; margin-top: 0.5rem;">Giao diện làm bài thi trắc nghiệm được tối ưu hóa, có bấm giờ và chấm điểm tự động ngay lập tức.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <h3>Thống kê chi tiết</h3>
                <p style="color: #6b7280; margin-top: 0.5rem;">Theo dõi sự tiến bộ của bạn qua từng bài thi. Xem lại lịch sử và phân tích lỗi sai.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                <h3>Học mọi lúc mọi nơi</h3>
                <p style="color: #6b7280; margin-top: 0.5rem;">Tương thích hoàn hảo trên mọi thiết bị: Máy tính, máy tính bảng và điện thoại.</p>
            </div>
        </div>
    </section>

    <!-- ROADMAP TEASER (Using actual categories) -->
    <section id="roadmap" class="section" style="background: #f9fafb; border-radius: 20px;">
        <div class="section-header">
            <h2 class="section-title">Lộ trình học tập</h2>
            <p class="section-desc">Chọn cấp độ phù hợp với mục tiêu của bạn.</p>
        </div>
        <div class="grid">
            <!-- Static for Landing Page Visuals but linked to logic -->
            <div class="step-card">
                <div class="step-number">01</div>
                <div>
                    <h3>IELTS Foundation</h3>
                    <p style="color: #6b7280;">Làm quen với các dạng bài Reading & Listening cơ bản.</p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-number">02</div>
                <div>
                    <h3>TOEIC Practice</h3>
                    <p style="color: #6b7280;">Luyện đề sát với thực tế, tăng tốc độ làm bài.</p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-number">03</div>
                <div>
                    <h3>Advanced Test</h3>
                    <p style="color: #6b7280;">Chinh phục band điểm cao với các bài thi nâng cao.</p>
                </div>
            </div>
        </div>

    </section>

    <!-- FOOTER -->
    <?php require_once 'includes/footer.php'; ?>

</body>

</html>