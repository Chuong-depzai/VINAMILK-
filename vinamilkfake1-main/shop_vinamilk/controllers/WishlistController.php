<?php
// controllers/WishlistController.php
require_once __DIR__ . '/../models/Wishlist.php';
require_once __DIR__ . '/../controllers/AuthController.php';

class WishlistController
{
    private $wishlistModel;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->wishlistModel = new Wishlist();
    }

    // Kiểm tra đăng nhập
    private function requireLogin()
    {
        if (!AuthController::isLoggedIn()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để sử dụng tính năng này',
                'redirect' => 'index.php?controller=auth&action=showLogin'
            ]);
            exit;
        }
    }

    // Hiển thị trang wishlist
    public function index()
    {
        $this->requireLogin();

        $userId = $_SESSION['user_id'];
        $wishlistItems = $this->wishlistModel->getByUserId($userId);

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/wishlist_view.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // API: Thêm vào wishlist (AJAX)
    public function add()
    {
        $this->requireLogin();

        header('Content-Type: application/json');

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

        if ($productId <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Sản phẩm không hợp lệ'
            ]);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $result = $this->wishlistModel->add($userId, $productId);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Đã thêm vào danh sách yêu thích',
                'count' => $this->wishlistModel->getCount($userId)
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Không thể thêm vào danh sách yêu thích'
            ]);
        }
        exit;
    }

    // API: Xóa khỏi wishlist (AJAX)
    public function remove()
    {
        $this->requireLogin();

        header('Content-Type: application/json');

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

        if ($productId <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Sản phẩm không hợp lệ'
            ]);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $result = $this->wishlistModel->remove($userId, $productId);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Đã xóa khỏi danh sách yêu thích',
                'count' => $this->wishlistModel->getCount($userId)
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Không thể xóa khỏi danh sách yêu thích'
            ]);
        }
        exit;
    }

    // API: Toggle wishlist (AJAX)
    public function toggle()
    {
        $this->requireLogin();

        header('Content-Type: application/json');

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

        if ($productId <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Sản phẩm không hợp lệ'
            ]);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $isInWishlist = $this->wishlistModel->isInWishlist($userId, $productId);

        if ($isInWishlist) {
            $result = $this->wishlistModel->remove($userId, $productId);
            $message = 'Đã xóa khỏi danh sách yêu thích';
            $action = 'removed';
        } else {
            $result = $this->wishlistModel->add($userId, $productId);
            $message = 'Đã thêm vào danh sách yêu thích';
            $action = 'added';
        }

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => $message,
                'action' => $action,
                'count' => $this->wishlistModel->getCount($userId)
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Có lỗi xảy ra'
            ]);
        }
        exit;
    }

    // Chuyển tất cả sang giỏ hàng
    public function moveAllToCart()
    {
        $this->requireLogin();

        $userId = $_SESSION['user_id'];
        $result = $this->wishlistModel->moveAllToCart($userId);

        if ($result) {
            $_SESSION['success_message'] = 'Đã chuyển tất cả sản phẩm vào giỏ hàng';
        } else {
            $_SESSION['error_message'] = 'Danh sách yêu thích trống';
        }

        header('Location: index.php?controller=cart&action=view');
        exit;
    }

    // API: Lấy số lượng wishlist (AJAX)
    public function getCount()
    {
        header('Content-Type: application/json');

        if (!AuthController::isLoggedIn()) {
            echo json_encode(['count' => 0]);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $count = $this->wishlistModel->getCount($userId);

        echo json_encode(['count' => $count]);
        exit;
    }
}
