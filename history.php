<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('index.php');

$user_id = $_SESSION['user_id'];
require_once 'includes/header_app.php';

// Fetch All History
$stmt = $pdo->prepare("SELECT r.*, e.title, c.name as cat_name FROM results r JOIN exams e ON r.exam_id = e.id LEFT JOIN categories c ON e.category_id = c.id WHERE r.user_id = ? ORDER BY r.completed_at DESC");
$stmt->execute([$user_id]);
$history = $stmt->fetchAll();
?>

<div class="card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
            <tr>
                <th style="padding: 1rem; text-align: left;">Đề thi</th>
                <th style="padding: 1rem; text-align: left;">Level</th>
                <th style="padding: 1rem; text-align: left;">Ngày làm</th>
                <th style="padding: 1rem; text-align: left;">Kết quả</th>
                <th style="padding: 1rem; text-align: left;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($history as $h): ?>
                <tr style="border-bottom: 1px solid #f3f4f6;">
                    <td style="padding: 1rem; font-weight: 500;"><?php echo htmlspecialchars($h['title']); ?></td>
                    <td style="padding: 1rem;"><span class="badge"><?php echo htmlspecialchars($h['cat_name']); ?></span></td>
                    <td style="padding: 1rem; color: #6b7280;"><?php echo date('d/m/Y H:i', strtotime($h['completed_at'])); ?></td>
                    <td style="padding: 1rem; font-weight: 700; color: var(--primary);">
                        <?php echo $h['total_correct']; ?> <span style="font-size: 0.8rem; color: #9ca3af; font-weight: 400;">đúng</span>
                        <div style="font-size: 0.8rem; color: #10b981;"><?php echo $h['score']; ?> pts</div>
                    </td>
                    <td style="padding: 1rem;">
                        <a href="test.php?id=<?php echo $h['exam_id']; ?>" class="btn btn-outline" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;">Làm lại</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($history)): ?>
                <tr>
                    <td colspan="5" style="padding: 2rem; text-align: center; color: #9ca3af;">Bạn chưa làm bài thi nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</main>
</div>
</body>

</html>