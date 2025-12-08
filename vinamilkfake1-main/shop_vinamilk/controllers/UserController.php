<?php
// controllers/UserController.php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->userModel = new User();
    }

    /**
     * Hiển thị trang thông tin cá nhân
     */
    public function profile()
    {
        AuthMiddleware::requireLogin();

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['error_message'] = 'Không tìm thấy thông tin người dùng';
            header('Location: index.php');
            exit;
        }

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/user_profile.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function updateProfile()
    {
        AuthMiddleware::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=user&action=profile');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $dateOfBirth = trim($_POST['date_of_birth'] ?? '');
        $gender = trim($_POST['gender'] ?? '');

        // Validate
        if (empty($fullName)) {
            $_SESSION['error_message'] = 'Họ tên không được để trống';
            header('Location: index.php?controller=user&action=profile');
            exit;
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = 'Email không hợp lệ';
            header('Location: index.php?controller=user&action=profile');
            exit;
        }

        // Cập nhật thông tin
        $result = $this->userModel->updateProfile($userId, [
            'full_name' => $fullName,
            'email' => $email,
            'address' => $address,
            'date_of_birth' => $dateOfBirth ?: null,
            'gender' => $gender ?: null
        ]);

        if ($result) {
            // Cập nhật session
            $_SESSION['user_name'] = $fullName;
            $_SESSION['user_email'] = $email;
            $_SESSION['success_message'] = 'Cập nhật thông tin thành công';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật thông tin';
        }

        header('Location: index.php?controller=user&action=profile');
        exit;
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword()
    {
        AuthMiddleware::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=user&action=profile');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error_message'] = 'Vui lòng nhập đầy đủ thông tin';
            header('Location: index.php?controller=user&action=profile');
            exit;
        }

        if (strlen($newPassword) < 6) {
            $_SESSION['error_message'] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
            header('Location: index.php?controller=user&action=profile');
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error_message'] = 'Mật khẩu xác nhận không khớp';
            header('Location: index.php?controller=user&action=profile');
            exit;
        }

        // Kiểm tra mật khẩu hiện tại
        $user = $this->userModel->findById($userId);
        if (!password_verify($currentPassword, $user['password'])) {
            $_SESSION['error_message'] = 'Mật khẩu hiện tại không đúng';
            header('Location: index.php?controller=user&action=profile');
            exit;
        }

        // Đổi mật khẩu
        $result = $this->userModel->changePassword($userId, $newPassword);

        if ($result) {
            $_SESSION['success_message'] = 'Đổi mật khẩu thành công';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi đổi mật khẩu';
        }

        header('Location: index.php?controller=user&action=profile');
        exit;
    }

    /**
     * Upload avatar
     */
    public function uploadAvatar()
    {
        AuthMiddleware::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=user&action=profile');
            exit;
        }

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error_message'] = 'Vui lòng chọn ảnh hợp lệ';
            header('Location: index.php?controller=user&action=profile');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $file = $_FILES['avatar'];

        // Validate file
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error_message'] = 'Chỉ chấp nhận file JPG, PNG, GIF';
            header('Location: index.php?controller=user&action=profile');
            exit;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            $_SESSION['error_message'] = 'File không được vượt quá 5MB';
            header('Location: index.php?controller=user&action=profile');
            exit;
        }

        // Upload file
        $uploadDir = __DIR__ . '/../uploads/avatars/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = 'avatar_' . $userId . '_' . time() . '.' . $extension;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Xóa avatar cũ
            $user = $this->userModel->findById($userId);
            if (!empty($user['avatar']) && file_exists($uploadDir . $user['avatar'])) {
                unlink($uploadDir . $user['avatar']);
            }

            // Cập nhật database
            $result = $this->userModel->updateAvatar($userId, $fileName);

            if ($result) {
                $_SESSION['user_avatar'] = $fileName;
                $_SESSION['success_message'] = 'Cập nhật ảnh đại diện thành công';
            } else {
                $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật ảnh';
            }
        } else {
            $_SESSION['error_message'] = 'Lỗi khi upload file';
        }

        header('Location: index.php?controller=user&action=profile');
        exit;
    }

    /**
     * Xóa avatar
     */
    public function deleteAvatar()
    {
        AuthMiddleware::requireLogin();

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);

        if (!empty($user['avatar'])) {
            $uploadDir = __DIR__ . '/../uploads/avatars/';
            $filePath = $uploadDir . $user['avatar'];

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $this->userModel->updateAvatar($userId, null);
            $_SESSION['user_avatar'] = null;
            $_SESSION['success_message'] = 'Đã xóa ảnh đại diện';
        }

        header('Location: index.php?controller=user&action=profile');
        exit;
    }
}
