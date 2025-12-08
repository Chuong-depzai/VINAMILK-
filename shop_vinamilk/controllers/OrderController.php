<?php
// controllers/OrderController.php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Cart.php';

class OrderController
{
    private $orderModel;
    private $cartModel;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->orderModel = new Order();
        $this->cartModel = new Cart();
    }

    // Hiển thị lịch sử đơn hàng
    public function history()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=showLogin");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $orders = $this->orderModel->getByUserId($userId);

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/order_history.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // Xem chi tiết đơn hàng
    public function detail()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=showLogin");
            exit;
        }

        $orderId = $_GET['id'] ?? 0;
        $order = $this->orderModel->getById($orderId);

        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            header("Location: index.php?controller=order&action=history");
            exit;
        }

        $orderItems = $this->orderModel->getOrderItems($orderId);

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/order_detail.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // Hủy đơn hàng
    public function cancel()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=showLogin");
            exit;
        }

        $orderId = $_GET['id'] ?? 0;
        $userId = $_SESSION['user_id'];

        if ($this->orderModel->cancel($orderId, $userId)) {
            $_SESSION['success_message'] = 'Đã hủy đơn hàng thành công';
        } else {
            $_SESSION['error_message'] = 'Không thể hủy đơn hàng này';
        }

        header("Location: index.php?controller=order&action=history");
        exit;
    }
}
