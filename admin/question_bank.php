<?php
require_once '../config.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle POST (Add/Edit Question)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $examId = $_POST['exam_id'];
    $content = $_POST['content'];
    $optA = $_POST['option_a'];
    $optB = $_POST['option_b'];
    $optC = $_POST['option_c'];
    $optD = $_POST['option_d'];
    $correct = $_POST['correct_option'];
    $explain = $_POST['explanation'];

    // Media Handling
    $mediaType = $_POST['media_type'] ?? 'none';
    $mediaUrl = $_POST['media_url'] ?? ''; // URL Text input

    // File Upload Override
    if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] == 0) {
        $fName = $_FILES['media_file']['name'];
        $fType = $_FILES['media_file']['type'];
        $ext = strtolower(pathinfo($fName, PATHINFO_EXTENSION));

        // Determine type based on extension first (more reliable than mime sometimes)
        $imgExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
        $audExts = ['mp3', 'wav', 'ogg', 'm4a', 'aac'];

        $isImage = in_array($ext, $imgExts) || strpos($fType, 'image') !== false;
        $isAudio = in_array($ext, $audExts) || strpos($fType, 'audio') !== false;

        // If user explicitly selected a type in dropdown, prioritize that for folder selection if ambiguous
        if ($mediaType === 'audio') {
            $isAudio = true;
            $isImage = false;
        }
        if ($mediaType === 'image') {
            $isImage = true;
            $isAudio = false;
        }

        $targetDir = "../images/";
        if ($isAudio) $targetDir = "../audio/";

        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . "_" . preg_replace('/[^a-zA-Z0-9.\-_]/', '', $fName); // Sanitize filename

        if (move_uploaded_file($_FILES['media_file']['tmp_name'], $targetDir . $fileName)) {
            $mediaUrl = $fileName;
            if ($isImage) $mediaType = 'image';
            if ($isAudio) $mediaType = 'audio';
        }
    }
    // --- VALIDATION START ---
    // 1. Get Exam Type
    $eStmt = $pdo->prepare("SELECT type FROM exams WHERE id = ?");
    $eStmt->execute([$examId]);
    $examInfo = $eStmt->fetch();
    $eType = $examInfo['type'] ?? 'mixed';

    // 2. Validate Media Type vs Exam Type
    if ($eType === 'image') {
        if ($mediaType !== 'image') {
            setFlash('error', "Lỗi: Đề thi này là loại 'Hình ảnh'. Bạn phải upload ảnh hoặc chọn Media Type là 'Hình ảnh'.");
            header("Location: question_bank.php");
            exit;
        }
        // Check extension if it's a URL/Filename (not a fresh upload we just vetted)
        if (!isset($_FILES['media_file']) || $_FILES['media_file']['error'] != 0) {
            $ext = strtolower(pathinfo($mediaUrl, PATHINFO_EXTENSION));
            $validImageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
            if ($mediaUrl && !in_array($ext, $validImageExts)) {
                setFlash('error', "Lỗi File: Đề thi Hình ảnh yêu cầu file ảnh hợp lệ (.jpg, .png...). Bạn đang nhập: .$ext");
                header("Location: question_bank.php");
                exit;
            }
        }
    }

    if ($eType === 'audio') {
        if ($mediaType !== 'audio') {
            setFlash('error', "Lỗi: Đề thi này là loại 'Audio'. Bạn phải upload audio hoặc chọn Media Type là 'Audio'.");
            header("Location: question_bank.php");
            exit;
        }
        // Check extension
        if (!isset($_FILES['media_file']) || $_FILES['media_file']['error'] != 0) {
            $ext = strtolower(pathinfo($mediaUrl, PATHINFO_EXTENSION));
            $validAudioExts = ['mp3', 'wav', 'ogg', 'm4a', 'aac'];
            if ($mediaUrl && !in_array($ext, $validAudioExts)) {
                setFlash('error', "Lỗi File: Đề thi Audio yêu cầu file âm thanh hợp lệ (.mp3, .wav...). Bạn đang nhập: .$ext");
                header("Location: question_bank.php");
                exit;
            }
        }
    }
    // --- VALIDATION END ---

    if ($action === 'add') {
        // Ensure columns exist (media_type, media_url) - assuming they do from previous steps or ignored if not
        $pdo->prepare("INSERT INTO questions (exam_id, content, option_a, option_b, option_c, option_d, correct_option, explanation, media_type, media_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")->execute([$examId, $content, $optA, $optB, $optC, $optD, $correct, $explain, $mediaType, $mediaUrl]);

        // Update Exam Question Count
        $count = $pdo->query("SELECT count(*) FROM questions WHERE exam_id = $examId")->fetchColumn();
        $pdo->prepare("UPDATE exams SET total_questions = ? WHERE id = ?")->execute([$count, $examId]);

        setFlash('success', 'Question added.');
    } elseif ($action === 'edit' && $id) {
        $pdo->prepare("UPDATE questions SET exam_id=?, content=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_option=?, explanation=?, media_type=?, media_url=? WHERE id=?")->execute([$examId, $content, $optA, $optB, $optC, $optD, $correct, $explain, $mediaType, $mediaUrl, $id]);
        setFlash('success', 'Question updated.');
    }
    header("Location: question_bank.php");
    exit;
}

// Handle Delete
if ($action === 'delete' && $id) {
    // Get exam_id before delete to update count
    $qid = $pdo->prepare("SELECT exam_id FROM questions WHERE id=?");
    $qid->execute([$id]);
    $exId = $qid->fetchColumn();

    $pdo->prepare("DELETE FROM questions WHERE id = ?")->execute([$id]);

    if ($exId) {
        $count = $pdo->query("SELECT count(*) FROM questions WHERE exam_id = $exId")->fetchColumn();
        $pdo->prepare("UPDATE exams SET total_questions = ? WHERE id = ?")->execute([$count, $exId]);
    }

    setFlash('success', 'Question deleted.');
    header("Location: question_bank.php");
    exit;
}

require_once 'includes/header.php';

// Prepare Data
$examFilter = $_GET['exam_id'] ?? '';
$search = $_GET['search'] ?? '';

$exams = $pdo->query("SELECT * FROM exams ORDER BY id DESC")->fetchAll();

$sql = "SELECT q.*, e.title as exam_title FROM questions q LEFT JOIN exams e ON q.exam_id = e.id WHERE 1=1";
$params = [];

if ($examFilter) {
    $sql .= " AND q.exam_id = ?";
    $params[] = $examFilter;
}
if ($search) {
    $sql .= " AND (q.content LIKE ?)";
    $params[] = "%$search%";
}

$sql .= " ORDER BY q.id DESC LIMIT 200"; // Limit for performance
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$questions = $stmt->fetchAll();

// Edit Mode Data
$editQ = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE id=?");
    $stmt->execute([$id]);
    $editQ = $stmt->fetch();
}
?>

<div class="card-primary">
    <div class="card-header">
        <h3 style="margin:0;">Quản lý Ngân hàng Câu hỏi</h3>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" style="background: #f8f9fa; padding: 1rem; border-radius: 4px; margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label>Tìm kiếm nội dung</label>
                <input type="text" name="search" class="form-control" placeholder="Nhập câu hỏi..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div style="min-width: 200px;">
                <label>Lọc theo Đề thi</label>
                <select name="exam_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Tất cả Đề thi --</option>
                    <?php foreach ($exams as $e): ?><option value="<?php echo $e['id']; ?>" <?php echo $examFilter == $e['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($e['title']); ?></option><?php endforeach; ?>
                </select>
            </div>
            <div>
                <button type="submit" class="admin-btn btn-success"><i class="fas fa-filter"></i> Lọc</button>
                <a href="question_bank.php" class="admin-btn btn-warning"><i class="fas fa-undo"></i> Reset</a>
            </div>
        </form>

        <!-- Form Area -->
        <div style="background: white; border: 1px solid #dee2e6; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
            <h4 style="margin-top: 0; margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">
                <?php echo $editQ ? 'Sửa Câu Hỏi: #' . $id : 'Thêm Câu Hỏi Mới'; ?>
            </h4>
            <form id="questionForm" action="?action=<?php echo $editQ ? 'edit&id=' . $id : 'add'; ?>" method="POST" enctype="multipart/form-data">

                <div style="margin-bottom: 1rem;">
                    <label>Thuộc Đề thi <span style="color:red">*</span></label>
                    <select name="exam_id" class="form-control" required>
                        <option value="">-- Chọn Đề thi --</option>
                        <?php foreach ($exams as $e): ?>
                            <option value="<?php echo $e['id']; ?>" <?php echo ($editQ && $editQ['exam_id'] == $e['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($e['title']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label>Nội dung câu hỏi <span style="color:red">*</span></label>
                    <textarea name="content" class="form-control" rows="3" required placeholder="Nhập đề bài..."><?php echo $editQ['content'] ?? ''; ?></textarea>
                </div>

                <!-- Media -->
                <div style="background: #f1f3f5; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; border: 1px dashed #ced4da;">
                    <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;"><i class="fas fa-photo-video"></i> Media (Hình ảnh / Âm thanh)</label>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <div style="flex: 1;">
                            <label>Chọn File (Ưu tiên)</label>
                            <input type="file" name="media_file" id="mediaFile" class="form-control" style="padding: 0; border: 0;">
                        </div>
                        <div style="flex: 1;">
                            <label>Hoặc URL/Filename</label>
                            <input type="text" name="media_url" class="form-control" placeholder="http://... hoặc image.jpg" value="<?php echo $editQ['media_url'] ?? ''; ?>">
                        </div>
                        <div style="flex: 0 0 150px;">
                            <label>Loại Media</label>
                            <select name="media_type" class="form-control">
                                <option value="none" <?php echo ($editQ && isset($editQ['media_type']) && $editQ['media_type'] == 'none') ? 'selected' : ''; ?>>None</option>
                                <option value="image" <?php echo ($editQ && isset($editQ['media_type']) && $editQ['media_type'] == 'image') ? 'selected' : ''; ?>>Hình ảnh</option>
                                <option value="audio" <?php echo ($editQ && isset($editQ['media_type']) && $editQ['media_type'] == 'audio') ? 'selected' : ''; ?>>Âm thanh</option>
                            </select>
                        </div>
                    </div>
                    <?php if (isset($editQ['media_url']) && $editQ['media_url']): ?>
                        <div style="margin-top: 5px; font-size: 0.9rem; color: #666;">Hiện tại: <b><?php echo $editQ['media_url']; ?></b> (<?php echo $editQ['media_type']; ?>)</div>
                    <?php endif; ?>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div><label>Option A</label><input type="text" name="option_a" class="form-control" value="<?php echo $editQ['option_a'] ?? ''; ?>" required></div>
                    <div><label>Option B</label><input type="text" name="option_b" class="form-control" value="<?php echo $editQ['option_b'] ?? ''; ?>" required></div>
                    <div><label>Option C</label><input type="text" name="option_c" class="form-control" value="<?php echo $editQ['option_c'] ?? ''; ?>" required></div>
                    <div><label>Option D</label><input type="text" name="option_d" class="form-control" value="<?php echo $editQ['option_d'] ?? ''; ?>" required></div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label>Đáp án đúng</label>
                        <select name="correct_option" class="form-control">
                            <option value="A" <?php echo ($editQ && $editQ['correct_option'] == 'A') ? 'selected' : ''; ?>>A</option>
                            <option value="B" <?php echo ($editQ && $editQ['correct_option'] == 'B') ? 'selected' : ''; ?>>B</option>
                            <option value="C" <?php echo ($editQ && $editQ['correct_option'] == 'C') ? 'selected' : ''; ?>>C</option>
                            <option value="D" <?php echo ($editQ && $editQ['correct_option'] == 'D') ? 'selected' : ''; ?>>D</option>
                        </select>
                    </div>
                    <div>
                        <label>Giải thích</label>
                        <input type="text" name="explanation" class="form-control" value="<?php echo $editQ['explanation'] ?? ''; ?>">
                    </div>
                </div>

                <div style="padding-top: 1rem; border-top: 1px solid #eee;">
                    <button type="submit" class="admin-btn btn-primary"><i class="fas fa-save"></i> <?php echo $editQ ? 'Cập nhật' : 'Lưu Câu hỏi'; ?></button>
                    <?php if ($editQ): ?><a href="question_bank.php" class="admin-btn btn-warning">Hủy bỏ</a><?php endif; ?>
                </div>
            </form>
        </div>

        <!-- List -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Đề thi</th>
                    <th>Nội dung</th>
                    <th>Media</th>
                    <th>Đáp án</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $q): ?>
                    <tr>
                        <td><?php echo $q['id']; ?></td>
                        <td><span class="badge" style="background:#e9ecef; color:#333;"><?php echo htmlspecialchars($q['exam_title']); ?></span></td>
                        <td>
                            <div style="font-weight:600;"><?php echo htmlspecialchars(substr($q['content'], 0, 100)) . (strlen($q['content']) > 100 ? '...' : ''); ?></div>
                            <small style="color: grey;">
                                A: <?php echo htmlspecialchars($q['option_a']); ?> |
                                B: <?php echo htmlspecialchars($q['option_b']); ?> |
                                C: <?php echo htmlspecialchars($q['option_c']); ?> |
                                D: <?php echo htmlspecialchars($q['option_d']); ?>
                            </small>
                        </td>
                        <td>
                            <?php if (isset($q['media_type']) && $q['media_type'] === 'image'): ?>
                                <i class="fas fa-image" style="color: blue;"></i> Img
                            <?php elseif (isset($q['media_type']) && $q['media_type'] === 'audio'): ?>
                                <i class="fas fa-volume-up" style="color: orange;"></i> Audio
                            <?php else: ?>
                                <span style="color:#ccc;">-</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge" style="background:green; color:white;"><?php echo $q['correct_option']; ?></span></td>
                        <td>
                            <a href="?action=edit&id=<?php echo $q['id']; ?>" class="admin-btn btn-warning"><i class="fas fa-edit"></i></a>
                            <a href="?action=delete&id=<?php echo $q['id']; ?>" class="admin-btn btn-danger" onclick="return confirm('Xóa?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>

<script src="js/question_bank.js"></script>

<?php require_once 'includes/footer.php'; ?>