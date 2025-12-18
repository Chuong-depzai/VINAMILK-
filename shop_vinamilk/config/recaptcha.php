<?php


return [

    'enabled' => true,

    // 🔑 KEYS TỪ GOOGLE RECAPTCHA ADMIN CONSOLE
    // ⚠️ THAY BẰNG KEYS THỰC CỦA BẠN
    'site_key' => getenv('RECAPTCHA_SITE_KEY') ?: '6LfWnC4sAAAAAC5mbPZ7A2YJwzgY49zkBQ4um0Zk',  // Test Key
    'secret_key' => getenv('RECAPTCHA_SECRET_KEY') ?: '6LfWnC4sAAAAAJz-M2fZ22vSm2ElGP90zo-h7ldc', // Test Key

    // ✅ URL VERIFY RECAPTCHA (v2)
    'verify_url' => 'https://www.google.com/recaptcha/api/siteverify',

    // ⚙️ CẤU HÌNH
    'timeout' => 10, // Timeout request (giây)
    'log_path' => __DIR__ . '/../logs/recaptcha.log',
];
