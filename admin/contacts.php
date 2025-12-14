<?php
require_once '../config.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

if ($action === 'delete' && $id) {
    // Delete
    $pdo->prepare("DELETE FROM contacts WHERE id = ?")->execute([$id]);
    setFlash('success', 'Đã xóa tin nhắn.');
    header("Location: contacts.php");
    exit;
} elseif ($action === 'mark_read' && $id) {
    // Mark as Read
    $pdo->prepare("UPDATE contacts SET status = 'read' WHERE id = ?")->execute([$id]);
    setFlash('success', 'Đã đánh dấu đã đọc.');
    header("Location: contacts.php");
    exit;
} elseif ($action === 'reply' && $id && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Reply
    $replyParams = trim($_POST['reply_content']);
    $pdo->prepare("UPDATE contacts SET reply = ?, status = 'replied' WHERE id = ?")->execute([$replyParams, $id]);
    setFlash('success', 'Đã gửi phản hồi.');
    header("Location: contacts.php");
    exit;
}

// Stats
$newCount = $pdo->query("SELECT count(*) FROM contacts WHERE status = 'new'")->fetchColumn();

// List
$contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();

require_once 'includes/header.php';
?>

<div class="card-primary">
    <div class="card-header">
        <h3 style="margin:0;">Quản lý Liên Hệ / Hỗ trợ</h3>
        <?php if ($newCount > 0): ?>
            <span class="badge badge-danger" style="background: red; color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">
                <?php echo $newCount; ?> tin nhắn mới
            </span>
        <?php endif; ?>
    </div>
    <div class="card-body">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th width="150">Người gửi</th>
                    <th width="150">Chủ đề</th>
                    <th>Nội dung</th>
                    <th width="150">Ngày gửi</th>
                    <th width="100">Trạng thái</th>
                    <th width="100">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $c): ?>
                    <tr style="<?php echo $c['status'] == 'new' ? 'background-color: #f0fdf4;' : ''; ?>">
                        <td><?php echo $c['id']; ?></td>
                        <td>
                            <b><?php echo htmlspecialchars($c['name']); ?></b><br>
                            <small style="color: #666;"><?php echo htmlspecialchars($c['email']); ?></small>
                        </td>
                        <td><span class="badge" style="background: #e9ecef; color: #333;"><?php echo htmlspecialchars($c['subject']); ?></span></td>
                        <td>
                            <div style="max-height: 100px; overflow-y: auto;">
                                <?php echo nl2br(htmlspecialchars($c['message'])); ?>
                            </div>
                            <?php if ($c['reply']): ?>
                                <div style="margin-top: 0.5rem; padding: 0.5rem; background: #e6fffa; border-left: 3px solid #00b894; font-size: 0.9rem;">
                                    <b><i class="fas fa-reply"></i> Admin:</b> <?php echo nl2br(htmlspecialchars($c['reply'])); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($c['created_at'])); ?></td>
                        <td>
                            <?php if ($c['status'] == 'new'): ?>
                                <span class="badge" style="background: #28a745; color: white;">Mới</span>
                            <?php elseif ($c['status'] == 'replied'): ?>
                                <span class="badge" style="background: #007bff; color: white;">Đã trả lời</span>
                            <?php else: ?>
                                <span class="badge" style="background: #6c757d; color: white;">Đã xem</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($c['status'] == 'new'): ?>
                                <a href="?action=mark_read&id=<?php echo $c['id']; ?>" class="admin-btn btn-success btn-sm" title="Đánh dấu đã đọc"><i class="fas fa-check"></i></a>
                            <?php endif; ?>

                            <button type="button" class="admin-btn btn-primary btn-sm" onclick="document.getElementById('reply-<?php echo $c['id']; ?>').style.display = document.getElementById('reply-<?php echo $c['id']; ?>').style.display === 'none' ? 'block' : 'none'"><i class="fas fa-reply"></i></button>

                            <a href="?action=delete&id=<?php echo $c['id']; ?>" class="admin-btn btn-danger btn-sm" onclick="return confirm('Xóa tin nhắn này?');"><i class="fas fa-trash"></i></a>

                            <div id="reply-<?php echo $c['id']; ?>" style="display: none; margin-top: 5px; position: absolute; background: white; border: 1px solid #ccc; padding: 10px; z-index: 10; width: 300px; right: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                                <form method="POST" action="?action=reply&id=<?php echo $c['id']; ?>">
                                    <textarea name="reply_content" class="form-control" rows="3" placeholder="Nhập câu trả lời..." required></textarea>
                                    <button type="submit" class="admin-btn btn-primary btn-sm" style="margin-top: 5px; width: 100%;">Gửi trả lời</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($contacts)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Chưa có tin nhắn hỗ trợ nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>