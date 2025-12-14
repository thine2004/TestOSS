<?php
require_once '../config.php';

$action = $_GET['action'] ?? 'list';
$deleteId = $_GET['delete_id'] ?? null;

// Handle Delete
if ($action === 'delete' && $deleteId) {
    $pdo->prepare("DELETE FROM results WHERE id = ?")->execute([$deleteId]);
    setFlash('success', 'Đã xóa kết quả thi.');
    header("Location: results.php");
    exit;
}

// Filters
$userId = $_GET['user_id'] ?? '';
$examId = $_GET['exam_id'] ?? '';

// Build Query
$sql = "SELECT r.*, u.username, u.fullname, e.title as exam_title 
        FROM results r 
        LEFT JOIN users u ON r.user_id = u.id 
        LEFT JOIN exams e ON r.exam_id = e.id 
        WHERE 1=1";
$params = [];

if ($userId) {
    $sql .= " AND r.user_id = ?";
    $params[] = $userId;
}
if ($examId) {
    $sql .= " AND r.exam_id = ?";
    $params[] = $examId;
}

$sql .= " ORDER BY r.completed_at DESC LIMIT 100";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();

// Fetch lists for filters
$users = $pdo->query("SELECT id, username,UCASE(fullname) as fullname FROM users ORDER BY username ASC")->fetchAll();
$exams = $pdo->query("SELECT id, title FROM exams ORDER BY title ASC")->fetchAll();

require_once 'includes/header.php';
?>

<div class="card-primary">
    <div class="card-header">
        <h3 style="margin:0;">Quản lý Kết quả Thi</h3>
    </div>
    <div class="card-body">

        <!-- Filters -->
        <form method="GET" style="background: #f8f9fa; padding: 1rem; border-radius: 4px; margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label>Lọc theo Thành viên</label>
                <select name="user_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Tất cả thành viên --</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?php echo $u['id']; ?>" <?php echo $userId == $u['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($u['username']); ?> (<?php echo htmlspecialchars($u['fullname']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label>Lọc theo Đề thi</label>
                <select name="exam_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Tất cả đề thi --</option>
                    <?php foreach ($exams as $e): ?>
                        <option value="<?php echo $e['id']; ?>" <?php echo $examId == $e['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($e['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <a href="results.php" class="admin-btn btn-warning"><i class="fas fa-undo"></i> Reset</a>
            </div>
        </form>

        <!-- Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Thành viên</th>
                    <th>Đề thi</th>
                    <th>Điểm số</th>
                    <th>Số câu đúng</th>
                    <th>Ngày thi</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $r): ?>
                    <tr>
                        <td><?php echo $r['id']; ?></td>
                        <td>
                            <div style="font-weight: 600;"><?php echo htmlspecialchars($r['fullname'] ?? $r['username']); ?></div>
                            <small style="color: #666;"><?php echo htmlspecialchars($r['username']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($r['exam_title']); ?></td>
                        <td>
                            <span class="badge" style="background: <?php echo $r['score'] >= 50 ? '#28a745' : '#dc3545'; ?>; color: white;">
                                <?php echo $r['score']; ?>
                            </span>
                        </td>
                        <td><?php echo $r['total_correct']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($r['completed_at'])); ?></td>
                        <td>
                            <a href="?action=delete&delete_id=<?php echo $r['id']; ?>" class="admin-btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa kết quả này?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($results)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Chưa có kết quả thi nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>