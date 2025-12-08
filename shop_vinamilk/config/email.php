<?php
// config/email.php - Cấu hình gửi email

// Cấu hình SMTP Gmail
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'chuonghello2@gmail.com'); // ⚠️ Email của bạn
define('SMTP_PASSWORD', 'klzz mugu gnhe mxmj'); // ⚠️ THAY BẰNG APP PASSWORD
define('SMTP_FROM_EMAIL', 'chuonghello2@gmail.com');
define('SMTP_FROM_NAME', 'Vinamilk - Hệ thống quản lý');

// Cấu hình email reset password
define('RESET_EMAIL_SUBJECT', 'Mã xác nhận đặt lại mật khẩu');
define('RESET_CODE_EXPIRY_MINUTES', 15);
