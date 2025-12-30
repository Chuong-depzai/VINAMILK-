<style>
    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .admin-title {
        font-size: 32px;
        color: #0033a0;
        margin: 0;
    }

    .search-form {
        display: flex;
        gap: 10px;
    }

    .search-input {
        padding: 10px 15px;
        border: 2px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        width: 300px;
    }

    .btn-search {
        padding: 10px 20px;
        background: #0033a0;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-search:hover {
        background: #002780;
    }

    .users-table-wrapper {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #0033a0;
    }

    .users-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #0033a0;
        font-size: 14px;
    }

    .users-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }

    .users-table tbody tr:hover {
        background: #f5f8ff;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        background: #e0e0e0;
    }

    .role-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .role-admin {
        background: #fff3cd;
        color: #856404;
    }

    .role-user {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #d4edda;
        color: #155724;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-change-role {
        padding: 8px 16px;
        background: #ffc107;
        color: #333;
        border: none;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-change-role:hover {
        background: #e0a800;
    }

    .btn-delete-user {
        padding: 8px 16px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-delete-user:hover {
        background: #c82333;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .stat-value {
        font-size: 32px;
        font-weight: bold;
        color: #0033a0;
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
    }

    .empty-state-text {
        font-size: 18px;
        color: #666;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        padding: 30px;
        border-radius: 12px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }

    .modal-title {
        font-size: 20px;
        color: #0033a0;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .modal-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .form-select {
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .modal-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-confirm {
        flex: 1;
        padding: 12px;
        background: #0033a0;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-confirm:hover {
        background: #002780;
    }

    .btn-cancel {
        flex: 1;
        padding: 12px;
        background: #ccc;
        color: #333;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background: #bbb;
    }
</style>
<br><br>
<br><br>
<div class="admin-container">
    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?php echo number_format($totalUsers ?? 0); ?></div>
            <div class="stat-label">Tổng người dùng</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">
                <?php
                $adminCount = count(array_filter($users ?? [], function ($u) {
                    return $u['role'] === 'admin';
                }));
                echo $adminCount;
                ?>
            </div>
            <div class="stat-label">Quản trị viên</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">
                <?php
                $userCount = count(array_filter($users ?? [], function ($u) {
                    return $u['role'] === 'user';
                }));
                echo $userCount;
                ?>
            </div>
            <div class="stat-label">Khách hàng</div>
        </div>
    </div>

    <!-- Header -->
    <div class="admin-header">
        <h1 class="admin-title">👥 Quản lý người dùng</h1>
        <form method="GET" class="search-form">
            <input type="hidden" name="controller" value="admin">
            <input type="hidden" name="action" value="users">
            <input type="text"
                name="search"
                class="search-input"
                placeholder="Tìm kiếm theo tên, email, SĐT..."
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn-search">🔍 Tìm kiếm</button>
        </form>
    </div>

    <?php if (empty($users)): ?>
        <div class="empty-state">
            <p class="empty-state-text">Không có người dùng nào</p>
        </div>
    <?php else: ?>
        <div class="users-table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Họ tên</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Quyền</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <?php if (!empty($user['avatar'])): ?>
                                    <img src="uploads/avatars/<?php echo htmlspecialchars($user['avatar']); ?>"
                                        alt="Avatar"
                                        class="user-avatar">
                                <?php else: ?>
                                    <div class="user-avatar" style="display: flex; align-items: center; justify-content: center; background: #0033a0; color: white; font-weight: bold;">
                                        <?php echo substr($user['full_name'] ?? 'U', 0, 1); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($user['full_name'] ?? 'N/A'); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($user['phone'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
                            <td>
                                <span class="role-badge role-<?php echo $user['role']; ?>">
                                    <?php echo $user['role'] === 'admin' ? '👨‍💼 Admin' : '👤 Khách hàng'; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <button class="btn-change-role"
                                            onclick="openRoleModal(<?php echo $user['id']; ?>, '<?php echo $user['role']; ?>')">
                                            ⚙️ Quyền
                                        </button>
                                        <a href="index.php?controller=admin&action=deleteUser&id=<?php echo $user['id']; ?>"
                                            class="btn-delete-user"
                                            onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                            🗑️ Xóa
                                        </a>
                                    <?php else: ?>
                                        <span style="color: #999; font-size: 12px;">Chính bạn</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal thay đổi quyền -->
<div id="roleModal" class="modal">
    <div class="modal-content">
        <h3 class="modal-title">Thay đổi quyền người dùng</h3>
        <form method="POST" action="index.php?controller=admin&action=updateUserRole" class="modal-form">
            <input type="hidden" id="userId" name="user_id">

            <div class="form-group">
                <label class="form-label">Chọn quyền:</label>
                <select name="role" id="roleSelect" class="form-select" required>
                    <option value="user">👤 Khách hàng</option>
                    <option value="admin">👨‍💼 Quản trị viên</option>
                </select>
            </div>

            <div class="modal-buttons">
                <button type="submit" class="btn-confirm">✅ Cập nhật</button>
                <button type="button" class="btn-cancel" onclick="closeRoleModal()">❌ Hủy</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRoleModal(userId, currentRole) {
        document.getElementById('userId').value = userId;
        document.getElementById('roleSelect').value = currentRole;
        document.getElementById('roleModal').classList.add('active');
    }

    function closeRoleModal() {
        document.getElementById('roleModal').classList.remove('active');
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeRoleModal();
        }
    });
</script>