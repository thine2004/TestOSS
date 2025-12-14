<?php
require_once 'config.php';
$id = $_GET['id'] ?? 1;
?>
<?php
// Fetch User Info
$userId = $_SESSION['user_id'] ?? 0;
$userAvatar = 'images/avatar.png';
$userFullname = 'Sinh Viên';

if ($userId) {
    try {
        $stmt = $pdo->prepare("SELECT fullname, avatar FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if ($user) {
            $userFullname = $user['fullname'];
            if (!empty($user['avatar']) && file_exists('images/' . $user['avatar'])) {
                $userAvatar = 'images/' . $user['avatar'];
            }
        }
    } catch (Exception $e) {
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết Lab <?php echo $id; ?> | English Mastery</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/landing.css?v=<?php echo time(); ?>">
    <style>
        .lab-header {
            background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
            color: white;
            padding: 4rem 1rem 8rem 1rem;
            margin-bottom: -3rem;
            position: relative;
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            background: #fff;
            overflow: hidden;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .profile-info h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
            line-height: 1.2;
        }

        .lab-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
            margin-top: -3rem;
            position: relative;
            z-index: 10;
        }

        .main-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .main-layout {
                grid-template-columns: 1fr;
            }
        }

        .content-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .file-list {
            margin-top: 1.5rem;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            transition: 0.2s;
            text-decoration: none;
            color: #374151;
        }

        .file-item:last-child {
            border-bottom: none;
        }

        .file-item:hover {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .file-icon {
            margin-right: 1rem;
            color: #9ca3af;
            width: 20px;
            text-align: center;
        }

        .file-meta {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            font-weight: 600;
        }

        .sidebar-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f3f4f6;
            color: #374151;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .btn-back:hover {
            background: #e5e7eb;
        }
    </style>
</head>

<body style="background: #f3f4f6;">

    <?php require_once 'includes/navbar_public.php';
    renderNav(isLoggedIn()); ?>

    <div class="lab-header">
        <div class="profile-card">
            <div class="profile-avatar">
                <img src="<?php echo htmlspecialchars($userAvatar) . '?v=' . time(); ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <i class="fas fa-user-graduate" style="display: none; font-size: 2rem; color: #333;"></i>
            </div>
            <div class="profile-info">
                <div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.2rem; text-transform: uppercase;">Thông tin sinh viên</div>
                <h1>Đặng Trường Thi</h1>
                <div style="opacity: 0.9;">DH52201479 - D22_TH10</div>
            </div>
        </div>
    </div>

    <div class="lab-container">
        <div class="main-layout">

            <!-- Main Content -->
            <div class="content-card">
                <div style="display: flex; align-items: center; gap: 0.8rem; margin-bottom: 1rem;">
                    <div style="width: 32px; height: 32px; background: #ede9fe; color: #8b5cf6; border-radius: 50%; display: grid; place-items: center;"><i class="fas fa-flask"></i></div>
                    <h3 style="margin: 0; font-size: 1.2rem;">Danh sách Lab thực hành</h3>
                </div>

                <a href="labs.php" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại danh sách Lab</a>

                <h2 style="font-size: 1.25rem; margin-bottom: 1.5rem;">Buổi <?php echo $id; ?> – Danh sách file trong <span style="background: #f3f4f6; padding: 2px 8px; border-radius: 4px; font-size: 0.9rem;">Buoi<?php echo $id; ?></span></h2>

                <div class="file-list">
                    <?php
                    // Fetch files from DB
                    $dbFiles = [];
                    try {
                        $stmt = $pdo->prepare("SELECT * FROM lab_files WHERE lab_session = ? ORDER BY id ASC");
                        $stmt->execute([$id]);
                        $dbFiles = $stmt->fetchAll();
                    } catch (Exception $e) { /* Table might not exist yet */
                    }

                    // Recursive function to render tree
                    if (!function_exists('renderTree')) {
                        function renderTree($nodes, $level = 0)
                        {
                            // 1. Render Folders
                            if (isset($nodes['__folders__'])) {
                                foreach ($nodes['__folders__'] as $folderName => $subNodes) {
                                    $padding = $level * 1.5;
                                    $uniqueId = md5(uniqid($folderName, true));
                    ?>
                                    <div class="folder-group" style="margin-bottom: 0.5rem; margin-left: <?php echo $level > 0 ? '1rem' : '0'; ?>; <?php echo $level > 0 ? 'border-left: 2px solid #e5e7eb; padding-left: 0.5rem;' : ''; ?>">
                                        <div class="folder-header" style="background: <?php echo $level === 0 ? '#f9fafb' : 'transparent'; ?>; padding: 0.6rem 0.5rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; border-radius: 6px;"
                                            onclick="var el = document.getElementById('<?php echo $uniqueId; ?>'); el.style.display = (el.style.display === 'none' ? 'block' : 'none'); this.querySelector('.fa-chevron-right').style.transform = (el.style.display === 'block' ? 'rotate(90deg)' : 'rotate(0deg)');">
                                            <i class="fas fa-chevron-right" style="font-size: 0.8rem; color: #9ca3af; transition: transform 0.2s;"></i>
                                            <i class="fas fa-folder" style="color: #fbbf24;"></i>
                                            <span style="font-weight: 600; color: #374151; font-size: 0.95rem;"><?php echo htmlspecialchars($folderName); ?></span>
                                        </div>
                                        <div id="<?php echo $uniqueId; ?>" class="folder-content" style="display: <?php echo $level === 0 ? 'block' : 'none'; ?>; padding-left: 0.5rem;">
                                            <?php renderTree($subNodes, $level + 1); ?>
                                        </div>
                                    </div>
                                <?php
                                }
                            }
                            // 2. Render Files
                            if (isset($nodes['__files__'])) {
                                foreach ($nodes['__files__'] as $fileData) {
                                    $item = $fileData['data']; // The DB row
                                    $name = $fileData['name']; // The filename part

                                    // Verify physical file exists
                                    if (!file_exists('files/' . $item['file_path'])) continue;

                                    $icon = 'far fa-file';
                                    if (strpos($item['file_type'], 'PHP') !== false) $icon = 'far fa-file-code';
                                    elseif (strpos($item['file_type'], 'HTML') !== false) $icon = 'fab fa-html5';
                                    elseif (strpos($item['file_type'], 'PDF') !== false) $icon = 'far fa-file-pdf';
                                    elseif (strpos($item['file_type'], 'RAR') !== false || strpos($item['file_type'], 'ZIP') !== false) $icon = 'far fa-file-archive';
                                ?>
                                    <a href="files/<?php echo htmlspecialchars($item['file_path']); ?>" class="file-item" target="_blank" style="padding: 0.5rem 0.5rem 0.5rem 2rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; text-decoration: none; color: #374151; background: white; border-radius: 4px; margin-bottom: 2px;">
                                        <div style="display: flex; align-items: center;">
                                            <div class="file-icon" style="margin-right: 0.8rem; color: #9ca3af;"><i class="<?php echo $icon; ?>"></i></div>
                                            <span style="font-size: 0.9rem;"><?php echo htmlspecialchars($name); ?></span>
                                        </div>
                                        <span class="file-meta" style="font-size: 0.75rem; color: #9ca3af;"><?php echo $item['file_type']; ?></span>
                                    </a>
                            <?php
                                }
                            }
                        }
                    }

                    // Build Tree from DB Files
                    $tree = [];
                    $singles = [];

                    foreach ($dbFiles as $f) {
                        $baseTitle = $f['title'];
                        $fullPath = $f['display_path'] ?? null;

                        // Fallback: Try regex if display_path is empty (Legacy Data support)
                        if (empty($fullPath) && preg_match('/^(.*?)\s\((.*)\)$/', $f['title'], $matches)) {
                            $baseTitle = $matches[1];
                            $fullPath = $matches[2];
                        }

                        if (!empty($fullPath)) {
                            // It has a structure -> Add to Tree

                            // Initialize Base Group
                            if (!isset($tree['__folders__'][$baseTitle])) {
                                $tree['__folders__'][$baseTitle] = [];
                            }

                            $current = &$tree['__folders__'][$baseTitle];
                            $parts = explode('/', $fullPath);
                            $count = count($parts);

                            foreach ($parts as $i => $part) {
                                if ($i === $count - 1) {
                                    // It's a file
                                    $current['__files__'][] = ['name' => $part, 'data' => $f];
                                } else {
                                    // It's a folder
                                    if (!isset($current['__folders__'][$part])) {
                                        $current['__folders__'][$part] = [];
                                    }
                                    $current = &$current['__folders__'][$part];
                                }
                            }
                            unset($current); // Break reference
                        } else {
                            // No structure -> Single File
                            $singles[] = $f;
                        }
                    }

                    if (count($dbFiles) > 0):
                        // Render the recursive tree
                        renderTree($tree);

                        // Render Singles (Legacy or flat files)
                        foreach ($singles as $f):
                            $icon = 'far fa-file';
                            $type = $f['file_type'];
                            if (strpos($type, 'PHP') !== false) $icon = 'far fa-file-code';
                            elseif (strpos($type, 'HTML') !== false) $icon = 'fab fa-html5';
                            elseif (strpos($type, 'PDF') !== false) $icon = 'far fa-file-pdf';
                            elseif (strpos($type, 'RAR') !== false || strpos($type, 'ZIP') !== false) $icon = 'far fa-file-archive';
                            ?>
                            <a href="files/<?php echo htmlspecialchars($f['file_path']); ?>" class="file-item" target="_blank">
                                <div style="display: flex; align-items: center;">
                                    <div class="file-icon"><i class="<?php echo $icon; ?>"></i></div>
                                    <span><?php echo htmlspecialchars($f['title']); ?></span>
                                </div>
                                <span class="file-meta"><?php echo $f['file_type']; ?></span>
                            </a>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <div style="text-align: center; padding: 2rem; color: #9ca3af;">
                            <i class="far fa-folder-open" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                            <p>Chưa có file nào cho buổi thực hành này.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->


            <div class="sidebar-card">
                <h3 style="margin: 0 0 1rem 0; font-size: 1.1rem;">Kết nối</h3>
                <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                    <a href="https://github.com/thine2004" style="display: flex; gap: 1rem; align-items: center; color: #4b5563;">
                        <i class="fab fa-github" style="width: 20px;"></i> GitHub
                    </a>
                    <a href="https://www.facebook.com/angtruongthi.2025/" style="display: flex; gap: 1rem; align-items: center; color: #4b5563;">
                        <i class="fab fa-facebook" style="width: 20px; color: #1877f2;"></i> Facebook
                    </a>
                    <a href="#" style="display: flex; gap: 1rem; align-items: center; color: #4b5563;">
                        <i class="fab fa-instagram" style="width: 20px; color: #e1306c;"></i> Instagram
                    </a>
                    <a href="#" style="display: flex; gap: 1rem; align-items: center; color: #4b5563;">
                        <i class="fab fa-tiktok" style="width: 20px; color: #000;"></i> TikTok
                    </a>
                </div>
            </div>
        </div>

    </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</body>

</html>