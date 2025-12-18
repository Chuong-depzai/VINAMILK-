<?php

/**
 * ReCaptcha Service - Xử lý reCAPTCHA v2
 */

class ReCaptchaService
{
    private $siteKey;
    private $secretKey;
    private $verifyUrl;
    private $enabled;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/recaptcha.php';
        $this->siteKey = $config['site_key'];
        $this->secretKey = $config['secret_key'];
        $this->verifyUrl = $config['verify_url'];
        $this->enabled = $config['enabled'];
    }

    /**
     * Render script tải reCAPTCHA
     */
    public function renderScript()
    {
        if (!$this->enabled) {
            return '';
        }

        return '
            <script src="https://www.google.com/recaptcha/api.js"></script>
        ';
    }

    /**
     * Render reCAPTCHA Checkbox Widget
     */
    public function renderCheckbox()
    {
        if (!$this->enabled) {
            return '';
        }

        return '
            <div class="g-recaptcha" data-sitekey="' . htmlspecialchars($this->siteKey) . '"></div>
        ';
    }

    /**
     * Verify reCAPTCHA Token từ Server
     * @return array ['success' => bool, 'score' => float, 'message' => string]
     */
    public function verify($token, $action = 'login')
    {
        // Nếu disabled, tự động pass
        if (!$this->enabled) {
            return [
                'success' => true,
                'score' => 1.0,
                'message' => 'reCAPTCHA disabled'
            ];
        }

        // Nếu không có token (reCAPTCHA v2 Checkbox)
        if (empty($token)) {
            return [
                'success' => false,
                'score' => 0,
                'message' => 'reCAPTCHA response missing'
            ];
        }

        // Gửi request đến Google để verify
        $response = $this->sendVerifyRequest($token);

        // Log kết quả
        $this->log($action, $token, $response);

        return $response;
    }

    /**
     * Gửi request verify đến Google reCAPTCHA API
     */
    private function sendVerifyRequest($token)
    {
        try {
            // Chuẩn bị dữ liệu
            $data = [
                'secret' => $this->secretKey,
                'response' => $token
            ];

            // Gửi POST request
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/x-www-form-urlencoded',
                    'content' => http_build_query($data),
                    'timeout' => 10
                ]
            ]);

            $response = @file_get_contents($this->verifyUrl, false, $context);

            if ($response === false) {
                return [
                    'success' => false,
                    'score' => 0,
                    'message' => 'Network error'
                ];
            }

            $result = json_decode($response, true);

            // Kiểm tra kết quả từ Google
            $success = isset($result['success']) && $result['success'] === true;

            return [
                'success' => $success,
                'score' => $result['score'] ?? 0,
                'message' => $success ? 'Verified' : 'Verification failed',
                'action' => $result['action'] ?? null,
                'challenge_ts' => $result['challenge_ts'] ?? null,
                'hostname' => $result['hostname'] ?? null,
                'error_codes' => $result['error-codes'] ?? []
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'score' => 0,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Log request để debug
     */
    private function log($action, $token, $response)
    {
        $logFile = __DIR__ . '/../logs/recaptcha.log';

        // Tạo folder logs nếu chưa có
        if (!is_dir(dirname($logFile))) {
            @mkdir(dirname($logFile), 0777, true);
        }

        $logMessage = "[" . date('Y-m-d H:i:s') . "] " .
            "Action: $action | " .
            "Success: " . ($response['success'] ? 'YES' : 'NO') . " | " .
            "Score: " . $response['score'] . " | " .
            "Message: " . $response['message'] . " | " .
            "IP: " . $this->getClientIp() . "\n";

        @file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    /**
     * Lấy IP client
     */
    private function getClientIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
    }

    /**
     * Lấy Site Key (để truyền vào HTML)
     */
    public function getSiteKey()
    {
        return $this->siteKey;
    }
}
