<?php
require_once '../config.php';

$examId = $_GET['exam_id'] ?? null;
if (!$examId) {
    setFlash('error', 'No exam specified.');
    redirect('exams.php');
}

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Get Exam Info
$stmt = $pdo->prepare("SELECT * FROM exams WHERE id = ?");
$stmt->execute([$examId]);
$exam = $stmt->fetch();

if (!$exam) {
    setFlash('error', 'Exam not found.');
    redirect('exams.php');
}

// Auto-migration: Ensure 'type' column exists because previous migration might have been missed
if (!isset($exam['type'])) {
    try {
        $pdo->exec("ALTER TABLE exams ADD COLUMN type ENUM('mixed', 'image', 'audio') DEFAULT 'mixed'");
        // Refetch to get the new column (will be 'mixed' by default)
        $stmt->execute([$examId]);
        $exam = $stmt->fetch();
    } catch (Exception $e) { /* Column might exist but wasn't fetched? Unlikely. */
    }
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qText = $_POST['content'];
    $optA = $_POST['option_a'];
    $optB = $_POST['option_b'];
    $optC = $_POST['option_c'];
    $optD = $_POST['option_d'];
    $correct = $_POST['correct_option'];
    $explain = $_POST['explanation'];
    $mediaType = $_POST['media_type'] ?? 'none';
    $mediaUrl = $_POST['media_url'] ?? '';

    // RE-FETCH Exam Type to be absolutely sure we have strictly current DB state
    $stmt = $pdo->prepare("SELECT type FROM exams WHERE id = ?");
    $stmt->execute([$examId]);
    $currentExam = $stmt->fetch();
    $eType = $currentExam['type'] ?? 'mixed';

    // Normalize inputs
    $mType = $_POST['media_type'];

    // STRICT VALIDATION LOGIC
    $ext = strtolower(pathinfo($mediaUrl, PATHINFO_EXTENSION));

    if ($eType === 'image') {
        if ($mType !== 'image') {
            setFlash('error', "Lỗi: Đề thi này là loại 'Hình ảnh'. Bạn chỉ được phép chọn Media Type là 'Hình ảnh'.");
            header("Location: questions.php?exam_id=" . $examId . "&action=" . ($id ? 'edit&id=' . $id : 'list'));
            exit;
        }
        // Check file extension for image
        $validImageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
        if ($mediaUrl && !in_array($ext, $validImageExts)) {
            setFlash('error', "Lỗi File: Đề thi Hình ảnh yêu cầu file ảnh hợp lệ (.jpg, .png, .gif...). Bạn đang nhập: .$ext");
            header("Location: questions.php?exam_id=" . $examId . "&action=" . ($id ? 'edit&id=' . $id : 'list'));
            exit;
        }
    }

    if ($eType === 'audio') {
        if ($mType !== 'audio') {
            setFlash('error', "Lỗi: Đề thi này là loại 'Audio'. Bạn chỉ được phép chọn Media Type là 'Audio'.");
            header("Location: questions.php?exam_id=" . $examId . "&action=" . ($id ? 'edit&id=' . $id : 'list'));
            exit;
        }
        // Check file extension for audio
        $validAudioExts = ['mp3', 'wav', 'ogg', 'm4a', 'aac'];
        if ($mediaUrl && !in_array($ext, $validAudioExts)) {
            setFlash('error', "Lỗi File: Đề thi Audio yêu cầu file âm thanh hợp lệ (.mp3, .wav...). Bạn đang nhập: .$ext");
            header("Location: questions.php?exam_id=" . $examId . "&action=" . ($id ? 'edit&id=' . $id : 'list'));
            exit;
        }
    }

    if ($action === 'add') {
        $pdo->prepare("INSERT INTO questions (exam_id, content, option_a, option_b, option_c, option_d, correct_option, explanation, media_type, media_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")->execute([$examId, $qText, $optA, $optB, $optC, $optD, $correct, $explain, $mediaType, $mediaUrl]);
        setFlash('success', 'Question added.');
    } elseif ($action === 'edit' && $id) {
        $pdo->prepare("UPDATE questions SET content=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_option=?, explanation=?, media_type=?, media_url=? WHERE id=?")->execute([$qText, $optA, $optB, $optC, $optD, $correct, $explain, $mediaType, $mediaUrl, $id]);
        setFlash('success', 'Question updated.');
    }

    // Update total questions count in exams table
    $count = $pdo->query("SELECT count(*) FROM questions WHERE exam_id = $examId")->fetchColumn();
    $pdo->prepare("UPDATE exams SET total_questions = ? WHERE id = ?")->execute([$count, $examId]);

    header("Location: questions.php?exam_id=" . $examId);
    exit;
}

// Handle Delete
if ($action === 'delete' && $id) {
    $pdo->prepare("DELETE FROM questions WHERE id = ?")->execute([$id]);

    // Update count
    $count = $pdo->query("SELECT count(*) FROM questions WHERE exam_id = $examId")->fetchColumn();
    $pdo->prepare("UPDATE exams SET total_questions = ? WHERE id = ?")->execute([$count, $examId]);

    setFlash('success', 'Question deleted.');
    header("Location: questions.php?exam_id=" . $examId);
    exit;
}

require_once 'includes/header.php';

// Data
$questions = $pdo->prepare("SELECT * FROM questions WHERE exam_id = ?");
$questions->execute([$examId]);
$questions = $questions->fetchAll();

// Edit Mode
$editQ = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE id=?");
    $stmt->execute([$id]);
    $editQ = $stmt->fetch();
}
?>

<div class="card-primary">
    <div class="card-header">
        <h3>Quản lý Câu hỏi: <span style="color:var(--primary)"><?php echo htmlspecialchars($exam['title']); ?></span></h3>
        <a href="exams.php" class="admin-btn btn-warning"><i class="fas fa-arrow-left"></i> Quay lại Đề thi</a>
    </div>
    <div class="card-body">
        <form action="?exam_id=<?php echo $examId; ?>&action=<?php echo $editQ ? 'edit&id=' . $id : 'add'; ?>" method="POST" style="background: #f8f9fa; padding: 1rem; margin-bottom: 2rem; border-radius: 4px;">
            <div style="margin-bottom: 1rem;">
                <label>Nội dung câu hỏi</label>
                <textarea name="content" class="form-control" rows="3" required><?php echo $editQ['content'] ?? ''; ?></textarea>
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

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label>Loại Media</label>
                    <select name="media_type" class="form-control">
                        <option value="none" <?php echo ($editQ && $editQ['media_type'] == 'none') ? 'selected' : ''; ?>>Không có</option>
                        <option value="audio" <?php echo ($editQ && $editQ['media_type'] == 'audio') ? 'selected' : ''; ?>>Audio</option>
                        <option value="image" <?php echo ($editQ && $editQ['media_type'] == 'image') ? 'selected' : ''; ?>>Hình ảnh</option>
                        <!-- <option value="flashcard" <?php echo ($editQ && $editQ['media_type'] == 'flashcard') ? 'selected' : ''; ?>>Flashcard View</option> -->
                    </select>
                </div>
                <div>
                    <label>Link/File Media</label>
                    <input type="text" name="media_url" class="form-control" placeholder="example.mp3 or image.jpg" value="<?php echo $editQ['media_url'] ?? ''; ?>">
                    <small style="color:red">Copy file vào thư mục images/audio hoặc nhập URL</small>
                </div>
            </div>

            <button type="submit" class="admin-btn btn-success"><?php echo $editQ ? 'Cập nhật' : 'Thêm câu hỏi'; ?></button>
            <?php if ($editQ): ?><a href="questions.php?exam_id=<?php echo $examId; ?>" class="admin-btn btn-warning">Hủy</a><?php endif; ?>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Câu hỏi</th>
                    <th>Đáp án</th>
                    <th>Giải thích</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $q): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($q['content']); ?></td>
                        <td><span class="badge" style="background: green; color: white;"><?php echo $q['correct_option']; ?></span></td>
                        <td><?php echo htmlspecialchars($q['explanation']); ?></td>
                        <td>
                            <a href="?exam_id=<?php echo $examId; ?>&action=edit&id=<?php echo $q['id']; ?>" class="admin-btn btn-warning"><i class="fas fa-edit"></i></a>
                            <a href="?exam_id=<?php echo $examId; ?>&action=delete&id=<?php echo $q['id']; ?>" class="admin-btn btn-danger" onclick="return confirm('Xóa?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>