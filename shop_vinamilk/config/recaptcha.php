<?php

/**
 * reCAPTCHA Configuration
 * 
 * Lấy keys từ: https://www.google.com/recaptcha/admin
 * Chọn: Challenge (v2) > "I'm not a robot" Checkbox
 */

return [
    // ✅ Bật/Tắt reCAPTCHA (dev mode có thể tắt)
    'enabled' => true,

    // 🔑 Keys từ Google reCAPTCHA Admin Console
    'site_key' => '6LeLSywsAAAAALpDlW-zUAMD9mSXn3ys6rVfxteI
',      // Dùng ở HTML/JavaScript
    'secret_key' => 'YOUR_SECRET_KEY_HERE',  // Dùng ở Server (PHP)

    // URL xác thực (v2)
    'verify_url' => '6LeLSywsAAAAAKnKaxiCEPzZBiryWPXd5W8GoGrO
',

    // Ngưỡng điểm (v3 - không cần cho v2)
    'min_score' => 0.5,

    // Timeout request (giây)
    'timeout' => 10,

    // Logging
    'log_path' => __DIR__ . '/../logs/recaptcha.log',
];
