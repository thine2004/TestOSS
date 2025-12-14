<?php
require_once '../config.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];

    if ($action === 'add') {
        $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)")->execute([$name, $desc]);
        setFlash('success', 'Level added.');
    } elseif ($action === 'edit' && $id) {
        $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?")->execute([$name, $desc, $id]);
        setFlash('success', 'Level updated.');
    }
    header("Location: levels.php");
    exit;
}

// Handle Delete
if ($action === 'delete' && $id) {
    try {
        $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
        setFlash('success', 'Level deleted.');
    } catch (Exception $e) {
        setFlash('error', 'Cannot delete. Level might be in use.');
    }
    header("Location: levels.php");
    exit;
}

require_once 'includes/header.php';
$levels = $pdo->query("SELECT * FROM categories")->fetchAll();

// Edit Mode
$editL = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
    $stmt->execute([$id]);
    $editL = $stmt->fetch();
}
?>

<div class="card-primary">
    <div class="card-header">
        <h3>Quản lý Levels (Categories)</h3>
    </div>
    <div class="card-body">
        <form action="?action=<?php echo $editL ? 'edit&id=' . $id : 'add'; ?>" method="POST" style="margin-bottom: 2rem; display: flex; gap: 1rem; align-items: flex-end;">
            <div style="flex:1;">
                <label>Tên Level</label>
                <input type="text" name="name" class="form-control" placeholder="Tên Level..." value="<?php echo $editL['name'] ?? ''; ?>" required>
            </div>
            <div style="flex:2;">
                <label>Mô tả</label>
                <input type="text" name="description" class="form-control" placeholder="Mô tả..." value="<?php echo $editL['description'] ?? ''; ?>">
            </div>
            <button type="submit" class="admin-btn btn-primary"><?php echo $editL ? 'Cập nhật' : 'Thêm'; ?></button>
            <?php if ($editL): ?><a href="levels.php" class="admin-btn btn-warning">Hủy</a><?php endif; ?>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Mô tả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($levels as $l): ?>
                    <tr>
                        <td><?php echo $l['id']; ?></td>
                        <td><?php echo htmlspecialchars($l['name']); ?></td>
                        <td><?php echo htmlspecialchars($l['description']); ?></td>
                        <td>
                            <a href="?action=edit&id=<?php echo $l['id']; ?>" class="admin-btn btn-warning"><i class="fas fa-edit"></i></a>
                            <a href="?action=delete&id=<?php echo $l['id']; ?>" class="admin-btn btn-danger" onclick="return confirm('Xóa?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>