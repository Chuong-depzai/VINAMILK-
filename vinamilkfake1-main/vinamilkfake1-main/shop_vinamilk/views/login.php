<div class="page-container">
    <div class="auth-container">
        <div class="auth-box">
            <h1 class="auth-title">Đăng nhập</h1>
            <p class="auth-subtitle">Đăng nhập vào tài khoản thành viên của bạn</p>

            <?php if (isset($_SESSION['reset_success'])): ?>
                <div class="auth-success">
                    <p><?php echo htmlspecialchars($_SESSION['reset_success']); ?></p>
                </div>
                <?php unset($_SESSION['reset_success']); ?>
            <?php endif; ?>

            <?php if (isset($error) && !empty($error)): ?>
                <div class="auth-error">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?controller=auth&action=login" class="auth-form">
                <div class="form-group">
                    <label for="phone" class="form-label">Số điện thoại <span class="required">*</span></label>
                    <input type="text"
                        id="phone"
                        name="phone"
                        class="form-input"
                        placeholder="Nhập số điện thoại"
                        value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu <span class="required">*</span></label>
                    <input type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Nhập mật khẩu"
                        required>
                </div>

                <div class="form-group-checkbox-wrapper">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" class="checkbox-input">
                        <span>Ghi nhớ đăng nhập</span>
                    </label>
                    <a href="index.php?controller=auth&action=showForgotPassword" class="forgot-password-link">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn-auth-submit">Đăng nhập</button>
            </form>

            <div class="auth-footer">
                <p>Bạn chưa có tài khoản? <a href="index.php?controller=auth&action=showRegister" class="auth-link">Đăng ký</a></p>
            </div>
        </div>
    </div>
</div>