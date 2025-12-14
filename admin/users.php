<?php
require_once '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit;
}

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $fullname = $_POST['fullname'] ?? '';
        $role = $_POST['role'] ?? 'user';

        // Check if username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            setFlash('error', 'Username already exists!');
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, plain_password, fullname, role, status) VALUES (?, ?, ?, ?, ?, 'active')");
            $stmt->execute([$username, $hash, $password, $fullname, $role]);
            setFlash('success', 'User added successfully!');
        }
        header("Location: users.php");
        exit;
    } elseif ($action === 'update') {
        $id = $_POST['id'] ?? null;
        $fullname = $_POST['fullname'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $curr = $stmt->fetch();

            if ($curr) {
                // Constraint Check for thi2004 - Strict Protection
                // NO ONE else can edit thi2004 except thi2004 themselves
                if ($curr['username'] === 'thi2004' && $curr['role'] === 'admin') {
                    if (($_SESSION['username'] ?? '') !== 'thi2004') {
                        setFlash('error', 'Bạn không có quyền chỉnh sửa tài khoản Super Admin!');
                        header("Location: users.php");
                        exit;
                    }
                }

                if (!empty($password)) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET fullname = ?, role = ?, password = ?, plain_password = ? WHERE id = ?");
                    $stmt->execute([$fullname, $role, $hash, $password, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET fullname = ?, role = ? WHERE id = ?");
                    $stmt->execute([$fullname, $role, $id]);
                }
                setFlash('success', 'Cập nhật thành công!');
            }
        }
        header("Location: users.php");
        exit;
    }
}
// [Truncated]

// Handle Delete / Status
if ($action === 'delete' && $id) {
    if ($id == $_SESSION['user_id']) {
        setFlash('error', 'Cannot delete yourself!');
    } else {
        // Protect super admin
        $stmt = $pdo->prepare("SELECT username, role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $targetUser = $stmt->fetch();

        if ($targetUser && $targetUser['username'] === 'thi2004' && $targetUser['role'] === 'admin') {
            setFlash('error', 'Không thể xóa tài khoản Super Admin này!');
        } else {
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
            setFlash('success', 'User deleted.');
        }
    }
    header("Location: users.php");
    exit;
} elseif ($action === 'toggle_status' && $id) {
    $stmt = $pdo->prepare("SELECT status FROM users WHERE id=?");
    $stmt->execute([$id]);
    $u = $stmt->fetch();
    $newStatus = (isset($u['status']) && $u['status'] === 'blocked') ? 'active' : 'blocked';

    // Protect thi2004 from being blocked
    $stmt = $pdo->prepare("SELECT username, role FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $targetUser = $stmt->fetch();
    if ($targetUser && $targetUser['username'] === 'thi2004' && $targetUser['role'] === 'admin') {
        setFlash('error', 'Không thể khóa tài khoản Super Admin!');
    } else {
        $pdo->prepare("UPDATE users SET status=? WHERE id=?")->execute([$newStatus, $id]);
        setFlash('success', "User $newStatus.");
    }

    header("Location: users.php");
    exit;
}

require_once 'includes/header.php';
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card-primary">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>Quản lý Thành Viên</h3>
        <button onclick="document.getElementById('addUserModal').style.display='flex'" class="admin-btn btn-success"><i class="fas fa-plus"></i> Thêm thành viên</button>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; width: 400px; padding: 2rem; border-radius: 8px; position: relative;">
            <h3 style="margin-top: 0;">Thêm thành viên mới</h3>
            <form method="POST" action="?action=create">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Username</label>
                    <input type="text" name="username" required class="form-control" style="width: 100%; padding: 0.5rem;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Họ và tên</label>
                    <input type="text" name="fullname" required class="form-control" style="width: 100%; padding: 0.5rem;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Mật khẩu</label>
                    <input type="password" name="password" required class="form-control" style="width: 100%; padding: 0.5rem;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Vai trò</label>
                    <select name="role" class="form-control" style="width: 100%; padding: 0.5rem;">
                        <option value="user">Học viên (User)</option>
                        <option value="admin">Quản trị viên (Admin)</option>
                    </select>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="document.getElementById('addUserModal').style.display='none'" class="admin-btn btn-danger">Hủy</button>
                    <button type="submit" class="admin-btn btn-success">Thêm mới</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; width: 400px; padding: 2rem; border-radius: 8px; position: relative;">
            <h3 style="margin-top: 0;">Cập nhật thành viên</h3>
            <form method="POST" action="?action=update">
                <input type="hidden" name="id" id="edit_id">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Username</label>
                    <input type="text" id="edit_username" class="form-control" disabled style="width: 100%; padding: 0.5rem; background: #eee;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Họ và tên</label>
                    <input type="text" name="fullname" id="edit_fullname" required class="form-control" style="width: 100%; padding: 0.5rem;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Mật khẩu mới (Để trống nếu không đổi)</label>
                    <input type="password" name="password" class="form-control" style="width: 100%; padding: 0.5rem;" placeholder="******">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Vai trò</label>
                    <select name="role" id="edit_role" class="form-control" style="width: 100%; padding: 0.5rem;">
                        <option value="user">Học viên (User)</option>
                        <option value="admin">Quản trị viên (Admin)</option>
                    </select>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="document.getElementById('editUserModal').style.display='none'" class="admin-btn btn-danger">Hủy</button>
                    <button type="submit" class="admin-btn btn-success">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Mật khẩu</th>
                    <th>Fullname</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo $u['id']; ?></td>
                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                        <td>
                            <?php
                            if ($u['username'] === 'thi2004' && $u['role'] === 'admin') {
                                echo '******';
                            } else {
                                echo htmlspecialchars($u['plain_password'] ?? '');
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($u['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($u['role']); ?></td>
                        <td>
                            <?php if (isset($u['status']) && $u['status'] === 'blocked'): ?>
                                <span class="badge" style="background: red; color: white; padding: 3px 7px; border-radius: 4px;">KHÓA</span>
                            <?php else: ?>
                                <span class="badge" style="background: green; color: white; padding: 3px 7px; border-radius: 4px;">Hoạt động</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            // Only show Edit button if it's NOT thi2004/admin, OR if I am thi2004
                            $isTargetThi2004 = ($u['username'] === 'thi2004' && $u['role'] === 'admin');
                            $isSessionThi2004 = (($_SESSION['username'] ?? '') === 'thi2004');

                            if (!$isTargetThi2004 || $isSessionThi2004):
                            ?>
                                <button type="button" class="admin-btn btn-info" onclick='openEditModal(<?php echo htmlspecialchars(json_encode($u), ENT_QUOTES, "UTF-8"); ?>)'>
                                    <i class="fas fa-edit"></i>
                                </button>
                            <?php endif; ?>

                            <?php


                            $isSuperAdminTarget = ($u['username'] === 'thi2004' && $u['role'] === 'admin');
                            // Standard protection: Cannot delete/lock admins if I am an admin? Usually admins can manage users. 
                            // Admins shouldn't delete themselves.
                            // And special protection for thi2004.

                            if (($u['role'] !== 'admin' || $u['id'] != $_SESSION['user_id']) && !$isSuperAdminTarget):
                            ?>
                                <a href="?action=toggle_status&id=<?php echo $u['id']; ?>" class="admin-btn btn-warning" title="Khóa/Mở khóa">
                                    <i class="fas <?php echo (isset($u['status']) && $u['status'] === 'blocked') ? 'fa-unlock' : 'fa-lock'; ?>"></i>
                                </a>
                                <a href="?action=delete&id=<?php echo $u['id']; ?>" class="admin-btn btn-danger" onclick="return confirm('Xóa?');"><i class="fas fa-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function openEditModal(user) {
        document.getElementById('edit_id').value = user.id;
        document.getElementById('edit_username').value = user.username;
        document.getElementById('edit_fullname').value = user.fullname;
        document.getElementById('edit_role').value = user.role;

        // Show modal
        document.getElementById('editUserModal').style.display = 'flex';
    }
</script>

<?php require_once 'includes/footer.php'; ?>