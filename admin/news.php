<?php
require_once '../config.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Ensure image dir exists
$imgDir = "../images/news/";
if (!file_exists($imgDir)) {
    mkdir($imgDir, 0777, true);
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $summary = $_POST['summary'];
    $content = $_POST['content'];
    $image = $_POST['current_image'] ?? '';

    // Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $valid = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ext, $valid)) {
            $newName = time() . "_" . uniqid() . "." . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imgDir . $newName)) {
                $image = $newName;
            }
        }
    }

    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO news (title, summary, content, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $summary, $content, $image]);
        setFlash('success', 'Đã thêm tin tức mới.');
    } elseif ($action === 'edit' && $id) {
        $stmt = $pdo->prepare("UPDATE news SET title=?, summary=?, content=?, image=? WHERE id=?");
        $stmt->execute([$title, $summary, $content, $image, $id]);
        setFlash('success', 'Đã cập nhật tin tức.');
    }
    header("Location: news.php");
    exit;
}

// Handle Delete
if ($action === 'delete' && $id) {
    // Get image to delete
    $stmt = $pdo->prepare("SELECT image FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();
    if ($img && file_exists($imgDir . $img)) {
        unlink($imgDir . $img);
    }

    $pdo->prepare("DELETE FROM news WHERE id = ?")->execute([$id]);
    setFlash('success', 'Đã xóa tin tức.');
    header("Location: news.php");
    exit;
}

// Get Data for Edit
$editItem = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $editItem = $stmt->fetch();
}

// List
$newsList = $pdo->query("SELECT * FROM news ORDER BY created_at DESC")->fetchAll();

require_once 'includes/header.php';
?>

<div class="card-primary">
    <div class="card-header">
        <h3 style="margin:0;">Quản lý Tin Tức</h3>
    </div>
    <div class="card-body">

        <!-- Form -->
        <div style="background: white; border: 1px solid #dee2e6; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
            <h4 style="margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">
                <?php echo $editItem ? 'Sửa Tin Tức' : 'Thêm Tin Tức Mới'; ?>
            </h4>
            <form action="?action=<?php echo $editItem ? 'edit&id=' . $id : 'add'; ?>" method="POST" enctype="multipart/form-data">
                <div style="margin-bottom: 1rem;">
                    <label>Tiêu đề <span style="color:red">*</span></label>
                    <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($editItem['title'] ?? ''); ?>">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label>Tóm tắt ngắn</label>
                    <textarea name="summary" class="form-control" rows="2"><?php echo htmlspecialchars($editItem['summary'] ?? ''); ?></textarea>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label>Nội dung chi tiết</label>
                    <textarea name="content" class="form-control" rows="10" style="font-family: monospace;"><?php echo htmlspecialchars($editItem['content'] ?? ''); ?></textarea>
                    <small style="color: grey;">Hỗ trợ HTML cơ bản.</small>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label>Hình ảnh</label>
                    <input type="file" name="image" class="form-control">
                    <input type="hidden" name="current_image" value="<?php echo $editItem['image'] ?? ''; ?>">
                    <?php if (!empty($editItem['image'])): ?>
                        <div style="margin-top: 5px;">
                            <img src="../images/news/<?php echo htmlspecialchars($editItem['image']); ?>?v=<?php echo time(); ?>" height="50">
                        </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="admin-btn btn-primary"><i class="fas fa-save"></i> Lưu tin tức</button>
                <?php if ($editItem): ?>
                    <a href="news.php" class="admin-btn btn-warning">Hủy</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- List -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Hình ảnh</th>
                    <th>Tiêu đề / Tóm tắt</th>
                    <th width="150">Ngày tạo</th>
                    <th width="120">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsList as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td>
                            <?php if ($item['image']): ?>
                                <img src="../images/news/<?php echo htmlspecialchars($item['image']); ?>?v=<?php echo time(); ?>" height="40" style="border-radius: 4px;">
                            <?php else: ?>
                                <span style="color:#ccc;">No img</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight: 600;"><?php echo htmlspecialchars($item['title']); ?></div>
                            <div style="color: #666; font-size: 0.9em;"><?php echo htmlspecialchars(substr($item['summary'], 0, 100)) . '...'; ?></div>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($item['created_at'])); ?></td>
                        <td>
                            <a href="?action=edit&id=<?php echo $item['id']; ?>" class="admin-btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="?action=delete&id=<?php echo $item['id']; ?>" class="admin-btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>