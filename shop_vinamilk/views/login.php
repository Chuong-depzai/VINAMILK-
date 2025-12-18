<?php
// Load reCAPTCHA service
require_once __DIR__ . '/../services/ReCaptchaService.php';
$recaptchaService = new ReCaptchaService();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <!-- ✅ THÊM GOOGLE RECAPTCHA SCRIPT -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <br> <br> <br><br> <br>

    <div class="page-container">
        <div class="auth-container">
            <div class="auth-box">
                <h1 class="auth-title">Đăng nhập</h1>
                <p class="auth-subtitle">Đăng nhập vào tài khoản thành viên của bạn</p>

                <!-- ✅ Thông báo reset password thành công -->
                <?php if (isset($_SESSION['reset_success'])): ?>
                    <div class="auth-success">
                        <p><?php echo htmlspecialchars($_SESSION['reset_success']); ?></p>
                    </div>
                    <?php unset($_SESSION['reset_success']); ?>
                <?php endif; ?>

                <!-- ✅ Thông báo lỗi -->
                <?php if (isset($error) && !empty($error)): ?>
                    <div class="auth-error">
                        <p><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>

                <!-- ✅ Form đăng nhập -->
                <form method="POST" action="index.php?controller=auth&action=login" class="auth-form" id="loginForm">

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

                    <div class="form-group-checkbox-wrapper" style="display: flex; justify-content: space-between; margin: 15px 0;">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" class="checkbox-input">
                            <span>Ghi nhớ đăng nhập</span>
                        </label>
                        <a href="index.php?controller=auth&action=showForgotPassword" class="forgot-password-link">Quên mật khẩu?</a>
                    </div>

                    <!-- ✅ reCAPTCHA v2 Checkbox (PHẢI CÓ DIV NÀY) -->
                    <div class="form-group" style="margin: 20px 0;">
                        <div class="g-recaptcha"
                            data-sitekey="<?php echo htmlspecialchars($recaptchaService->getSiteKey()); ?>"
                            data-callback="onRecaptchaSuccess"
                            data-expired-callback="onRecaptchaExpired">
                        </div>
                        <input type="hidden" id="recaptchaToken" name="g-recaptcha-response" value="">
                    </div>

                    <!-- ✅ Google Privacy Notice -->
                    <div style="font-size: 11px; color: #999; margin: 15px 0; line-height: 1.4;">
                        Trang này được bảo vệ bởi reCAPTCHA và tuân thủ
                        <a href="https://policies.google.com/privacy" target="_blank" style="color: #0033a0; text-decoration: none;">Chính sách Bảo mật</a> và
                        <a href="https://policies.google.com/terms" target="_blank" style="color: #0033a0; text-decoration: none;">Điều khoản Dịch vụ</a> của Google.
                    </div>

                    <!-- ✅ Submit Button -->
                    <button type="submit" class="btn-auth-submit" id="submitBtn">
                        <span id="btnText">Đăng nhập</span>
                    </button>
                </form>

                <!-- ✅ Register Link -->
                <div class="auth-footer">
                    <p>Bạn chưa có tài khoản? <a href="index.php?controller=auth&action=showRegister" class="auth-link">Đăng ký</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ JavaScript xử lý form & reCAPTCHA -->
    <script>
        // Callback khi reCAPTCHA thành công
        function onRecaptchaSuccess(token) {
            document.getElementById('recaptchaToken').value = token;
            console.log('reCAPTCHA success:', token);
        }

        // Callback khi reCAPTCHA hết hạn
        function onRecaptchaExpired() {
            document.getElementById('recaptchaToken').value = '';
            console.log('reCAPTCHA expired');
        }

        // Xử lý submit form
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');

        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();

            // ✅ Kiểm tra reCAPTCHA đã được check chưa
            const recaptchaToken = document.getElementById('recaptchaToken').value;

            if (!recaptchaToken) {
                alert('⚠️ Vui lòng xác thực reCAPTCHA');
                return;
            }

            // Disable button & show loading
            submitBtn.disabled = true;
            btnText.textContent = '⏳ Đang xác thực...';

            // Submit form
            setTimeout(() => {
                loginForm.submit();
            }, 500);
        });
    </script>

    <style>
        #submitBtn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>

</body>

</html>