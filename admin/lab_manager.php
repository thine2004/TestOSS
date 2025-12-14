<?php
require_once '../config.php';
require_once 'includes/header.php';

// Bonus Security Check for this specific page
if ($_SESSION['username'] !== 'thi2004') {
    setFlash('error', 'Access denied. Only Super Admin can access Labs.');
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

// Auto-create table if not exists (Simplified migration)
try {
    $pdo->query("SELECT 1 FROM lab_files LIMIT 1");
} catch (Exception $e) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS lab_files (
        id INT AUTO_INCREMENT PRIMARY KEY,
        lab_session INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        file_path VARCHAR(255) NOT NULL,
        file_type VARCHAR(10) DEFAULT 'FILE',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
}

// Handle Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_FILES['lab_file']) || isset($_FILES['lab_folder']))) {
    // Only process upload if it's not a bulk delete action (just in case)
    if (!isset($_POST['bulk_delete'])) {
        $labSession = $_POST['lab_session'];
        $baseTitle = $_POST['title']; // Base title

        // Subfolder logic
        $subDirName = "lab" . $labSession;
        $targetDir = "../files/" . $subDirName . "/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

        $inputs = ['lab_file', 'lab_folder'];
        $successCount = 0;

        // Decode paths captured by JS
        $folderPaths = isset($_POST['folder_paths']) ? json_decode($_POST['folder_paths'], true) : [];
        $globalFolderIndex = 0;

        foreach ($inputs as $inputKey) {
            if (!isset($_FILES[$inputKey])) continue;

            $fileInput = $_FILES[$inputKey];
            $fileCount = is_array($fileInput['name']) ? count($fileInput['name']) : 1;

            for ($i = 0; $i < $fileCount; $i++) {
                $name = is_array($fileInput['name']) ? $fileInput['name'][$i] : $fileInput['name'];
                $tmpName = is_array($fileInput['tmp_name']) ? $fileInput['tmp_name'][$i] : $fileInput['tmp_name'];
                $error = is_array($fileInput['error']) ? $fileInput['error'][$i] : $fileInput['error'];

                if ($error === UPLOAD_ERR_OK && !empty($name)) {
                    // 1. Determine Display Name (Structure) FIRST
                    $displayName = basename($name); // Default
                    if ($inputKey === 'lab_folder') {
                        $jsPath = $folderPaths[$globalFolderIndex] ?? null;
                        $phpPath = is_array($fileInput['full_path'] ?? null) ? $fileInput['full_path'][$i] : ($fileInput['full_path'] ?? null);
                        $displayName = $jsPath ?? $phpPath ?? $name;
                        $globalFolderIndex++;
                    }

                    // 2. Determine Physical Path based on Structure
                    // If displayName is "Folder/Sub/File.txt", we create those folders physically.
                    $relDir = dirname($displayName);
                    // dirname returns '.' if no slash found
                    if ($relDir === '.') $relDir = '';

                    // Create subdirectories if needed
                    $finalTargetDir = $targetDir . ($relDir ? $relDir . '/' : '');
                    if (!file_exists($finalTargetDir)) {
                        mkdir($finalTargetDir, 0777, true);
                    }

                    $fileName = basename($displayName);

                    // 3. Collision Handling in destination folder
                    $uniqueName = $fileName;
                    $counter = 1;
                    $nameNoExt = pathinfo($fileName, PATHINFO_FILENAME);
                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);

                    while (file_exists($finalTargetDir . $uniqueName)) {
                        $uniqueName = $nameNoExt . "_" . $counter . "." . $ext;
                        $counter++;
                    }

                    $targetFile = $finalTargetDir . $uniqueName;
                    // DB Path: labX/Folder/Sub/uniqueName
                    $dbPath = $subDirName . '/' . ($relDir ? $relDir . '/' : '') . $uniqueName;
                    $fileType = strtoupper($ext);

                    // 4. Set DB Values
                    $displayPath = $displayName;
                    $title = $baseTitle;

                    if (move_uploaded_file($tmpName, $targetFile)) {
                        $stmt = $pdo->prepare("INSERT INTO lab_files (lab_session, title, display_path, file_path, file_type) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$labSession, $title, $displayPath, $dbPath, $fileType]);
                        $successCount++;
                    }
                }
            }
        }

        if ($successCount > 0) {
            echo "<script>alert('Upload thành công " . $successCount . " file!'); window.location.href='lab_manager.php';</script>";
        } else {
            echo "<script>alert('Lỗi: Không có file nào được upload thành công.');</script>";
        }
    }
}

// Handle Single Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Delete file from disk
    $stmt = $pdo->prepare("SELECT file_path FROM lab_files WHERE id=?");
    $stmt->execute([$id]);
    $f = $stmt->fetch();
    if ($f && $f['file_path']) {
        $filePath = "../files/" . $f['file_path'];
        if (file_exists($filePath)) unlink($filePath);
    }

    $pdo->prepare("DELETE FROM lab_files WHERE id=?")->execute([$id]);
    echo "<script>window.location.href='lab_manager.php';</script>";
}

// Handle Bulk Delete
if (isset($_POST['bulk_delete']) && isset($_POST['delete_ids'])) {
    $ids = $_POST['delete_ids'];
    $count = 0;
    foreach ($ids as $id) {
        $stmt = $pdo->prepare("SELECT file_path FROM lab_files WHERE id=?");
        $stmt->execute([$id]);
        $f = $stmt->fetch();
        if ($f && $f['file_path']) {
            $filePath = "../files/" . $f['file_path'];
            if (file_exists($filePath)) unlink($filePath);
        }
        $pdo->prepare("DELETE FROM lab_files WHERE id=?")->execute([$id]);
        $count++;
    }
    echo "<script>alert('Đã xóa " . $count . " file.'); window.location.href='lab_manager.php';</script>";
}


// Fetch Files
$files = $pdo->query("SELECT * FROM lab_files ORDER BY lab_session ASC, id ASC")->fetchAll();
?>

<div class="card-primary">
    <div class="card-header">
        <h3>Quản lý Tài liệu Lab</h3>
    </div>
    <div class="card-body">

        <!-- Upload Form -->
        <form method="POST" enctype="multipart/form-data" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
            <div style="display: grid; grid-template-columns: 1fr 2fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                <div>
                    <label style="font-weight: 600;">Chọn Buổi Lab</label>
                    <select name="lab_session" class="form-control" required>
                        <?php for ($i = 1; $i <= 8; $i++): ?>
                            <option value="<?php echo $i; ?>">Bài tập Buổi <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label style="font-weight: 600;">Tên hiển thị</label>
                    <input type="text" name="title" class="form-control" placeholder="Ví dụ: Code mẫu PHP" required>
                </div>
                <div>
                    <label style="font-weight: 600;">File tài liệu (Chọn nhiều)</label>
                    <input type="file" name="lab_file[]" class="form-control" style="padding: 3px;" multiple>
                </div>
                <div id="folder-container">
                    <div class="folder-input-group" style="margin-bottom: 0.5rem;">
                        <label style="font-weight: 600;">Hoặc Upload Folder</label>
                        <input type="file" name="lab_folder[]" class="form-control" style="padding: 3px;" webkitdirectory directory multiple>
                    </div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <button type="button" class="btn-sm btn-info" onclick="addFolderInput()"><i class="fas fa-plus"></i> Chọn thêm folder khác</button>
                    <small class="text-muted" style="display: block; margin-top: 5px;">(Nếu muốn up nhiều folder khác nhau, nhấn nút này để thêm ô chọn)</small>
                </div>
                <div>
                    <input type="hidden" name="folder_paths" id="folder_paths">
                    <button type="button" class="admin-btn btn-success" onclick="prepareUpload(); this.form.submit();"><i class="fas fa-upload"></i> Upload</button>
                </div>
            </div>
        </form>

        <script>
            function addFolderInput() {
                var container = document.getElementById('folder-container');
                var div = document.createElement('div');
                div.className = 'folder-input-group';
                div.style.marginBottom = '0.5rem';
                div.innerHTML = '<label style="font-weight: 600;">Folder tiếp theo</label>' +
                    '<div style="display: flex; gap: 5px;">' +
                    '<input type="file" name="lab_folder[]" class="form-control" style="padding: 3px;" webkitdirectory directory multiple>' +
                    '<button type="button" class="btn-danger" style="border:none; border-radius:4px; px:20px;" onclick="this.parentNode.parentNode.remove()"><i class="fas fa-times"></i></button>' +
                    '</div>';
                container.appendChild(div);
            }

            function prepareUpload() {
                var inputs = document.querySelectorAll('input[name="lab_folder[]"]');
                var paths = [];
                var hasFiles = false;
                inputs.forEach(function(input) {
                    if (input.files.length > 0) hasFiles = true;
                    for (var i = 0; i < input.files.length; i++) {
                        paths.push(input.files[i].webkitRelativePath || input.files[i].name);
                    }
                });

                // Debug / Warning
                if (hasFiles) {
                    // Show first few paths to verify
                    alert("Đang xử lý " + paths.length + " file từ folder.\nVí dụ path: " + (paths[0] || ''));
                } else {
                    // Check if normal files are selected
                    var normalFiles = document.querySelector('input[name="lab_file[]"]').files.length;
                    if (normalFiles > 0) {
                        if (!confirm("Bạn đang chọn upload LẺ FILE (không giữ cấu trúc folder).\nNếu muốn giữ folder, hãy dùng ô 'Upload Folder' bên dưới.\nBạn có muốn tiếp tục upload LẺ không?")) {
                            return; // Cancel submit
                        }
                    }
                }

                document.getElementById('folder_paths').value = JSON.stringify(paths);
            }
        </script>

        <!-- Bulk Actions & List -->
        <form method="POST" id="bulkForm" onsubmit="return confirm('Bạn có chắc muốn xóa các file đã chọn?');">
            <input type="hidden" name="bulk_delete" value="1">

            <div style="margin-bottom: 1rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="admin-btn btn-danger" id="bulkDeleteBtn" disabled>
                    <i class="fas fa-trash"></i> Xóa đã chọn
                </button>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                        </th>
                        <th>Buổi</th>
                        <th>Tên tài liệu</th>
                        <th>Loại</th>
                        <th>File</th>
                        <th>Ngày đăng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($files as $f): ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="delete_ids[]" value="<?php echo $f['id']; ?>" class="file-checkbox" onclick="updateBulkBtn()">
                            </td>
                            <td><span class="badge badge-info">Buổi <?php echo $f['lab_session']; ?></span></td>
                            <td><?php echo htmlspecialchars($f['title']); ?></td>
                            <td><?php echo $f['file_type']; ?></td>
                            <td><?php echo $f['file_path']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($f['created_at'])); ?></td>
                            <td>
                                <a href="?delete=<?php echo $f['id']; ?>" class="admin-btn btn-danger" onclick="return confirm('Xóa file này?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($files)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Chưa có file nào được upload.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>

    </div>
</div>

<script>
    function toggleSelectAll(source) {
        var checkboxes = document.getElementsByClassName('file-checkbox');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
        updateBulkBtn();
    }

    function updateBulkBtn() {
        var checkboxes = document.getElementsByClassName('file-checkbox');
        var checkedCount = 0;
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            if (checkboxes[i].checked) checkedCount++;
        }
        document.getElementById('bulkDeleteBtn').disabled = checkedCount === 0;
    }
</script>

<?php require_once 'includes/footer.php'; ?>