<?php
require_once '../config.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle Toggle Visibility
if ($action === 'toggle' && $id) {
    $stmt = $pdo->prepare("UPDATE reviews SET is_visible = NOT is_visible WHERE id = ?");
    $stmt->execute([$id]);
    setFlash('success', 'Đã thay đổi trạng thái hiển thị.');
    header("Location: reviews.php");
    exit;
}

// Handle Delete
if ($action === 'delete' && $id) {
    $pdo->prepare("DELETE FROM reviews WHERE id = ?")->execute([$id]);
    setFlash('success', 'Đã xóa đánh giá.');
    header("Location: reviews.php");
    exit;
}

// List
$reviews = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC")->fetchAll();

require_once 'includes/header.php';
?>

<div class="card-primary">
    <div class="card-header">
        <h3 style="margin:0;">Quản lý Đánh giá & Phản hồi</h3>
    </div>
    <div class="card-body">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th width="150">Người gửi</th>
                    <th width="100">Đánh giá</th>
                    <th>Nội dung</th>
                    <th width="150">Ngày gửi</th>
                    <th width="100">Trạng thái</th>
                    <th width="120">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $r): ?>
                    <tr>
                        <td><?php echo $r['id']; ?></td>
                        <td><b><?php echo htmlspecialchars($r['user_name']); ?></b></td>
                        <td>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star" style="color: <?php echo $i <= $r['rating'] ? '#f1c40f' : '#ccc'; ?>; font-size: 0.8rem;"></i>
                            <?php endfor; ?>
                        </td>
                        <td><?php echo nl2br(htmlspecialchars($r['comment'])); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($r['created_at'])); ?></td>
                        <td>
                            <?php if ($r['is_visible']): ?>
                                <span class="badge badge-success" style="background: #28a745; color: white;">Hiển thị</span>
                            <?php else: ?>
                                <span class="badge badge-secondary" style="background: #6c757d; color: white;">Ẩn</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?action=toggle&id=<?php echo $r['id']; ?>" class="admin-btn btn-warning btn-sm" title="<?php echo $r['is_visible'] ? 'Ẩn' : 'Hiện'; ?>">
                                <i class="fas <?php echo $r['is_visible'] ? 'fa-eye-slash' : 'fa-eye'; ?>"></i>
                            </a>
                            <a href="?action=delete&id=<?php echo $r['id']; ?>" class="admin-btn btn-danger btn-sm" onclick="return confirm('Xóa đánh giá này?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Chưa có đánh giá nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>