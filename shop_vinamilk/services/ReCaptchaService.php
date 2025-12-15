<?php

/**
 * ReCaptchaService - xác thực reCAPTCHA v2 Checkbox
 * 
 * Type: Challenge (v2) > "I'm not a robot" Checkbox
 */

class ReCaptchaService
{
    private $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/recaptcha.php';
    }

    /**
     * Xác thực reCAPTCHA token từ form submit
     * 
     * @param string $token Token từ input hidden (g-recaptcha-response)
     * @param string $action Action name (login, register, etc.) - không dùng cho v2
     * @return array ['success' => bool, 'score' => float]
     */
    public function verify($token, $action = null)
    {
        // Nếu tắt reCAPTCHA (dev mode)
        if (!$this->config['enabled']) {
            return [
                'success' => true,
                'score' => 1.0
            ];
        }

        // Validate input
        if (empty($token)) {
            return [
                'success' => false,
                'score' => 0.0
            ];
        }

        try {
            // Gửi request đến Google
            $response = $this->sendVerifyRequest($token);

            if (!$response) {
                error_log("reCAPTCHA: Request failed");
                return [
                    'success' => false,
                    'score' => 0.0
                ];
            }

            // Parse response từ Google
            $result = json_decode($response, true);

            if (!isset($result['success'])) {
                error_log("reCAPTCHA: Invalid response - " . $response);
                return [
                    'success' => false,
                    'score' => 0.0
                ];
            }

            $success = $result['success'] === true;
            $score = $success ? 1.0 : 0.0; // v2 chỉ có success/fail, không có score

            // Log result
            $this->logVerification($result, $success);

            return [
                'success' => $success,
                'score' => $score
            ];
        } catch (Exception $e) {
            error_log("reCAPTCHA Error: " . $e->getMessage());
            return [
                'success' => false,
                'score' => 0.0
            ];
        }
    }

    /**
     * Gửi POST request đến Google reCAPTCHA API
     */
    private function sendVerifyRequest($token)
    {
        $data = http_build_query([
            'secret' => $this->config['secret_key'],
            'response' => $token,
            'remoteip' => $this->getClientIp()
        ]);

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data,
                'timeout' => $this->config['timeout']
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents(
            $this->config['verify_url'],
            false,
            $context
        );

        return $response;
    }

    /**
     * Lấy IP client
     */
    private function getClientIp()
    {
        $ipKeys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER)) {
                $ips = explode(',', $_SERVER[$key]);
                $ip = trim($ips[0]);

                if (filter_var(
                    $ip,
                    FILTER_VALIDATE_IP,
                    FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                )) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Log kết quả xác thực
     */
    private function logVerification($result, $success)
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'success' => $success,
            'error_codes' => $result['error-codes'] ?? [],
            'challenge_ts' => $result['challenge_ts'] ?? null,
            'hostname' => $result['hostname'] ?? null,
            'ip' => $this->getClientIp()
        ];

        error_log("reCAPTCHA v2: " . json_encode($logData));
    }

    /**
     * Render Google reCAPTCHA script
     */
    public function renderScript()
    {
        if (!$this->config['enabled']) {
            return '';
        }

        $siteKey = htmlspecialchars($this->config['site_key']);

        return <<<HTML
<!-- Google reCAPTCHA v2 Script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
HTML;
    }

    /**
     * Render reCAPTCHA checkbox HTML
     */
    public function renderCheckbox()
    {
        if (!$this->config['enabled']) {
            return '';
        }

        $siteKey = htmlspecialchars($this->config['site_key']);

        return <<<HTML
<div class="g-recaptcha" data-sitekey="{$siteKey}"></div>
HTML;
    }

    /**
     * Lấy Site Key
     */
    public function getSiteKey()
    {
        return $this->config['site_key'];
    }
}
