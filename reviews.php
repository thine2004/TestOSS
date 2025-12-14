<?php
require_once 'config.php';

// Handle Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if (!$name) $name = 'Ẩn danh';
    $rating = (int) $_POST['rating'];
    $comment = trim($_POST['comment']);

    if ($rating < 1) $rating = 1;
    if ($rating > 5) $rating = 5;

    if ($comment) {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_name, rating, comment) VALUES (?, ?, ?)");
        $stmt->execute([$name, $rating, $comment]);
        // Auto-approve for demo or require admin? 
        // User said "allow anyone to review". Let's assume moderation is key so default is visible (user requirement was vague on moderation policy but "management" implies moderation). 
        // My table schema had default visible=1. So it shows immediately.
        setFlash('success', 'Cảm ơn bạn đã gửi đánh giá!');
        header("Location: reviews.php");
        exit;
    }
}

// Fetch Reviews
$reviews = $pdo->query("SELECT * FROM reviews WHERE is_visible = 1 ORDER BY created_at DESC")->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Đánh giá & Phản hồi | English Mastery</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="css/reviews.css">
</head>

<body style="display: block; background: #f9fafb;">

    <?php require_once 'includes/navbar_public.php';
    renderNav(isLoggedIn()); ?>

    <div class="section-header" style="margin-top: 5rem;">
        <h2 class="section-title">Đánh giá từ Cộng đồng</h2>
        <p class="section-desc">Chia sẻ trải nghiệm của bạn và xem mọi người nói gì về chúng tôi.</p>
    </div>

    <div class="section" style="padding-top: 0;">
        <div class="grid" style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem; align-items: start;">

            <!-- List Reviews -->
            <div>
                <?php if (empty($reviews)): ?>
                    <div style="text-align: center; color: #999; padding: 2rem;">
                        <i class="far fa-comment-dots" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <p>Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
                    </div>
                <?php endif; ?>

                <?php foreach ($reviews as $r): ?>
                    <div class="review-card">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <div style="font-weight: 600; font-size: 1.1rem;">
                                <i class="fas fa-user-circle" style="color: #ccc;"></i> <?php echo htmlspecialchars($r['user_name']); ?>
                            </div>
                            <div class="rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star" style="opacity: <?php echo $i <= $r['rating'] ? '1' : '0.3'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div style="color: #4b5563; line-height: 1.6;">
                            <?php echo nl2br(htmlspecialchars($r['comment'])); ?>
                        </div>
                        <div style="margin-top: 0.8rem; font-size: 0.8rem; color: #9ca3af;">
                            Đăng ngày <?php echo date('d/m/Y', strtotime($r['created_at'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Review Form -->
            <div class="review-form-card">
                <h3 style="margin-top: 0; margin-bottom: 1rem;"><i class="fas fa-pen"></i> Viết đánh giá</h3>
                <form method="POST">
                    <label style="font-size: 0.9rem; margin-bottom: 0.3rem; display: block;">Tên của bạn (Tùy chọn)</label>
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên...">

                    <label style="font-size: 0.9rem; margin-bottom: 0.3rem; display: block;">Đánh giá</label>
                    <select name="rating" class="form-control">
                        <option value="5">⭐⭐⭐⭐⭐ (Tuyệt vời)</option>
                        <option value="4">⭐⭐⭐⭐ (Tốt)</option>
                        <option value="3">⭐⭐⭐ (Bình thường)</option>
                        <option value="2">⭐⭐ (Tệ)</option>
                        <option value="1">⭐ (Rất tệ)</option>
                    </select>

                    <label style="font-size: 0.9rem; margin-bottom: 0.3rem; display: block;">Nội dung</label>
                    <textarea name="comment" class="form-control" rows="4" placeholder="Chia sẻ cảm nghĩ..." required></textarea>

                    <button type="submit" class="btn-submit">Gửi Đánh Giá</button>
                </form>
            </div>

        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>

</body>

</html>