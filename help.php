<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Hỗ trợ người dùng | English Mastery</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/landing.css?v=<?php echo time(); ?>">
</head>

<body style="background: #f9fafb;">

    <?php require_once 'includes/navbar_public.php';
    renderNav(isLoggedIn()); ?>

    <link rel="stylesheet" href="css/help.css">

    <div class="section" style="max-width: 1200px; margin: 0 auto; margin-top: 4rem; padding: 0 2rem; padding-bottom: 4rem;">
        <div class="faq-header">
            <div style="font-size: 0.9rem; color: #10b981; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 0.5rem;">Hỗ trợ khách hàng</div>
            <h2>Câu hỏi thường gặp</h2>
            <p>Giải đáp những thắc mắc phổ biến nhất để giúp bạn sử dụng English Mastery hiệu quả hơn.</p>
        </div>

        <div class="faq-grid">
            <!-- Card 1 -->
            <div class="faq-card">
                <div class="faq-icon"><i class="fas fa-key"></i></div>
                <div class="faq-content">
                    <h3>Tôi quên mật khẩu đăng nhập?</h3>
                    <p>Hiện tại hệ thống chưa hỗ trợ tự đặt lại mật khẩu. Vui lòng liên hệ Admin qua trang <b>Liên hệ</b> hoặc gửi email về support@englishstudy.vn để được cấp lại.</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="faq-card">
                <div class="faq-icon"><i class="fas fa-layer-group"></i></div>
                <div class="faq-content">
                    <h3>Các cấp độ (Level) phân chia thế nào?</h3>
                    <p>Hệ thống cung cấp lộ trình từ sơ cấp đến nâng cao (Starters, Movers... IELTS). Bạn có thể chọn khóa học phù hợp tại trang <b>Lộ trình</b>.</p>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="faq-card">
                <div class="faq-icon"><i class="fas fa-clipboard-check"></i></div>
                <div class="faq-content">
                    <h3>Kết quả thi tính điểm ra sao?</h3>
                    <p>Mỗi câu đúng sẽ được tính điểm theo thang điểm của đề (thường là thang 10). Bạn sẽ nhận kết quả và đáp án chi tiết ngay sau khi nộp bài.</p>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="faq-card">
                <div class="faq-icon"><i class="fas fa-history"></i></div>
                <div class="faq-content">
                    <h3>Tôi có thể xem lại bài thi đã làm?</h3>
                    <p>Có. Mọi bài thi đã làm đều lưu trong <b>Lịch sử học tập</b>. Bạn có thể xem lại điểm và làm lại để cải thiện thành tích.</p>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="faq-card">
                <div class="faq-icon"><i class="fas fa-mobile-alt"></i></div>
                <div class="faq-content">
                    <h3>Tôi có thể học trên điện thoại không?</h3>
                    <p>Được. Giao diện English Mastery tối ưu cho mọi thiết bị (PC, tablet, mobile), giúp bạn học mọi lúc mọi nơi một cách thuận tiện.</p>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="faq-card">
                <div class="faq-icon"><i class="fas fa-headset"></i></div>
                <div class="faq-content">
                    <h3>Tôi cần hỗ trợ trực tiếp?</h3>
                    <p>Đội ngũ hỗ trợ hoạt động 24/7. Hãy sử dụng form tại trang <b>Liên hệ</b> hoặc gọi hotline <b>1900 1234</b> để được giải đáp nhanh nhất.</p>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</body>

</html>