<?php
require_once 'config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('news.php');
}

$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$news = $stmt->fetch();

if (!$news) {
    echo "News not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($news['title']); ?> | English Mastery</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="css/news.css">
</head>

<body style="display: block; background: #f9fafb;">

    <?php require_once 'includes/navbar_public.php';
    renderNav(isLoggedIn()); ?>

    <div class="container" style="max-width: 900px; margin: 0 auto; padding: 2rem; margin-top: 4rem;">

        <a href="news.php" style="color: #6b7280; text-decoration: none; display: inline-block; margin-bottom: 1rem;">
            <i class="fas fa-arrow-left"></i> Quay lại Tin tức
        </a>

        <article style="background: white; padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">
                <i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y H:i', strtotime($news['created_at'])); ?>
            </div>

            <h1 style="font-size: 2rem; color: #111827; margin-bottom: 1.5rem; line-height: 1.3;">
                <?php echo htmlspecialchars($news['title']); ?>
            </h1>

            <?php if ($news['image']): ?>
                <div style="margin-bottom: 2rem;">
                    <img src="images/news/<?php echo $news['image'] . '?v=' . time(); ?>" style="width: 100%; max-height: 500px; object-fit: cover; border-radius: 8px;">
                </div>
            <?php endif; ?>

            <div class="news-content">
                <div style="font-weight: 500; font-size: 1.1rem; color: #4b5563; margin-bottom: 2rem; border-left: 4px solid var(--primary); padding-left: 1rem;">
                    <?php echo nl2br(htmlspecialchars($news['summary'])); ?>
                </div>

                <div>
                    <?php
                    // Allow basic HTML or just nl2br? Admin form said HTML supported but simplistic.
                    // For safety, let's keep it simple or minimal safe HTML.
                    // Given the prompt "add management", usually implies rich text but I used textarea.
                    // I'll assume raw content if they put HTML tags, but usually htmlspecialchars is safe.
                    // If the user wants rich text, they'd use TinyMCE. I am just using textarea.
                    // So nl2br is safer.
                    echo nl2br(htmlspecialchars($news['content']));
                    ?>
                </div>
            </div>

        </article>

    </div>

    <?php require_once 'includes/footer.php'; ?>

</body>

</html>