<?php
// models/Order.php
require_once __DIR__ . '/../db.php';

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // Tạo đơn hàng mới
    public function create($userId, $cartItems, $shippingInfo)
    {
        try {
            $this->db->beginTransaction();

            // Tính tổng tiền
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += $item['subtotal'];
            }

            // Insert đơn hàng
            $sql = "INSERT INTO orders (user_id, total_amount, shipping_name, shipping_phone, shipping_address, notes) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $userId,
                $totalAmount,
                $shippingInfo['name'] ?? '',
                $shippingInfo['phone'] ?? '',
                $shippingInfo['address'] ?? '',
                $shippingInfo['notes'] ?? ''
            ]);

            $orderId = $this->db->lastInsertId();

            // Insert chi tiết đơn hàng
            $sql = "INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);

            foreach ($cartItems as $item) {
                $stmt->execute([
                    $orderId,
                    $item['id'],
                    $item['name'],
                    $item['price'],
                    $item['quantity'],
                    $item['subtotal']
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Lấy đơn hàng theo user
    public function getByUserId($userId)
    {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Lấy chi tiết đơn hàng
    public function getById($orderId)
    {
        $sql = "SELECT o.*, u.full_name, u.phone, u.email 
                FROM orders o
                JOIN users u ON o.user_id = u.id
                WHERE o.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetch();
    }

    // Lấy các sản phẩm trong đơn hàng
    public function getOrderItems($orderId)
    {
        $sql = "SELECT * FROM order_items WHERE order_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus($orderId, $status)
    {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $orderId]);
    }

    // Hủy đơn hàng
    public function cancel($orderId, $userId)
    {
        $sql = "UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status = 'pending'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$orderId, $userId]);
    }

    // Đếm số đơn hàng theo trạng thái
    public function countByStatus($userId, $status = null)
    {
        if ($status) {
            $sql = "SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $status]);
        } else {
            $sql = "SELECT COUNT(*) FROM orders WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
        }
        return $stmt->fetchColumn();
    }

    // ADMIN: Lấy tất cả đơn hàng
    public function getAllOrders($page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT o.*, u.full_name, u.phone as user_phone, u.email 
                FROM orders o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$perPage, $offset]);
        return $stmt->fetchAll();
    }

    // ADMIN: Đếm tổng số đơn hàng
    public function countAllOrders()
    {
        $sql = "SELECT COUNT(*) FROM orders";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn();
    }

    // ADMIN: Lấy đơn hàng theo trạng thái
    public function getByStatus($status, $page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT o.*, u.full_name, u.phone as user_phone, u.email 
                FROM orders o
                JOIN users u ON o.user_id = u.id
                WHERE o.status = ?
                ORDER BY o.created_at DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status, $perPage, $offset]);
        return $stmt->fetchAll();
    }

    // ADMIN: Lấy đơn hàng gần đây
    public function getRecentOrders($limit = 10)
    {
        $sql = "SELECT o.*, u.full_name, u.phone as user_phone 
                FROM orders o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    // ADMIN: Cập nhật trạng thái và lưu lịch sử
    public function updateStatusWithHistory($orderId, $oldStatus, $newStatus, $adminId, $note = '')
    {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE orders SET status = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$newStatus, $orderId]);

            $sql = "INSERT INTO order_status_history (order_id, old_status, new_status, changed_by, note) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId, $oldStatus, $newStatus, $adminId, $note]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // ADMIN: Lấy lịch sử thay đổi trạng thái
    public function getStatusHistory($orderId)
    {
        $sql = "SELECT h.*, u.full_name as changed_by_name 
                FROM order_status_history h
                JOIN users u ON h.changed_by = u.id
                WHERE h.order_id = ?
                ORDER BY h.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    // ADMIN: Tính tổng doanh thu
    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(total_amount) FROM orders WHERE status IN ('completed', 'processing')";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn() ?? 0;
    }

    // ADMIN: Báo cáo doanh thu theo ngày
    public function getDailyRevenue($days = 30)
    {
        $sql = "SELECT DATE(created_at) as date, 
                       COUNT(*) as order_count,
                       SUM(total_amount) as revenue
                FROM orders
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                  AND status IN ('completed', 'processing')
                GROUP BY DATE(created_at)
                ORDER BY date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }

    // ADMIN: Báo cáo doanh thu theo tháng
    public function getMonthlyRevenue($months = 12)
    {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month,
                       COUNT(*) as order_count,
                       SUM(total_amount) as revenue
                FROM orders
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
                  AND status IN ('completed', 'processing')
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$months]);
        return $stmt->fetchAll();
    }
}
