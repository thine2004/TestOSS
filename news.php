<?php
require_once 'config.php';

// Fetch News
$news = []; // Default empty
try {
    $news = $pdo->query("SELECT * FROM news ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) { /* Ignore if table missing */
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tin Tức | English Mastery</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/landing.css">
</head>

<body style="display: block; background: #f9fafb;">

    <?php require_once 'includes/navbar_public.php';
    renderNav(isLoggedIn()); ?>

    <div class="section-header" style="margin-top: 4rem;">
        <h2 class="section-title">Tin Tức & Sự Kiện</h2>
        <p class="section-desc">Cập nhật thông tin mới nhất về kỳ thi và phương pháp học tập.</p>
    </div>

    <div class="section" style="padding-top: 0;">
        <div class="grid">
            <?php foreach ($news as $n): ?>
                <div class="card" style="padding: 0; overflow: hidden; border: 1px solid #e5e7eb;">
                    <div style="height: 200px; background: #e0e7ff; display: grid; place-items: center; color: var(--primary); overflow: hidden;">
                        <?php if ($n['image']): ?>
                            <img src="images/news/<?php echo htmlspecialchars($n['image']); ?>?v=<?php echo time(); ?>" style="width:100%; height:100%; object-fit:cover;">
                        <?php else: ?>
                            <i class="fas fa-newspaper" style="font-size: 3rem;"></i>
                        <?php endif; ?>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div style="font-size: 0.8rem; color: #6b7280; margin-bottom: 0.5rem;"><i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y', strtotime($n['created_at'])); ?></div>
                        <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;"><a href="news_detail.php?id=<?php echo $n['id']; ?>" style="color: inherit;"><?php echo htmlspecialchars($n['title']); ?></a></h3>
                        <p style="color: #6b7280; line-height: 1.6; font-size: 0.95rem;"><?php echo htmlspecialchars($n['summary']); ?></p>
                        <a href="news_detail.php?id=<?php echo $n['id']; ?>" style="display: block; margin-top: 1rem; color: var(--primary); font-weight: 600;">Xem chi tiết &rarr;</a>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($news)): ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #9ca3af;">
                    <i class="fas fa-newspaper" style="font-size: 3rem; margin-bottom: 1rem; display:block;"></i>
                    Hiện chưa có tin tức nào.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>

</body>

</html>