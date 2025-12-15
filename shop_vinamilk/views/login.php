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
</head>

<body>
    <br> <br> <br><br> <br>

    <!-- ✅ Render reCAPTCHA Script -->
    <?php echo $recaptchaService->renderScript(); ?>

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

                        <!-- ✅ Password Strength Indicator -->
                        <div id="passwordStrength" style="display: none; margin-top: 8px;">
                            <div style="height: 4px; background: #eee; border-radius: 2px; overflow: hidden; margin-bottom: 4px;">
                                <div id="strengthBar" style="height: 100%; width: 0%; transition: all 0.3s;"></div>
                            </div>
                            <small id="strengthText" style="font-size: 12px;"></small>
                        </div>
                    </div>

                    <div class="form-group-checkbox-wrapper" style="display: flex; justify-content: space-between; margin: 15px 0;">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" class="checkbox-input">
                            <span>Ghi nhớ đăng nhập</span>
                        </label>
                        <a href="index.php?controller=auth&action=showForgotPassword" class="forgot-password-link">Quên mật khẩu?</a>
                    </div>

                    <!-- ✅ reCAPTCHA v2 Checkbox -->
                    <div class="form-group" style="margin: 20px 0;">
                        <?php echo $recaptchaService->renderCheckbox(); ?>
                        <input type="hidden" name="recaptcha_token" id="recaptchaToken">
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
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const passwordInput = document.getElementById('password');

        // ✅ Xử lý submit form
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();

            // Kiểm tra reCAPTCHA đã được check chưa
            const recaptchaResponse = document.querySelector('[name="g-recaptcha-response"]');

            if (!recaptchaResponse || !recaptchaResponse.value) {
                alert('Vui lòng xác thực reCAPTCHA');
                return;
            }

            // Disable button & show loading
            submitBtn.disabled = true;
            btnText.textContent = '⏳ Đang xác thực...';

            // Set token (v2 Checkbox tự động gán vào g-recaptcha-response)
            document.getElementById('recaptchaToken').value = recaptchaResponse.value;

            // Submit form
            setTimeout(() => {
                loginForm.submit();
            }, 500);
        });

        // ✅ Password Strength Indicator
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        const passwordStrength = document.getElementById('passwordStrength');

        passwordInput.addEventListener('input', () => {
            const password = passwordInput.value;

            if (!password) {
                passwordStrength.style.display = 'none';
                return;
            }

            const strength = calculatePasswordStrength(password);
            passwordStrength.style.display = 'block';
            strengthBar.style.width = strength.percent + '%';
            strengthBar.style.background = strength.color;
            strengthText.textContent = strength.text;
            strengthText.style.color = strength.color;
        });

        // ✅ Tính toán độ mạnh mật khẩu
        function calculatePasswordStrength(password) {
            let score = 0;

            if (password.length >= 6) score++;
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

            const levels = [{
                    percent: 0,
                    color: '#ccc',
                    text: ''
                },
                {
                    percent: 20,
                    color: '#dc3545',
                    text: 'Rất yếu'
                },
                {
                    percent: 40,
                    color: '#fd7e14',
                    text: 'Yếu'
                },
                {
                    percent: 60,
                    color: '#ffc107',
                    text: 'Trung bình'
                },
                {
                    percent: 80,
                    color: '#28a745',
                    text: 'Mạnh'
                },
                {
                    percent: 100,
                    color: '#0033a0',
                    text: 'Rất mạnh'
                }
            ];

            return levels[Math.min(score, 5)];
        }
    </script>

    <style>
        #submitBtn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        #passwordStrength {
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

</body>

</html>