<?php
require_once __DIR__ . '/../db.php';

class User
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function register($phone, $password, $full_name = '', $email = '')
    {
        if ($this->findByPhone($phone)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (phone, password, full_name, email, role) VALUES (?, ?, ?, ?, 'user')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$phone, $hashedPassword, $full_name, $email]);
    }

    public function login($phone, $password)
    {
        $user = $this->findByPhone($phone);

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function findByPhone($phone)
    {
        $sql = "SELECT * FROM users WHERE phone = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$phone]);
        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Cập nhật thông tin profile
     */
    public function updateProfile($id, $data)
    {
        $sql = "UPDATE users SET 
                full_name = ?, 
                email = ?, 
                address = ?, 
                date_of_birth = ?, 
                gender = ?,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['full_name'],
            $data['email'],
            $data['address'],
            $data['date_of_birth'],
            $data['gender'],
            $id
        ]);
    }

    /**
     * Cập nhật avatar
     */
    public function updateAvatar($id, $avatar)
    {
        $sql = "UPDATE users SET avatar = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$avatar, $id]);
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$hashedPassword, $id]);
    }

    /**
     * ADMIN: Lấy danh sách tất cả users
     */
    public function getAllUsers($page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT id, phone, full_name, email, role, avatar, created_at, updated_at 
                FROM users 
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$perPage, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * ADMIN: Đếm tổng số users
     */
    public function countUsers()
    {
        $sql = "SELECT COUNT(*) FROM users";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn();
    }

    /**
     * ADMIN: Cập nhật role user
     */
    public function updateRole($userId, $role)
    {
        $sql = "UPDATE users SET role = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$role, $userId]);
    }

    /**
     * ADMIN: Xóa user
     */
    public function deleteUser($userId)
    {
        // Không cho phép xóa admin
        $user = $this->findById($userId);
        if ($user['role'] === 'admin') {
            return false;
        }

        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId]);
    }

    /**
     * ADMIN: Tìm kiếm users
     */
    public function searchUsers($keyword)
    {
        $sql = "SELECT id, phone, full_name, email, role, avatar, created_at 
                FROM users 
                WHERE phone LIKE ? OR full_name LIKE ? OR email LIKE ?
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%{$keyword}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
}
