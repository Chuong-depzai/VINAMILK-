<?php

require_once __DIR__ . '/../db.php';

class PasswordReset
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // Tạo mã 6 số
    public function generateCode(): string
    {
        return str_pad(rand(0, 999999), 6, "0", STR_PAD_LEFT);
    }

    // Tạo mã + lưu 15 phút
    public function createResetCode(string $email)
    {
        // xóa mã cũ
        $this->deleteOldCodes($email);

        $code = $this->generateCode();

        // hết hạn sau 15 phút
        $expiresAt = date("Y-m-d H:i:s", time() + (15 * 60));

        $sql = "INSERT INTO password_resets (email, code, expires_at) 
                VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        if ($stmt->execute([$email, $code, $expiresAt])) {
            return $code;
        }

        return false;
    }

    // kiểm tra mã hợp lệ
    public function verifyCode(string $email, string $code)
    {
        $sql = "SELECT * 
                FROM password_resets 
                WHERE email = ? 
                  AND code = ? 
                  AND is_used = 0
                  AND expires_at > NOW()
                ORDER BY created_at DESC
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email, $code]);

        return $stmt->fetch();
    }

    // đánh dấu dùng rồi
    public function markAsUsed(string $email, string $code)
    {
        $sql = "UPDATE password_resets 
                SET is_used = 1 
                WHERE email = ? AND code = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$email, $code]);
    }

    // xóa mã cũ của email
    private function deleteOldCodes(string $email)
    {
        $sql = "DELETE FROM password_resets WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
    }

    // dọn mã hết hạn
    public function cleanExpiredCodes()
    {
        $sql = "DELETE FROM password_resets WHERE expires_at < NOW()";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }
}
