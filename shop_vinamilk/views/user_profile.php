<style>
    .profile-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .profile-title {
        font-size: 32px;
        color: #0033a0;
        margin-bottom: 10px;
    }

    .profile-main {
        background: white;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .profile-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        border-bottom: 2px solid #f0f0f0;
    }

    .tab-btn {
        padding: 12px 24px;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        color: #666;
        transition: all 0.3s;
    }

    .tab-btn:hover {
        color: #0033a0;
    }

    .tab-btn.active {
        color: #0033a0;
        border-bottom-color: #0033a0;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 18px;
        color: #0033a0;
        margin-bottom: 20px;
        font-weight: 700;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .user-info-card {
        background: linear-gradient(135deg, #0033a0 0%, #005ce6 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        text-align: center;
    }

    .user-info-name {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .user-info-phone {
        font-size: 16px;
        opacity: 0.9;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Avatar Upload Section */
    .avatar-upload-section {
        text-align: center;
        padding: 30px;
        background: linear-gradient(135deg, #f5f8ff 0%, #e6f0ff 100%);
        border-radius: 12px;
        margin-bottom: 30px;
    }

    .current-avatar-display,
    .avatar-placeholder-display {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto;
        border: 4px solid white;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .current-avatar-display {
        object-fit: cover;
    }

    .avatar-placeholder-display {
        background: white;
        color: #0033a0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 48px;
    }

    .avatar-camera-icon {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 40px;
        height: 40px;
        background: #0033a0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transition: all 0.3s;
    }

    .avatar-camera-icon:hover {
        background: #002780;
        transform: scale(1.1);
    }

    .btn-upload-avatar,
    .btn-delete-avatar {
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-upload-avatar {
        background: #0033a0;
        color: white;
    }

    .btn-upload-avatar:hover {
        background: #002780;
    }

    .btn-delete-avatar {
        background: #dc3545;
        color: white;
    }

    .btn-delete-avatar:hover {
        background: #c82333;
    }

    @media (max-width: 768px) {
        .profile-main {
            padding: 20px;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-header">
        <h1 class="profile-title">👤 Thông tin cá nhân</h1>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?php
            echo htmlspecialchars($_SESSION['success_message']);
            unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-error">
            <?php
            echo htmlspecialchars($_SESSION['error_message']);
            unset($_SESSION['error_message']);
            ?>
        </div>
    <?php endif; ?>

    <!-- User Info Card -->
    <div class="user-info-card">
        <div class="user-info-name"><?php echo htmlspecialchars($user['full_name'] ?: 'Người dùng'); ?></div>
        <div class="user-info-phone">📞 <?php echo htmlspecialchars($user['phone']); ?></div>
        <?php if (!empty($user['email'])): ?>
            <div class="user-info-phone">📧 <?php echo htmlspecialchars($user['email']); ?></div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div class="profile-main">
        <!-- Tabs -->
        <div class="profile-tabs">
            <button class="tab-btn active" onclick="switchTab('info')">
                📝 Thông tin cá nhân
            </button>
            <button class="tab-btn" onclick="switchTab('password')">
                🔒 Đổi mật khẩu
            </button>
        </div>

        <!-- Tab: Thông tin cá nhân -->
        <div id="tab-info" class="tab-content active">
            <!-- AVATAR UPLOAD SECTION -->
            <div class="avatar-upload-section">
                <h3 class="section-title" style="border: none; margin-bottom: 20px;">📸 Ảnh đại diện</h3>

                <!-- Current Avatar Display -->
                <div style="position: relative; display: inline-block;">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="uploads/avatars/<?php echo htmlspecialchars($user['avatar']); ?>"
                            class="current-avatar-display"
                            alt="Avatar"
                            id="avatarPreview">
                    <?php else: ?>
                        <div class="avatar-placeholder-display" id="avatarPreview">
                            <?php echo strtoupper(substr($user['full_name'] ?? 'U', 0, 1)); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Camera Icon Overlay -->
                    <label for="avatarInput" class="avatar-camera-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="2">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                            <circle cx="12" cy="13" r="4" />
                        </svg>
                    </label>
                </div>

                <!-- Upload Form -->
                <form method="POST" action="index.php?controller=user&action=uploadAvatar"
                    enctype="multipart/form-data"
                    class="avatar-upload-form"
                    id="avatarForm">
                    <input type="file"
                        name="avatar"
                        id="avatarInput"
                        accept="image/*"
                        style="display: none;"
                        onchange="previewAvatar(this)">

                    <div style="display: flex; gap: 10px; margin-top: 15px; justify-content: center;">
                        <button type="button"
                            class="btn-upload-avatar"
                            onclick="document.getElementById('avatarInput').click()">
                            📷 Chọn ảnh mới
                        </button>

                        <?php if (!empty($user['avatar'])): ?>
                            <a href="index.php?controller=user&action=deleteAvatar"
                                class="btn-delete-avatar"
                                onclick="return confirm('Bạn có chắc muốn xóa ảnh đại diện?')">
                                🗑️ Xóa ảnh
                            </a>
                        <?php endif; ?>
                    </div>

                    <button type="submit"
                        id="btnUploadAvatar"
                        class="btn-upload-avatar"
                        style="display: none; margin-top: 10px;">
                        💾 Lưu ảnh mới
                    </button>
                </form>

                <p style="font-size: 12px; color: #666; margin-top: 15px;">
                    📌 Chấp nhận: JPG, PNG, GIF (tối đa 5MB)
                </p>
            </div>

            <!-- Profile Form -->
            <form method="POST" action="index.php?controller=user&action=updateProfile" class="auth-form">
                <div class="form-section">
                    <h3 class="section-title">Thông tin cơ bản</h3>

                    <div class="form-group">
                        <label for="full_name" class="form-label">Họ và tên <span class="required">*</span></label>
                        <input type="text"
                            id="full_name"
                            name="full_name"
                            class="form-input"
                            value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email"
                            id="email"
                            name="email"
                            class="form-input"
                            value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text"
                            id="phone"
                            class="form-input"
                            value="<?php echo htmlspecialchars($user['phone']); ?>"
                            disabled>
                        <p class="form-help-text">Số điện thoại không thể thay đổi</p>
                    </div>

                    <div class="form-group">
                        <label for="gender" class="form-label">Giới tính</label>
                        <select id="gender" name="gender" class="form-select">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="male" <?php echo ($user['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Nam</option>
                            <option value="female" <?php echo ($user['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Nữ</option>
                            <option value="other" <?php echo ($user['gender'] ?? '') === 'other' ? 'selected' : ''; ?>>Khác</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth" class="form-label">Ngày sinh</label>
                        <input type="date"
                            id="date_of_birth"
                            name="date_of_birth"
                            class="form-input"
                            value="<?php echo htmlspecialchars($user['date_of_birth'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">Địa chỉ</h3>

                    <div class="form-group">
                        <label for="address" class="form-label">Địa chỉ chi tiết</label>
                        <textarea id="address"
                            name="address"
                            class="form-textarea"
                            rows="3"
                            placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                </div>

                <button type="submit" class="btn-auth-submit">
                    💾 Lưu thay đổi
                </button>
            </form>
        </div>

        <!-- Tab: Đổi mật khẩu -->
        <div id="tab-password" class="tab-content">
            <form method="POST" action="index.php?controller=user&action=changePassword" class="auth-form">
                <div class="form-section">
                    <h3 class="section-title">Đổi mật khẩu</h3>

                    <div class="form-group">
                        <label for="current_password" class="form-label">Mật khẩu hiện tại <span class="required">*</span></label>
                        <input type="password"
                            id="current_password"
                            name="current_password"
                            class="form-input"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="new_password" class="form-label">Mật khẩu mới <span class="required">*</span></label>
                        <input type="password"
                            id="new_password"
                            name="new_password"
                            class="form-input"
                            placeholder="Ít nhất 6 ký tự"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới <span class="required">*</span></label>
                        <input type="password"
                            id="confirm_password"
                            name="confirm_password"
                            class="form-input"
                            required>
                    </div>
                </div>

                <button type="submit" class="btn-auth-submit">
                    🔒 Đổi mật khẩu
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // Show selected tab
        document.getElementById('tab-' + tabName).classList.add('active');
        event.target.classList.add('active');
    }

    // Preview avatar before upload
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');

                // Replace with image tag
                preview.outerHTML = `
                    <img src="${e.target.result}" 
                         class="current-avatar-display" 
                         alt="Avatar Preview"
                         id="avatarPreview">
                `;

                // Show upload button
                document.getElementById('btnUploadAvatar').style.display = 'inline-block';
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>