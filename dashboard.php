<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('index.php');

$user_id = $_SESSION['user_id'];

// Stats
$totalTests = $pdo->query("SELECT COUNT(*) FROM results WHERE user_id=$user_id")->fetchColumn();
$avgScore = $pdo->query("SELECT AVG(score) FROM results WHERE user_id=$user_id")->fetchColumn();
$lastTest = $pdo->query("SELECT completed_at FROM results WHERE user_id=$user_id ORDER BY completed_at DESC LIMIT 1")->fetchColumn();

// Recent Activity
$recent = $pdo->query("SELECT r.*, e.title FROM results r JOIN exams e ON r.exam_id = e.id WHERE r.user_id = $user_id ORDER BY r.completed_at DESC LIMIT 5")->fetchAll();

require_once 'includes/header_app.php';
?>

<div class="grid">
    <!-- Stat Cards (Like Reference Info-Boxes but Indigo Style) -->
    <div class="card stat-box" style="background: white; color: var(--text-main); border-left: 5px solid var(--primary);">
        <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">Cấp độ hiện tại</div>
        <div style="font-size: 1.5rem; font-weight: 700;">Pre-IELTS</div>
    </div>
    <div class="card stat-box" style="background: white; color: var(--text-main); border-left: 5px solid var(--secondary);">
        <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">Số đề đã làm</div>
        <div style="font-size: 1.5rem; font-weight: 700;"><?php echo $totalTests; ?></div>
    </div>
    <div class="card stat-box" style="background: white; color: var(--text-main); border-left: 5px solid #10b981;">
        <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">Điểm trung bình</div>
        <div style="font-size: 1.5rem; font-weight: 700;"><?php echo number_format($avgScore, 1); ?></div>
    </div>
</div>

<div class="grid-2" style="margin-top: 2rem;">
    <!-- Chart / Progress Placeholder -->
    <div class="card">
        <h3 style="margin-bottom: 1.5rem;">Tiến độ học tập</h3>
        <div style="display: flex; align-items: center; justify-content: center; height: 150px; background: #f9fafb; border-radius: 8px; color: #9ca3af;">
            <!-- Simple CSS Circle instead of complex JS chart for now -->
            <div style="width: 100px; height: 100px; border-radius: 50%; border: 10px solid #e0e7ff; display: grid; place-items: center; font-weight: bold; font-size: 1.2rem; color: var(--primary);">
                <?php echo $totalTests; ?>
            </div>
        </div>
        <p style="text-align: center; margin-top: 1rem; font-size: 0.9rem;">Bài đã hoàn thành</p>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <h3 style="margin-bottom: 1rem;">Hoạt động gần đây</h3>
        <ul style="list-style: none;">
            <?php foreach ($recent as $r): ?>
                <li style="padding: 0.8rem 0; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between;">
                    <div>
                        <div style="font-weight: 500;"><?php echo htmlspecialchars($r['title']); ?></div>
                        <div style="font-size: 0.8rem; color: #9ca3af;"><?php echo date('d/m/Y H:i', strtotime($r['completed_at'])); ?></div>
                    </div>
                    <div style="font-weight: bold; color: var(--primary);"><?php echo $r['score']; ?> pts</div>
                </li>
            <?php endforeach; ?>
            <?php if (empty($recent)): ?>
                <li style="color: #9ca3af; text-align: center;">Chưa có hoạt động nào.</li>
            <?php endif; ?>
        </ul>
        <a href="history.php" style="display: block; margin-top: 1rem; text-align: center; font-size: 0.9rem; color: var(--primary); font-weight: 600;">Xem tất cả</a>
    </div>

    <!-- Contact History -->
    <?php
    $myContacts = $pdo->query("SELECT * FROM contacts WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5")->fetchAll();
    ?>
    <div class="card" style="grid-column: 1 / -1;">
        <h3 style="margin-bottom: 1rem;">Lịch sử gửi hỗ trợ</h3>
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f3f4f6;">
                    <th style="padding: 0.5rem;">Ngày gửi</th>
                    <th style="padding: 0.5rem;">Chủ đề</th>
                    <th style="padding: 0.5rem;">Trạng thái</th>
                    <th style="padding: 0.5rem;">Phản hồi từ Admin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($myContacts as $mc): ?>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 0.8rem 0.5rem; color: #6b7280;"><?php echo date('d/m/Y', strtotime($mc['created_at'])); ?></td>
                        <td style="padding: 0.8rem 0.5rem; font-weight: 500;"><?php echo htmlspecialchars($mc['subject']); ?></td>
                        <td style="padding: 0.8rem 0.5rem;">
                            <?php if ($mc['status'] == 'new'): ?>
                                <span style="background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 99px; font-size: 0.8rem;">Đang chờ</span>
                            <?php elseif ($mc['status'] == 'read'): ?>
                                <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 99px; font-size: 0.8rem;">Đã xem</span>
                            <?php elseif ($mc['status'] == 'replied'): ?>
                                <span style="background: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 99px; font-size: 0.8rem;">Đã trả lời</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 0.8rem 0.5rem; color: #4b5563;">
                            <?php echo $mc['reply'] ? nl2br(htmlspecialchars($mc['reply'])) : '-'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($myContacts)): ?>
                    <tr>
                        <td colspan="4" style="padding: 1rem; text-align: center; color: #9ca3af;">Bạn chưa gửi yêu cầu hỗ trợ nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</main> <!-- End Main -->
</div> <!-- End App Container -->
</body>

</html>