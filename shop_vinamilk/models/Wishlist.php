<?php
// models/Wishlist.php
require_once __DIR__ . '/../db.php';

class Wishlist
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // Thêm sản phẩm vào wishlist
    public function add($userId, $productId)
    {
        try {
            $sql = "INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$userId, $productId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Xóa sản phẩm khỏi wishlist
    public function remove($userId, $productId)
    {
        $sql = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $productId]);
    }

    // Kiểm tra sản phẩm có trong wishlist không
    public function isInWishlist($userId, $productId)
    {
        $sql = "SELECT COUNT(*) FROM wishlist WHERE user_id = ? AND product_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $productId]);
        return $stmt->fetchColumn() > 0;
    }

    // Lấy tất cả sản phẩm trong wishlist của user
    public function getByUserId($userId)
    {
        $sql = "SELECT p.*, w.created_at as added_at 
                FROM wishlist w
                JOIN products p ON w.product_id = p.id
                WHERE w.user_id = ?
                ORDER BY w.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Đếm số lượng sản phẩm trong wishlist
    public function getCount($userId)
    {
        $sql = "SELECT COUNT(*) FROM wishlist WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }

    // Xóa toàn bộ wishlist của user
    public function clear($userId)
    {
        $sql = "DELETE FROM wishlist WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId]);
    }

    // Chuyển tất cả sản phẩm trong wishlist vào giỏ hàng
    public function moveAllToCart($userId)
    {
        $items = $this->getByUserId($userId);

        if (!empty($items)) {
            require_once __DIR__ . '/Cart.php';
            $cart = new Cart();

            foreach ($items as $item) {
                $cart->addItem($item['id'], 1);
            }

            // Xóa wishlist sau khi chuyển
            $this->clear($userId);
            return true;
        }

        return false;
    }
}
