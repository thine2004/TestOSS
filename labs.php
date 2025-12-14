<?php
require_once 'config.php';

// Fetch Current User Info
$userId = $_SESSION['user_id'] ?? 0;
$userAvatar = 'images/avatar.png';
$userFullname = 'Sinh Viên';

if ($userId) {
    try {
        $stmt = $pdo->prepare("SELECT fullname, avatar FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if ($user) {
            $userFullname = $user['fullname'];
            if (!empty($user['avatar']) && file_exists('images/' . $user['avatar'])) {
                $userAvatar = 'images/' . $user['avatar'];
            }
        }
    } catch (Exception $e) {
        // Build generic fallback
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lab Thực Hành | English Mastery</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/landing.css?v=<?php echo time(); ?>">
    <style>
        .lab-header {
            background: linear-gradient(120deg, #6366f1 0%, #a855f7 100%);
            color: white;
            padding: 3rem 1rem 6rem 1rem;
            margin-bottom: -3rem;
            position: relative;
            overflow: hidden;
        }

        /* Abstract circles for uniqueness */
        .lab-header::before,
        .lab-header::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .lab-header::before {
            top: -50px;
            left: -50px;
        }

        .lab-header::after {
            bottom: -50px;
            right: -50px;
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 2rem;
            max-width: 1100px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .profile-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            /* Circular avatar */
            border: 4px solid rgba(255, 255, 255, 0.4);
            background: #fff;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .profile-info h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            line-height: 1.1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .lab-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1rem 3rem 1rem;
            margin-top: -3rem;
            position: relative;
            z-index: 10;
        }

        .lab-layout {
            display: grid;
            grid-template-columns: 2.5fr 1fr;
            gap: 2rem;
        }

        /* NEW "Playlist"/"Syllabus" Style for Labs */
        .lab-list-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 1rem;
        }

        .lab-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            padding: 1.2rem 1.5rem;
            margin-bottom: 0.8rem;
            border-radius: 12px;
            border: 1px solid #f3f4f6;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .lab-item:hover {
            transform: translateX(5px);
            border-color: #c4b5fd;
            background: #fdfcff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* Left accent bar on hover */
        .lab-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #8b5cf6;
            opacity: 0;
            transition: 0.2s;
        }

        .lab-item:hover::before {
            opacity: 1;
        }

        .lab-content {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }

        .lab-icon-box {
            width: 48px;
            height: 48px;
            background: #f3f4f6;
            color: #8b5cf6;
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 1.2rem;
            transition: 0.3s;
        }

        .lab-item:hover .lab-icon-box {
            background: #8b5cf6;
            color: white;
            transform: scale(1.1) rotate(5deg);
        }

        .lab-title {
            font-weight: 700;
            font-size: 1.05rem;
            color: #1f2937;
            margin-bottom: 0.2rem;
        }

        .lab-subtitle {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid #e5e7eb;
            display: grid;
            place-items: center;
            color: #9ca3af;
            transition: 0.2s;
        }

        .lab-item:hover .btn-action {
            background: #8b5cf6;
            border-color: #8b5cf6;
            color: white;
        }

        .sidebar-box {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0, 0, 0, 0.02);
        }

        .contact-link {
            display: flex;
            padding: 0.8rem;
            align-items: center;
            gap: 1rem;
            border-radius: 10px;
            transition: 0.2s;
            text-decoration: none;
            color: #4b5563;
        }

        .contact-link:hover {
            background: #f3f4f6;
            color: #111827;
        }

        @media (max-width: 768px) {
            .lab-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body style="background: #f3f4f6;">

    <?php require_once 'includes/navbar_public.php';
    renderNav(isLoggedIn()); ?>

    <div class="lab-header">
        <div class="profile-card">
            <div class="profile-avatar">
                <!-- Dynamic Avatar -->
                <img src="<?php echo htmlspecialchars($userAvatar) . '?v=' . time(); ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <i class="fas fa-user-graduate" style="display: none; font-size: 3rem; color: #333;"></i>
            </div>
            <div class="profile-info">
                <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 0.2rem; text-transform: uppercase; letter-spacing: 1px;">Thông tin sinh viên</div>
                <h1>Đặng Trường Thi</h1>
                <div style="opacity: 0.9;">Khoa Công Nghệ Thông Tin - Thực hành Lập trình Web</div>

                <div class="profile-badges">
                    <div class="p-badge"><i class="far fa-id-card"></i> DH52201479</div>
                    <div class="p-badge"><i class="fas fa-layer-group"></i> D22_TH10</div>
                    <div class="p-badge"><i class="fas fa-code-branch"></i> Nhóm 18 - Thứ 7 Ca 4</div>
                </div>
            </div>
        </div>
    </div>

    <div class="lab-container">

        <div class="lab-layout">

            <!-- Left Column: Syllabus List -->
            <div>
                <div class="lab-list-container">
                    <div style="padding-bottom: 1rem; border-bottom: 1px solid #f3f4f6; margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
                        <h3 style="margin: 0; font-size: 1.2rem; display: flex; align-items: center; gap: 0.8rem;">
                            <span style="width: 32px; height: 32px; background: #ede9fe; color: #8b5cf6; border-radius: 8px; display: grid; place-items: center; font-size: 1rem;"><i class="fas fa-list-ul"></i></span>
                            Danh sách bài tập
                        </h3>
                        <span style="font-size: 0.85rem; color: #9ca3af;">8 Buổi học</span>
                    </div>

                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <a href="lab_detail.php?id=<?php echo $i; ?>" class="lab-item" style="text-decoration: none;">
                            <div class="lab-content">
                                <div class="lab-icon-box">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <div>
                                    <div class="lab-title">Bài tập thực hành Buổi <?php echo $i; ?></div>
                                    <div class="lab-subtitle">Các bài tập và code mẫu cho buổi <?php echo $i; ?></div>
                                </div>
                            </div>
                            <div class="btn-action">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="lab-sidebar">

                <!-- Connect Links -->
                <div class="sidebar-box">
                    <div style="display: flex; align-items: center; gap: 0.8rem; margin-bottom: 1rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 0.8rem;">
                        <span style="width: 32px; height: 32px; background: #f3f4f6; color: #4b5563; border-radius: 50%; display: grid; place-items: center;"><i class="fas fa-link"></i></span>
                        <h3 style="margin: 0; font-size: 1.1rem;">Kết nối</h3>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <a href="https://github.com/thine2004" class="contact-link">
                            <i class="fab fa-github" style="width: 20px; text-align: center;"></i>
                            <span style="flex: 1;">Github</span>
                        </a>
                        <a href="https://www.facebook.com/angtruongthi.2025/" class="contact-link">
                            <i class="fab fa-facebook" style="width: 20px; text-align: center; color: #1877f2;"></i>
                            <span style="flex: 1;">Facebook</span>
                        </a>
                        <a href="#" class="contact-link">
                            <i class="fab fa-instagram" style="width: 20px; text-align: center; color: #e1306c;"></i>
                            <span style="flex: 1;">Instagram</span>
                        </a>
                        <a href="#" class="contact-link">
                            <i class="fab fa-tiktok" style="width: 20px; text-align: center; color: #000;"></i>
                            <span style="flex: 1;">Tiktok</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <?php require_once 'includes/footer.php'; ?>
</body>

</html>