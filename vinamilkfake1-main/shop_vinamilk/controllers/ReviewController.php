<?php
// controllers/ReviewController.php
require_once __DIR__ . '/../models/Review.php'; 

class ReviewController
{
    private $reviewModel;

    public function __construct()
    {
        // Khởi tạo Review Model
        $this->reviewModel = new Review();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function create()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để gửi đánh giá.';
            header("Location: index.php?controller=auth&action=showLogin");
            exit;
        }

        // Xử lý form POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = intval($_POST['product_id'] ?? 0);
            $userId = $_SESSION['user_id'];
            $rating = intval($_POST['rating'] ?? 0);
            $comment = trim(strip_tags($_POST['comment'] ?? ''));

            // Validate
            if ($productId > 0 && $userId && $rating >= 1 && $rating <= 5) {
                $this->reviewModel->create($productId, $userId, $rating, $comment);
            }
            
            // Quay lại trang chi tiết sản phẩm
            header("Location: index.php?controller=product&action=show&id=" . $productId);
            exit;
        }
        
        // Nếu không phải POST, chuyển hướng về trang chủ
        header("Location: index.php");
        exit;
    }

    // Hàm load (có thể để trống, hoặc xóa nếu không dùng)
    public function load() {
        // Có thể dùng để trả về JSON cho AJAX, nhưng hiện tại ta dùng PHP thuần.
        // Để trống để tránh lỗi "method not found" nếu index.php gọi tới nó.
    }
}