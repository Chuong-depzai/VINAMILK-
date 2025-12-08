<?php
// services/EmailService.php
require_once __DIR__ . '/../config/email.php';

// Import PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../libraries/PHPMailer-7.0.0/src/Exception.php';
require_once __DIR__ . '/../libraries/PHPMailer-7.0.0/src/PHPMailer.php';
require_once __DIR__ . '/../libraries/PHPMailer-7.0.0/src/SMTP.php';

class EmailService
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->configure();
    }

    // Cấu hình SMTP
    private function configure()
    {
        try {
            // Cấu hình server
            $this->mailer->isSMTP();
            $this->mailer->Host = SMTP_HOST;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = SMTP_USERNAME;
            $this->mailer->Password = SMTP_PASSWORD;
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = SMTP_PORT;

            // Cấu hình người gửi
            $this->mailer->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            $this->mailer->CharSet = 'UTF-8';
        } catch (Exception $e) {
            error_log("Email configuration error: " . $e->getMessage());
        }
    }

    // Gửi mã reset password
    public function sendResetCode($toEmail, $code, $userName = '')
    {
        try {
            // Người nhận
            $this->mailer->addAddress($toEmail);

            // Nội dung email
            $this->mailer->isHTML(true);
            $this->mailer->Subject = RESET_EMAIL_SUBJECT;
            $this->mailer->Body = $this->getResetEmailTemplate($code, $userName);
            $this->mailer->AltBody = "Mã xác nhận của bạn là: $code (Có hiệu lực trong 15 phút)";

            // Gửi email
            $result = $this->mailer->send();
            $this->mailer->clearAddresses(); // Xóa địa chỉ sau khi gửi

            return $result;
        } catch (Exception $e) {
            error_log("Email send error: " . $this->mailer->ErrorInfo);
            return false;
        }
    }

    // Template HTML cho email reset password
    private function getResetEmailTemplate($code, $userName)
    {
        $greeting = $userName ? "Xin chào $userName," : "Xin chào,";

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #0033a0; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 8px; margin: 20px 0; }
                .code-box { background: #fff; border: 2px dashed #0033a0; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px; }
                .code { font-size: 32px; font-weight: bold; color: #0033a0; letter-spacing: 5px; }
                .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
                .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Vinamilk</h1>
                </div>
                
                <div class='content'>
                    <p>$greeting</p>
                    <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
                    
                    <div class='code-box'>
                        <p style='margin: 0; font-size: 14px; color: #666;'>Mã xác nhận của bạn là:</p>
                        <div class='code'>$code</div>
                        <p style='margin: 10px 0 0 0; font-size: 13px; color: #999;'>Có hiệu lực trong 15 phút</p>
                    </div>
                    
                    <div class='warning'>
                        <strong>⚠️ Lưu ý bảo mật:</strong>
                        <ul style='margin: 10px 0 0 0; padding-left: 20px;'>
                            <li>Không chia sẻ mã này với bất kỳ ai</li>
                            <li>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này</li>
                            <li>Mã sẽ hết hạn sau 15 phút</li>
                        </ul>
                    </div>
                    
                    <p style='margin-top: 20px;'>Trân trọng,<br><strong>Đội ngũ Vinamilk</strong></p>
                </div>
                
                <div class='footer'>
                    <p>© 2025 Vinamilk. Tất cả quyền được bảo lưu.</p>
                    <p>Email này được gửi tự động, vui lòng không trả lời.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
