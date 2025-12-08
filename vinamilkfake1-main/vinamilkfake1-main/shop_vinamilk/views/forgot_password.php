<div class="page-container">
    <div class="auth-container">
        <div class="auth-box">
            <h1 class="auth-title">Quên mật khẩu</h1>
            <p class="auth-subtitle">Nhập email đã đăng ký để nhận mã xác nhận</p>

            <?php if (isset($error) && !empty($error)): ?>
                <div class="auth-error">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <?php if (isset($success) && !empty($success)): ?>
                <div class="auth-success">
                    <p><?php echo htmlspecialchars($success); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?controller=auth&action=sendResetCode" class="auth-form">
                <div class="form-group">
                    <label for="email" class="form-label">Email <span class="required">*</span></label>
                    <input type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        placeholder="Nhập email đã đăng ký"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                        required>
                    <p class="form-help-text">Mã xác nhận sẽ được gửi đến email này</p>
                </div>

                <button type="submit" class="btn-auth-submit">Gửi mã xác nhận</button>
            </form>

            <div class="auth-footer">
                <p><a href="index.php?controller=auth&action=showLogin" class="auth-link">← Quay lại đăng nhập</a></p>
            </div>
        </div>
    </div>
</div>