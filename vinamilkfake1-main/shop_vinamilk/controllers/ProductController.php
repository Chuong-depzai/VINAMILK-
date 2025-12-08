<?php
require_once __DIR__ . '/../models/Product.php';

class ProductController
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    // ========================================
    // TRANG CHỦ MỚI (Home page)
    // ========================================
    public function index()
    {
        $products = $this->productModel->getAll();
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/home.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // ========================================
    // DANH SÁCH SẢN PHẨM (Product list)
    // ========================================
    public function productList()
    {
        $products = $this->productModel->getAll();
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/product_list.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // ========================================
    // CHI TIẾT SẢN PHẨM (Product detail)
    // ========================================
    public function show($id)
    {
        $product = $this->productModel->getById($id);
        if (!$product) {
            header("Location: index.php");
            exit;
        }

        // Tải Review Model
        require_once __DIR__ . '/../models/Review.php';
        $reviewModel = new Review();
        $reviews = $reviewModel->getByProduct($id);
        $ratingInfo = $reviewModel->getAverageRating($id);

        // Hiển thị nút wishlist
        $showWishlist = true;

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/product_detail.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // ========================================
    // TÌM KIẾM SẢN PHẨM
    // ========================================
    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';

        if (empty($keyword)) {
            header("Location: index.php?controller=product&action=productList");
            exit;
        }

        $products = $this->productModel->search($keyword);

        require_once __DIR__ . '/../views/header.php';
        echo '<div class="page-container">';
        echo '<h1 class="page-title">Kết quả tìm kiếm: "' . htmlspecialchars($keyword) . '"</h1>';

        if (empty($products)) {
            echo '<div class="empty-state">';
            echo '<p class="empty-state-text">Không tìm thấy sản phẩm nào phù hợp</p>';
            echo '<a href="index.php" class="btn-primary">Quay về trang chủ</a>';
            echo '</div>';
        } else {
            require_once __DIR__ . '/../views/product_list.php';
        }

        echo '</div>';
        require_once __DIR__ . '/../views/footer.php';
    }

    // ========================================
    // QUẢN TRỊ SẢN PHẨM
    // ========================================
    public function admin()
    {
        $products = $this->productModel->getAll();
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/admin_products.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // ========================================
    // THÊM SẢN PHẨM MỚI
    // ========================================
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->handleUpload();
            if ($data) {
                $this->productModel->create($data);
                $_SESSION['success_message'] = 'Thêm sản phẩm thành công!';
                header("Location: index.php?controller=product&action=admin");
                exit;
            } else {
                $_SESSION['error_message'] = 'Lỗi khi upload ảnh!';
            }
        }

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/product_form.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // ========================================
    // SỬA SẢN PHẨM
    // ========================================
    public function edit($id)
    {
        $product = $this->productModel->getById($id);
        if (!$product) {
            header("Location: index.php?controller=product&action=admin");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->handleUpload($product['image']);
            if ($data) {
                $this->productModel->update($id, $data);
                $_SESSION['success_message'] = 'Cập nhật sản phẩm thành công!';
                header("Location: index.php?controller=product&action=admin");
                exit;
            }
        }

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/product_form.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // ========================================
    // XÓA SẢN PHẨM
    // ========================================
    public function delete($id)
    {
        $product = $this->productModel->getById($id);
        if ($product && file_exists(__DIR__ . '/../uploads/' . $product['image'])) {
            unlink(__DIR__ . '/../uploads/' . $product['image']);
        }

        $this->productModel->delete($id);
        $_SESSION['success_message'] = 'Xóa sản phẩm thành công!';
        header("Location: index.php?controller=product&action=admin");
        exit;
    }

    // ========================================
    // XỬ LÝ UPLOAD ẢNH
    // ========================================
    private function handleUpload($oldImage = null)
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'type' => $_POST['type'] ?? '',
            'description' => $_POST['description'] ?? '',
            'ingredients' => $_POST['ingredients'] ?? '',
            'packaging' => $_POST['packaging'] ?? 'Hộp',
            'image' => $oldImage ?? ''
        ];

        // Kiểm tra nếu có upload ảnh
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';

            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    error_log("Lỗi: Không thể tạo thư mục uploads!");
                    return false;
                }
            }

            // Kiểm tra quyền ghi
            if (!is_writable($uploadDir)) {
                error_log("Lỗi: Thư mục uploads không có quyền ghi!");
                return false;
            }

            // Tạo tên file an toàn
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $targetPath = $uploadDir . $fileName;

            // Kiểm tra loại file
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $fileType = $_FILES['image']['type'];

            if (!in_array($fileType, $allowedTypes)) {
                error_log("Lỗi: Chỉ chấp nhận file JPG, PNG, GIF!");
                return false;
            }

            // Kiểm tra kích thước file (max 5MB)
            if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                error_log("Lỗi: File không được vượt quá 5MB!");
                return false;
            }

            // Upload file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                // Xóa ảnh cũ nếu có
                if ($oldImage && file_exists($uploadDir . $oldImage)) {
                    unlink($uploadDir . $oldImage);
                }
                $data['image'] = $fileName;
            } else {
                error_log("Lỗi: Không thể upload file! Error code: " . $_FILES['image']['error']);
                return false;
            }
        }

        return $data;
    }
}
