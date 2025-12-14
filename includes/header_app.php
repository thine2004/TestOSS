<?php
require_once 'config.php';
// Common Sidebar & Header Logic
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | English Mastery</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
</head>

<body style="background: var(--bg-body);">

    <div class="app-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="brand">
                <i class="fas fa-layer-group"></i> Mastery
            </div>

            <ul class="menu">
                <li class="menu-item">
                    <a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="fas fa-th-large"></i> Tổng quan
                    </a>
                </li>
                <li class="menu-item">
                    <a href="roadmap.php" class="<?php echo $current_page == 'roadmap.php' ? 'active' : ''; ?>">
                        <i class="fas fa-map-signs"></i> Lộ trình Luyện thi
                    </a>
                </li>
                <li class="menu-item">
                    <a href="history.php" class="<?php echo $current_page == 'history.php' ? 'active' : ''; ?>">
                        <i class="fas fa-history"></i> Lịch sử học
                    </a>
                </li>
                <!--
            <li class="menu-item">
                <a href="errors.php" class="<?php echo $current_page == 'errors.php' ? 'active' : ''; ?>">
                    <i class="fas fa-bug"></i> Báo lỗi
                </a>
            </li>
            -->
                <li class="menu-item">
                    <a href="index.php">
                        <i class="fas fa-home"></i> Trang chủ
                    </a>
                </li>
            </ul>


        </aside>

        <!-- MAIN CONTENT WRAPPER -->
        <main class="main-content">
            <!-- Top Mobile Header (Optional) -->
            <header style="margin-bottom: 2rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; display: flex; justify-content: space-between;">
                <h2 style="font-weight: 700; color: var(--text-main);">
                    <?php
                    if ($current_page == 'dashboard.php') echo 'Dashboard Overview';
                    elseif ($current_page == 'roadmap.php') echo 'Lộ Trình Luyện Thi';
                    elseif ($current_page == 'history.php') echo 'Lịch Sử Học Tập';
                    else echo 'English Mastery';
                    ?>
                </h2>
                <!-- <div class="top-actions">...</div> -->
            </header>