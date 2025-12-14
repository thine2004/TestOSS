<?php
require_once 'config.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($name && $email && $message) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contacts (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $name, $email, $subject, $message]);
            $success = true;
        } catch (PDOException $e) {
            $error = "Lỗi gửi tin nhắn. Vui lòng thử lại sau.";
        }
    } else {
        $error = "Vui lòng điền đầy đủ thông tin.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Liên Hệ | English Mastery</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/landing.css">
</head>

<body style="display: block; background: #f9fafb;">

    <?php require_once 'includes/navbar_public.php';
    renderNav(isLoggedIn()); ?>

    <div class="section-header" style="margin-top: 4rem;">
        <h2 class="section-title">Liên Hệ Hỗ Trợ</h2>
        <p class="section-desc">Chúng tôi luôn sẵn sàng giải đáp mọi thắc mắc của bạn.</p>
    </div>

    <div class="section" style="padding-top: 0;">
        <div class="grid-2" style="align-items: start;">
            <!-- Contact Info -->
            <div>
                <div class="card">
                    <h3 style="margin-bottom: 1.5rem;">Thông tin liên hệ</h3>
                    <div style="margin-bottom: 1rem; display: flex; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: #e0e7ff; color: var(--primary); border-radius: 8px; display: grid; place-items: center;"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <div style="font-weight: 600;">Địa chỉ</div>
                            <div style="color: #6b7280;">Tầng 5, Tòa nhà TechBlock, TP.HCM</div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem; display: flex; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: #e0e7ff; color: var(--primary); border-radius: 8px; display: grid; place-items: center;"><i class="fas fa-envelope"></i></div>
                        <div>
                            <div style="font-weight: 600;">Email</div>
                            <div style="color: #6b7280;">contact@englishmastery.com</div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem; display: flex; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: #e0e7ff; color: var(--primary); border-radius: 8px; display: grid; place-items: center;"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <div style="font-weight: 600;">Hotline</div>
                            <div style="color: #6b7280;">1900 1234 56</div>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div class="card" style="padding: 0; overflow: hidden; height: 300px;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.424605658931!2d106.69848931533411!3d10.7797854620963!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f36048d00d9%3A0xe96c469502773c2!2sDinh%20Doc%20Lap!5e0!3m2!1sen!2s!4v1646282866952!5m2!1sen!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="card">
                <h3 style="margin-bottom: 1.5rem;">Gửi tin nhắn</h3>

                <?php if ($success): ?>
                    <div style="padding: 1rem; background: #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 1rem;">
                        <i class="fas fa-check-circle"></i> Tin nhắn của bạn đã được gửi thành công!
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div style="padding: 1rem; background: #fee2e2; color: #b91c1c; border-radius: 8px; margin-bottom: 1rem;">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Họ tên</label>
                            <input type="text" name="name" class="form-control" style="width: 100%; padding: 0.8rem; border: 1px solid #d1d5db; border-radius: 8px;" required>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email</label>
                            <input type="email" name="email" class="form-control" style="width: 100%; padding: 0.8rem; border: 1px solid #d1d5db; border-radius: 8px;" required>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Chủ đề</label>
                        <select name="subject" style="width: 100%; padding: 0.8rem; border: 1px solid #d1d5db; border-radius: 8px;">
                            <option>Hỗ trợ kỹ thuật</option>
                            <option>Tư vấn khóa học</option>
                            <option>Góp ý hệ thống</option>
                            <option>Khác</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Nội dung</label>
                        <textarea name="message" rows="5" style="width: 100%; padding: 0.8rem; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit;" required></textarea>
                    </div>
                    <button type="submit" class="btn" style="width: 100%;">Gửi Liên Hệ</button>
                </form>
            </div>
        </div>
    </div>
    <?php require_once 'includes/footer.php'; ?>
</body>

</html>