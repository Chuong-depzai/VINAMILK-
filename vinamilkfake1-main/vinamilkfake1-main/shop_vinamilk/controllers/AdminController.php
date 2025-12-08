<?php
// controllers/AdminController.php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class AdminController
{
    private $userModel;
    private $orderModel;
    private $productModel;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        // Yêu cầu đăng nhập và là admin
        AuthMiddleware::requireAdmin();

        $this->userModel = new User();
        $this->orderModel = new Order();
        $this->productModel = new Product();
    }

    // ========================================
    // DASHBOARD - Trang chủ Admin
    // ========================================
    public function dashboard()
    {
        // Lấy thống kê tổng quan
        $stats = $this->getDashboardStats();

        // Đơn hàng gần đây
        $recentOrders = $this->orderModel->getRecentOrders(10);

        // Sản phẩm bán chạy
        $topProducts = $this->productModel->getTopSelling(5);

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/admin_dashboard.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // ========================================
    // QUẢN LÝ ĐỐN HÀNG
    // ========================================
    public function orders()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 20;

        $status = isset($_GET['status']) ? $_GET['status'] : null;

        if ($status) {
            $orders = $this->orderModel->getByStatus($status, $page, $perPage);
            $totalOrders = $this->orderModel->countByStatus($status);
        } else {
            $orders = $this->orderModel->getAllOrders($page, $perPage);
            $totalOrders = $this->orderModel->countAllOrders();
        }

        $totalPages = ceil($totalOrders / $perPage);

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/admin_orders.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // Chi tiết đơn hàng
    public function orderDetail()
    {
        $orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if (!$orderId) {
            header('Location: index.php?controller=admin&action=orders');
            exit;
        }

        $order = $this->orderModel->getById($orderId);

        if (!$order) {
            $_SESSION['error_message'] = 'Không tìm thấy đơn hàng';
            header('Location: index.php?controller=admin&action=orders');
            exit;
        }

        $orderItems = $this->orderModel->getOrderItems($orderId);
        $statusHistory = $this->orderModel->getStatusHistory($orderId);

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/admin_order_detail.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=admin&action=orders');
            exit;
        }

        $orderId = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $newStatus = isset($_POST['status']) ? $_POST['status'] : '';
        $note = isset($_POST['note']) ? trim($_POST['note']) : '';

        $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];

        if (!$orderId || !in_array($newStatus, $validStatuses)) {
            $_SESSION['error_message'] = 'Dữ liệu không hợp lệ';
            header('Location: index.php?controller=admin&action=orders');
            exit;
        }

        $order = $this->orderModel->getById($orderId);

        if (!$order) {
            $_SESSION['error_message'] = 'Không tìm thấy đơn hàng';
            header('Location: index.php?controller=admin&action=orders');
            exit;
        }

        // Cập nhật trạng thái và lưu lịch sử
        $result = $this->orderModel->updateStatusWithHistory(
            $orderId,
            $order['status'],
            $newStatus,
            $_SESSION['user_id'],
            $note
        );

        if ($result) {
            $_SESSION['success_message'] = 'Cập nhật trạng thái đơn hàng thành công';
        } else {
            $_SESSION['error_message'] = 'Lỗi khi cập nhật trạng thái';
        }

        header('Location: index.php?controller=admin&action=orderDetail&id=' . $orderId);
        exit;
    }

    // ========================================
    // QUẢN LÝ NGƯỜI DÙNG
    // ========================================
    public function users()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 20;

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        if ($search) {
            $users = $this->userModel->searchUsers($search);
            $totalUsers = count($users);
        } else {
            $users = $this->userModel->getAllUsers($page, $perPage);
            $totalUsers = $this->userModel->countUsers();
        }

        $totalPages = ceil($totalUsers / $perPage);

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/admin_users.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // Cập nhật role user
    public function updateUserRole()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=admin&action=users');
            exit;
        }

        $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $role = isset($_POST['role']) ? $_POST['role'] : '';

        if (!$userId || !in_array($role, ['admin', 'user'])) {
            $_SESSION['error_message'] = 'Dữ liệu không hợp lệ';
            header('Location: index.php?controller=admin&action=users');
            exit;
        }

        // Không cho phép tự thay đổi role của chính mình
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Không thể thay đổi quyền của chính mình';
            header('Location: index.php?controller=admin&action=users');
            exit;
        }

        $result = $this->userModel->updateRole($userId, $role);

        if ($result) {
            $_SESSION['success_message'] = 'Cập nhật quyền người dùng thành công';
        } else {
            $_SESSION['error_message'] = 'Lỗi khi cập nhật quyền';
        }

        header('Location: index.php?controller=admin&action=users');
        exit;
    }

    // Xóa user
    public function deleteUser()
    {
        $userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if (!$userId) {
            $_SESSION['error_message'] = 'Dữ liệu không hợp lệ';
            header('Location: index.php?controller=admin&action=users');
            exit;
        }

        // Không cho phép xóa chính mình
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Không thể xóa tài khoản của chính mình';
            header('Location: index.php?controller=admin&action=users');
            exit;
        }

        $result = $this->userModel->deleteUser($userId);

        if ($result) {
            $_SESSION['success_message'] = 'Xóa người dùng thành công';
        } else {
            $_SESSION['error_message'] = 'Không thể xóa người dùng này (có thể là admin)';
        }

        header('Location: index.php?controller=admin&action=users');
        exit;
    }

    // ========================================
    // THỐNG KÊ
    // ========================================
    private function getDashboardStats()
    {
        return [
            'total_users' => $this->userModel->countUsers(),
            'total_orders' => $this->orderModel->countAllOrders(),
            'total_products' => $this->productModel->countProducts(),
            'total_revenue' => $this->orderModel->getTotalRevenue(),
            'pending_orders' => $this->orderModel->countByStatus('pending'),
            'processing_orders' => $this->orderModel->countByStatus('processing'),
            'completed_orders' => $this->orderModel->countByStatus('completed'),
            'cancelled_orders' => $this->orderModel->countByStatus('cancelled'),
        ];
    }

    public function reports()
    {
        $type = isset($_GET['type']) ? $_GET['type'] : 'daily';

        switch ($type) {
            case 'daily':
                $data = $this->orderModel->getDailyRevenue(30);
                break;
            case 'monthly':
                $data = $this->orderModel->getMonthlyRevenue(12);
                break;
            case 'products':
                $data = $this->productModel->getProductStats();
                break;
            default:
                $data = [];
        }

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/admin_reports.php';
        require_once __DIR__ . '/../views/footer.php';
    }
}
