<div class="page-container">
    <div class="auth-container">
        <div class="auth-box">
            <h1 class="auth-title">Đăng ký</h1>
            <p class="auth-subtitle">Tạo tài khoản Vinamilk và tham gia chương trình khách hàng thân thiết</p>

            <?php if (isset($error) && !empty($error)): ?>
                <div class="auth-error">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?controller=auth&action=register" class="auth-form">
                <div class="form-group">
                    <label for="phone" class="form-label">Số điện thoại <span class="required">*</span></label>
                    <input type="text"
                        id="phone"
                        name="phone"
                        class="form-input"
                        placeholder="Nhập số điện thoại (10 số)"
                        value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="full_name" class="form-label">Họ và tên</label>
                    <input type="text"
                        id="full_name"
                        name="full_name"
                        class="form-input"
                        placeholder="Nhập họ và tên"
                        value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        placeholder="Nhập email"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu <span class="required">*</span></label>
                    <input type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Nhập mật khẩu (ít nhất 6 ký tự)"
                        required>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span class="required">*</span></label>
                    <input type="password"
                        id="confirm_password"
                        name="confirm_password"
                        class="form-input"
                        placeholder="Nhập lại mật khẩu"
                        required>
                </div>

                <button type="submit" class="btn-auth-submit">Đăng ký</button>
            </form>

            <div class="auth-footer">
                <p>Bạn đã có tài khoản? <a href="index.php?controller=auth&action=showLogin" class="auth-link">Đăng nhập</a></p>
            </div>
        </div>
    </div>
</div>