<div class="page-container">
    <div class="auth-container">
        <div class="auth-box">
            <h1 class="auth-title">Xác nhận mã & Đổi mật khẩu</h1>
            <p class="auth-subtitle">
                Mã xác nhận đã được gửi đến: <strong><?php echo htmlspecialchars($_SESSION['reset_email'] ?? ''); ?></strong>
            </p>

            <?php if (isset($_SESSION['reset_code'])): ?>
                <div class="auth-success">
                    <p>📧 Mã test: <strong><?php echo $_SESSION['reset_code']; ?></strong> (Có hiệu lực 15 phút)</p>
                </div>
            <?php endif; ?>

            <?php if (isset($error) && !empty($error)): ?>
                <div class="auth-error">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?controller=auth&action=resetPassword" class="auth-form">
                <div class="form-group">
                    <label for="code" class="form-label">Mã xác nhận (6 số) <span class="required">*</span></label>
                    <input type="text"
                        id="code"
                        name="code"
                        class="form-input"
                        placeholder="Nhập mã 6 số"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        value="<?php echo isset($_POST['code']) ? htmlspecialchars($_POST['code']) : ''; ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label">Mật khẩu mới <span class="required">*</span></label>
                    <input type="password"
                        id="new_password"
                        name="new_password"
                        class="form-input"
                        placeholder="Nhập mật khẩu mới (ít nhất 6 ký tự)"
                        required>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span class="required">*</span></label>
                    <input type="password"
                        id="confirm_password"
                        name="confirm_password"
                        class="form-input"
                        placeholder="Nhập lại mật khẩu mới"
                        required>
                </div>

                <button type="submit" class="btn-auth-submit">Đổi mật khẩu</button>
            </form>

            <div class="auth-footer">
                <p><a href="index.php?controller=auth&action=showForgotPassword" class="auth-link">← Gửi lại mã</a></p>
            </div>
        </div>
    </div>
</div>