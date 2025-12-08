<?php
require_once __DIR__ . '/../db.php';

class Review
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // Thêm bình luận mới với validation
    public function create($productId, $userId, $rating, $comment)
    {
        // Validate input
        if ($productId <= 0 || !$userId || $rating < 1 || $rating > 5) {
            return false;
        }

        $sql = "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$productId, $userId, $rating, $comment]);
    }

    // Lấy danh sách bình luận theo sản phẩm
    public function getByProduct($productId)
    {
        $sql = "SELECT r.*, u.full_name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = ? 
            ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    // Tính điểm trung bình sao
    public function getAverageRating($productId)
    {
        $sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM reviews WHERE product_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }
}
