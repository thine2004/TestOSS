<?php
if (!isLoggedIn() || !isAdmin()) {
    setFlash('error', 'Access denied.');
    redirect('../login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | English System</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="admin-layout">

    <?php include 'includes/sidebar.php'; ?>

    <div class="admin-content-wrapper">
        <nav class="admin-header">
            <div style="color: #6c757d; cursor:pointer;"><i class="fas fa-bars"></i></div>
            <div style="font-weight: bold; color: #343a40;">English Learning System</div>
        </nav>
        <main class="admin-main">
            <!-- Flash Messages -->
            <?php if ($flash = getFlash()): ?>
                <div style="padding: 1rem; margin-bottom: 1rem; border-radius: 4px; color: white; background: <?php echo $flash['type'] == 'success' ? '#28a745' : '#dc3545'; ?>;">
                    <?php echo $flash['message']; ?>
                </div>
            <?php endif; ?>