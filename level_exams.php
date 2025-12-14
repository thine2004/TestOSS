<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('index.php');

$catId = $_GET['id'] ?? null;
if (!$catId) redirect('roadmap.php');

// Fetch Category
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$catId]);
$cat = $stmt->fetch();
if (!$cat) redirect('roadmap.php');

require_once 'includes/header_app.php';

// Fetch Exams
$exams = $pdo->query("SELECT * FROM exams WHERE category_id=" . $catId)->fetchAll();
?>

<div style="margin-bottom: 2rem;">
    <a href="roadmap.php" style="color: #6b7280; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách Level
    </a>
    <h2 style="font-size: 1.8rem; font-weight: 700; color: #1f2937; margin: 0;"><?php echo htmlspecialchars($cat['name']); ?></h2>
    <p style="color: #6b7280; margin-top: 0.5rem;"><?php echo htmlspecialchars($cat['description']); ?></p>
</div>

<div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
    <?php foreach ($exams as $ex):
        $user_id = $_SESSION['user_id'];
        $taken = $pdo->query("SELECT MAX(score) FROM results WHERE user_id=$user_id AND exam_id=" . $ex['id'])->fetchColumn();
        // Calc real question count
        $realQCount = $pdo->query("SELECT COUNT(*) FROM questions WHERE exam_id=" . $ex['id'])->fetchColumn();
    ?>
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #f3f4f6; position: relative;">
            <!-- Status Badge -->
            <?php if ($taken !== false): ?>
                <div style="position: absolute; top: 1rem; right: 1rem; color: #10b981; background: #ecfdf5; padding: 0.2rem 0.5rem; border-radius: 99px; font-size: 0.75rem; font-weight: 600;">Completed</div>
            <?php endif; ?>

            <h4 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; color: #111827; padding-right: 2rem;"><?php echo htmlspecialchars($ex['title']); ?></h4>

            <div style="margin-bottom: 0.8rem;">
                <?php
                $type = $ex['type'] ?? 'mixed';
                if ($type === 'audio') {
                    echo '<span style="display:inline-block; padding:2px 8px; border-radius:12px; font-size:0.75rem; background:#e0f2fe; color:#0369a1; font-weight:600;"><i class="fas fa-headphones"></i> Luyện nghe</span>';
                } elseif ($type === 'image') {
                    echo '<span style="display:inline-block; padding:2px 8px; border-radius:12px; font-size:0.75rem; background:#fef3c7; color:#b45309; font-weight:600;"><i class="fas fa-images"></i> Flashcard</span>';
                } else {
                    echo '<span style="display:inline-block; padding:2px 8px; border-radius:12px; font-size:0.75rem; background:#f3f4f6; color:#4b5563; font-weight:600;"><i class="fas fa-layer-group"></i> Tổng hợp</span>';
                }
                ?>
            </div>

            <div style="font-size: 0.85rem; color: #6b7280; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <span><i class="far fa-clock"></i> <?php echo $ex['duration_minutes']; ?> phút</span>
                <span><i class="far fa-question-circle"></i> <?php echo $realQCount; ?> câu</span>
            </div>

            <a href="test.php?id=<?php echo $ex['id']; ?>" class="btn" style="width: 100%; display: flex; justify-content: center; align-items: center; gap: 0.5rem;">
                Làm bài thi <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    <?php endforeach; ?>

    <?php if (empty($exams)): ?>
        <div style="grid-column: 1/-1; text-align: center; color: #9ca3af; padding: 3rem;">
            <i class="fas fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
            Chưa có đề thi nào trong Level này.
        </div>
    <?php endif; ?>
</div>

</main>
</div>
</body>

</html>