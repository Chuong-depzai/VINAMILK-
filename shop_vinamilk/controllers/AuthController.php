<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/PasswordReset.php';
require_once __DIR__ . '/../services/EmailService.php';
require_once __DIR__ . '/../services/ReCaptchaService.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class AuthController
{
    private $userModel;
    private $passwordResetModel;
    private $emailService;
    private $recaptchaService;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->userModel = new User();
        $this->passwordResetModel = new PasswordReset();
        $this->emailService = new EmailService();
        $this->recaptchaService = new ReCaptchaService();
    }

    // ============================================
    // LOGIN METHODS
    // ============================================

    public function showLogin()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLogin();
            return;
        }

        // ✅ KIỂM TRA RECAPTCHA
        $recaptchaToken = $_POST['recaptcha_token'] ?? '';
        $recaptchaResult = $this->recaptchaService->verify($recaptchaToken, 'login');

        if (!$recaptchaResult['success']) {
            $error = 'Xác thực bảo mật thất bại. Vui lòng thử lại.';
            error_log("Failed login attempt - reCAPTCHA score: " . $recaptchaResult['score']);

            require_once __DIR__ . '/../views/header.php';
            require_once __DIR__ . '/../views/login.php';
            require_once __DIR__ . '/../views/footer.php';
            return;
        }

        // ✅ RATE LIMITING - Giới hạn 5 lần/5 phút
        if (!$this->checkRateLimit('login', 5, 300)) {
            $error = 'Bạn đã thử đăng nhập quá nhiều lần. Vui lòng thử lại sau 5 phút.';
            require_once __DIR__ . '/../views/header.php';
            require_once __DIR__ . '/../views/login.php';
            require_once __DIR__ . '/../views/footer.php';
            return;
        }

        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $error = '';

        if (empty($phone) || empty($password)) {
            $error = 'Vui lòng nhập đầy đủ số điện thoại và mật khẩu';
        } else {
            // ✅ KIỂM TRA ACCOUNT LOCK
            if ($this->isAccountLocked($phone)) {
                $error = 'Tài khoản đã bị khóa do đăng nhập sai quá nhiều lần. Vui lòng đặt lại mật khẩu.';
                require_once __DIR__ . '/../views/header.php';
                require_once __DIR__ . '/../views/login.php';
                require_once __DIR__ . '/../views/footer.php';
                return;
            }

            $user = $this->userModel->login($phone, $password);

            if ($user) {
                // ✅ RESET failed login attempts
                $this->resetFailedAttempts($phone);

                // LƯU ĐẦY ĐỦ THÔNG TIN VÀO SESSION
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_phone'] = $user['phone'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_avatar'] = $user['avatar'];

                // Chuyển hướng về trang admin nếu là admin
                if ($user['role'] === 'admin') {
                    header("Location: index.php?controller=admin&action=dashboard");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                // ✅ INCREMENT failed login attempts
                $this->incrementFailedAttempts($phone);
                $remainingAttempts = $this->getRemainingAttempts($phone);

                if ($remainingAttempts > 0) {
                    $error = "Số điện thoại hoặc mật khẩu không đúng. Còn {$remainingAttempts} lần thử.";
                } else {
                    $error = 'Tài khoản đã bị khóa do đăng nhập sai quá nhiều lần.';
                }
            }
        }

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // ============================================
    // REGISTER METHODS
    // ============================================

    public function showRegister()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/register.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showRegister();
            return;
        }

        // ✅ KIỂM TRA RECAPTCHA
        $recaptchaToken = $_POST['recaptcha_token'] ?? '';
        $recaptchaResult = $this->recaptchaService->verify($recaptchaToken, 'register');

        if (!$recaptchaResult['success']) {
            $error = 'Xác thực bảo mật thất bại. Vui lòng thử lại.';
            require_once __DIR__ . '/../views/header.php';
            require_once __DIR__ . '/../views/register.php';
            require_once __DIR__ . '/../views/footer.php';
            return;
        }

        // ✅ RATE LIMITING - 3 lần đăng ký/giờ
        if (!$this->checkRateLimit('register', 3, 3600)) {
            $error = 'Bạn đã đăng ký quá nhiều tài khoản. Vui lòng thử lại sau 1 giờ.';
            require_once __DIR__ . '/../views/header.php';
            require_once __DIR__ . '/../views/register.php';
            require_once __DIR__ . '/../views/footer.php';
            return;
        }

        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $full_name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $error = '';

        // ✅ VALIDATE PASSWORD STRENGTH
        if (!$this->isPasswordStrong($password)) {
            $error = 'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số';
            require_once __DIR__ . '/../views/header.php';
            require_once __DIR__ . '/../views/register.php';
            require_once __DIR__ . '/../views/footer.php';
            return;
        }

        if (empty($phone) || empty($password)) {
            $error = 'Vui lòng nhập đầy đủ số điện thoại và mật khẩu';
        } elseif ($password !== $confirm_password) {
            $error = 'Mật khẩu xác nhận không khớp';
        } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
            $error = 'Số điện thoại không hợp lệ (phải có 10 số)';
        } else {
            $result = $this->userModel->register($phone, $password, $full_name, $email);

            if ($result) {
                $user = $this->userModel->findByPhone($phone);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_phone'] = $user['phone'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_avatar'] = $user['avatar'];

                header("Location: index.php");
                exit;
            } else {
                $error = 'Số điện thoại đã được đăng ký';
            }
        }

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/register.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // ============================================
    // LOGOUT METHOD
    // ============================================

    public function logout()
    {
        session_unset();
        session_destroy();
        session_start();

        $_SESSION['success_message'] = 'Đã đăng xuất thành công';
        header("Location: index.php");
        exit;
    }

    // ============================================
    // FORGOT PASSWORD METHODS
    // ============================================

    public function showForgotPassword()
    {
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/forgot_password.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function sendResetCode()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showForgotPassword();
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $error = '';

        if (empty($email)) {
            $error = 'Vui lòng nhập email';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Email không hợp lệ';
        } else {
            $user = $this->userModel->findByEmail($email);

            if (!$user) {
                $error = 'Email không tồn tại trong hệ thống';
            } else {
                $code = $this->passwordResetModel->createResetCode($email);

                if ($code) {
                    $emailSent = $this->emailService->sendResetCode(
                        $email,
                        $code,
                        $user['full_name']
                    );

                    if ($emailSent) {
                        $_SESSION['reset_email'] = $email;
                        header("Location: index.php?controller=auth&action=showVerifyCode");
                        exit;
                    } else {
                        $error = 'Lỗi khi gửi email. Vui lòng kiểm tra cấu hình SMTP';
                    }
                } else {
                    $error = 'Lỗi khi tạo mã xác nhận. Vui lòng thử lại';
                }
            }
        }

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/forgot_password.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function showVerifyCode()
    {
        if (!isset($_SESSION['reset_email'])) {
            header("Location: index.php?controller=auth&action=showForgotPassword");
            exit;
        }

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/verify_code.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showVerifyCode();
            return;
        }

        $email = $_SESSION['reset_email'] ?? '';
        $code = trim($_POST['code'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $error = '';

        if (empty($code)) {
            $error = 'Vui lòng nhập mã xác nhận';
        } elseif (empty($newPassword) || empty($confirmPassword)) {
            $error = 'Vui lòng nhập mật khẩu mới';
        } elseif (strlen($newPassword) < 6) {
            $error = 'Mật khẩu phải có ít nhất 6 ký tự';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Mật khẩu xác nhận không khớp';
        } else {
            $resetData = $this->passwordResetModel->verifyCode($email, $code);

            if (!$resetData) {
                $error = 'Mã xác nhận không đúng hoặc đã hết hạn';
            } else {
                $user = $this->userModel->findByEmail($email);

                if ($user) {
                    $updated = $this->userModel->changePassword($user['id'], $newPassword);

                    if ($updated) {
                        $this->passwordResetModel->markAsUsed($email, $code);
                        unset($_SESSION['reset_email']);

                        $_SESSION['reset_success'] = 'Đổi mật khẩu thành công! Vui lòng đăng nhập';
                        header("Location: index.php?controller=auth&action=showLogin");
                        exit;
                    } else {
                        $error = 'Lỗi khi cập nhật mật khẩu';
                    }
                } else {
                    $error = 'Không tìm thấy tài khoản';
                }
            }
        }

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/verify_code.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // ============================================
    // STATIC AUTH HELPER METHODS
    // ============================================

    public static function isLoggedIn()
    {
        return AuthMiddleware::isLoggedIn();
    }

    public static function getCurrentUser()
    {
        return AuthMiddleware::getCurrentUser();
    }

    public static function isAdmin()
    {
        return AuthMiddleware::isAdmin();
    }

    // ============================================
    // PRIVATE HELPER METHODS
    // ============================================

    /**
     * Kiểm tra rate limiting
     */
    private function checkRateLimit($action, $maxAttempts, $timeWindow)
    {
        $key = 'rate_limit_' . $action . '_' . $this->getClientIp();

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
        }

        $data = $_SESSION[$key];

        // Reset nếu hết thời gian
        if (time() - $data['first_attempt'] > $timeWindow) {
            $_SESSION[$key] = ['count' => 1, 'first_attempt' => time()];
            return true;
        }

        // Kiểm tra số lần
        if ($data['count'] >= $maxAttempts) {
            return false;
        }

        $_SESSION[$key]['count']++;
        return true;
    }

    /**
     * Kiểm tra tài khoản bị khóa
     */
    private function isAccountLocked($phone)
    {
        $key = 'failed_login_' . $phone;
        return isset($_SESSION[$key]) && $_SESSION[$key]['count'] >= 5;
    }

    /**
     * Tăng số lần đăng nhập sai
     */
    private function incrementFailedAttempts($phone)
    {
        $key = 'failed_login_' . $phone;

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
        }

        $_SESSION[$key]['count']++;
    }

    /**
     * Reset lần đăng nhập sai
     */
    private function resetFailedAttempts($phone)
    {
        $key = 'failed_login_' . $phone;
        unset($_SESSION[$key]);
    }

    /**
     * Lấy số lần thử còn lại
     */
    private function getRemainingAttempts($phone)
    {
        $key = 'failed_login_' . $phone;
        $count = isset($_SESSION[$key]) ? $_SESSION[$key]['count'] : 0;
        return max(0, 5 - $count);
    }

    /**
     * Kiểm tra mật khẩu mạnh
     * Yêu cầu: ít nhất 8 ký tự, có chữ hoa, chữ thường, số
     */
    private function isPasswordStrong($password)
    {
        return strlen($password) >= 8 &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[0-9]/', $password);
    }

    /**
     * Lấy IP của client
     */
    private function getClientIp()
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
