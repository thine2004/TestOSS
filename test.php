<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('index.php');

$userId = $_SESSION['user_id'];
$examId = $_GET['id'] ?? null;
$resultId = $_GET['result_id'] ?? null;

// --- VIEW RESULT MODE ---
if ($resultId) {
    require_once 'includes/header_app.php';

    // Fetch Result
    $stmt = $pdo->prepare("SELECT r.*, e.title, e.total_questions as exam_total FROM results r JOIN exams e ON r.exam_id = e.id WHERE r.id = ? AND r.user_id = ?");
    $stmt->execute([$resultId, $userId]);
    $result = $stmt->fetch();

    if (!$result) {
        echo "<div class='container' style='padding:2rem;'>Không tìm thấy kết quả.</div>";
        echo "</div></main></body></html>";
        exit;
    }

    // Calculate percentage
    $totalQ = $result['exam_total'] > 0 ? $result['exam_total'] : 1;


?>
    <div style="max-width: 800px; margin: 2rem auto; text-align: center;">
        <div style="background: white; padding: 3rem; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);">
            <div style="font-size: 3rem; margin-bottom: 1rem;">
                <?php if ($result['total_correct'] > 5): ?>
                    🎉
                <?php else: ?>
                    💪
                <?php endif; ?>
            </div>
            <h2 style="font-size: 2rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem;">Kết quả bài thi</h2>
            <p style="color: #6b7280; font-size: 1.1rem; margin-bottom: 2rem;"><?php echo htmlspecialchars($result['title']); ?></p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div style="background: #f0fdf4; padding: 1.5rem; border-radius: 12px; color: #166534;">
                    <div style="font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Điểm số</div>
                    <div style="font-size: 2.5rem; font-weight: 800; line-height: 1.2;"><?php echo $result['score']; ?></div>
                </div>
                <div style="background: #eff6ff; padding: 1.5rem; border-radius: 12px; color: #1e40af;">
                    <div style="font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Số câu đúng</div>
                    <div style="font-size: 2.5rem; font-weight: 800; line-height: 1.2;">
                        <?php echo $result['total_correct']; ?>
                        <span style="font-size: 1rem; opacity: 0.6;"></span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="roadmap.php" class="btn btn-outline">Quay về Lộ trình</a>
                <a href="test.php?id=<?php echo $result['exam_id']; ?>" class="btn">Làm lại bài này</a>
            </div>
        </div>
    </div>
<?php
    echo "</div></main></body></html>"; // Close tags opened in header_app
    exit;
}

// --- TAKE EXAM MODE ---

if (!$examId) redirect('roadmap.php');

// Fetch Exam
$stmt = $pdo->prepare("SELECT * FROM exams WHERE id = ?");
$stmt->execute([$examId]);
$exam = $stmt->fetch();

if (!$exam) {
    setFlash('error', 'Exam not found');
    redirect('roadmap.php');
}

// Fetch Questions
$qStmt = $pdo->prepare("SELECT * FROM questions WHERE exam_id = ? ORDER BY id ASC"); 
$qStmt->execute([$examId]);
$questions = $qStmt->fetchAll();

if (empty($questions)) {
    echo "<script>alert('Đề thi này chưa có câu hỏi nào!'); window.location.href='roadmap.php';</script>";
    exit;
}

// --- HANDLE SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_test'])) {

    $correctCount = 0;
    $totalQuestions = count($questions);

    foreach ($questions as $q) {
        $qid = $q['id'];
        if (isset($_POST['question_' . $qid])) {
            $userAns = $_POST['question_' . $qid];
            if ($userAns === $q['correct_option']) {
                $correctCount++;
            }
        }
    }


    $score = $correctCount * 10;

    // Save Result
    $insert = $pdo->prepare("INSERT INTO results (user_id, exam_id, total_correct, score, completed_at) VALUES (?, ?, ?, ?, NOW())");
    $insert->execute([$userId, $examId, $correctCount, $score]);
    $newResultId = $pdo->lastInsertId();

    // Redirect to Result View
    header("Location: test.php?result_id=" . $newResultId);
    exit;
}

require_once 'includes/header_app.php';
?>

<div style="max-width: 900px; margin: 0 auto; padding-bottom: 4rem;">
    <!-- Header Info -->
    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 2rem; position: sticky; top: 1rem; z-index: 50; display: flex; justify-content: space-between; align-items: center; border: 1px solid #e5e7eb;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0; color: #111827;"><?php echo htmlspecialchars($exam['title']); ?></h2>
            <div style="color: #6b7280; font-size: 0.9rem; margin-top: 0.2rem;">
                <?php echo count($questions); ?> câu hỏi • <?php echo $exam['duration_minutes']; ?> phút
            </div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 0.8rem; color: #6b7280; text-transform: uppercase; font-weight: 600;">Thời gian còn lại</div>
            <div id="timer" style="font-size: 1.5rem; font-weight: 700; color: #dc2626; font-variant-numeric: tabular-nums;">
                00:00
            </div>
        </div>
    </div>

    <form id="examForm" method="POST" action="">
        <input type="hidden" name="submit_test" value="1">

        <?php foreach ($questions as $index => $q): ?>
            <div class="card" style="margin-bottom: 1.5rem; padding: 1.5rem;">
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                    <span style="background: #f3f4f6; color: #374151; font-weight: 700; width: 32px; height: 32px; display: flex; text-align: center; border-radius: 50%; padding-top: 4px; border: 1px solid #d1d5db; flex-shrink: 0; justify-content: center;"><?php echo $index + 1; ?></span>
                    <div style="flex: 1;">
                        <h4 style="margin: 0 0 1rem 0; font-size: 1.1rem; line-height: 1.6; color: #1f2937;">
                            <?php echo nl2br(htmlspecialchars($q['content'])); ?>
                        </h4>

                        <!-- Media Handling -->
                        <?php if (!empty($q['media_url'])): ?>
                            <div style="margin-bottom: 1.5rem;">
                                <?php if ($q['media_type'] === 'image'): ?>
                                    <img src="images/<?php echo htmlspecialchars($q['media_url']); ?>?v=<?php echo time(); ?>" style="max-width: 100%; max-height: 400px; border-radius: 8px; border: 1px solid #e5e7eb;" alt="Question Image">
                                <?php elseif ($q['media_type'] === 'audio'): ?>
                                    <audio controls style="width: 100%;">
                                        <source src="audio/<?php echo htmlspecialchars($q['media_url']); ?>" type="audio/mpeg"> <!-- Adjust type if needed -->
                                        Trình duyệt không hỗ trợ audio.
                                    </audio>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Options -->
                        <div style="display: grid; gap: 0.8rem;">
                            <?php
                            $options = [
                                'A' => $q['option_a'],
                                'B' => $q['option_b'],
                                'C' => $q['option_c'],
                                'D' => $q['option_d']
                            ];
                            ?>
                            <?php foreach ($options as $key => $val): ?>
                                <label style="display: flex; align-items: flex-start; gap: 0.8rem; cursor: pointer; padding: 0.8rem; border: 1px solid #e5e7eb; border-radius: 8px; transition: all 0.2s; background: white;" onmouseover="this.style.borderColor='#9ca3af'" onmouseout="this.style.borderColor='#e5e7eb'">
                                    <input type="radio" name="question_<?php echo $q['id']; ?>" value="<?php echo $key; ?>" style="margin-top: 4px;">
                                    <span style="line-height: 1.5; color: #374151;">
                                        <b style="margin-right: 0.5rem; color: #111827;"><?php echo $key; ?>.</b> <?php echo htmlspecialchars($val); ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div style="margin-top: 2rem; text-align: center;">
            <button type="submit" class="btn" style="padding: 1rem 3rem; font-size: 1.1rem; font-weight: 700;">Nộp bài thi</button>
        </div>
    </form>
</div>

<script>
    // Timer Logic
    let durationMinutes = <?php echo isset($exam['duration_minutes']) ? $exam['duration_minutes'] : 60; ?>;
</script>
<script src="js/test.js"></script>
<script>
</script>

<?php
// Close tags
echo "</div></main></body></html>";
?>