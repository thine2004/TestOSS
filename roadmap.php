<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('index.php');

$user_id = $_SESSION['user_id'];
require_once 'includes/header_app.php';

// Fetch Categories
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">Chọn Level để học</h2>
    <p style="color: #6b7280;">Mỗi level có bộ từ vựng và bài luyện tập riêng.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 1.5rem;">
    <?php foreach ($cats as $index => $cat): ?>
        <a href="level_exams.php?id=<?php echo $cat['id']; ?>" style="text-decoration: none; color: inherit;">
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); height: 100%; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;"><?php echo htmlspecialchars($cat['name']); ?></h3>
                    <span style="background: #f3f4f6; color: #6b7280; font-size: 0.8rem; font-weight: 500; padding: 0.2rem 0.6rem; border-radius: 9999px; border: 1px solid #e5e7eb;">
                        Level #<?php echo $cat['id']; ?>
                    </span>
                </div>
                <p style="color: #4b5563; font-size: 0.95rem; line-height: 1.5;">
                    <?php echo htmlspecialchars($cat['description']); ?>
                </p>
            </div>
        </a>
    <?php endforeach; ?>
</div>

</main>
</div>
</body>

</html>