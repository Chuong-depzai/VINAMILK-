<?php
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/User.php';

class CartController
{
    private $cartModel;
    private $orderModel;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->cartModel = new Cart();
        $this->orderModel = new Order();
    }

    private function redirect($controller, $action = '')
    {
        $url = 'index.php?controller=' . $controller;
        if ($action) {
            $url .= '&action=' . $action;
        }
        header("Location: " . $url);
        exit;
    }

    public function view()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $cartItems = [];
            $total = 0;

            require_once __DIR__ . '/../views/header.php';
            echo '<div class="page-container">';
            echo '<p style="padding: 20px; text-align:center;">Hãy đăng nhập để dùng giỏ hàng.</p>';
            echo '<a href="index.php?controller=auth&action=showLogin" class="btn-primary" style="margin: 20px auto; display: block; text-align: center; width: 200px;">Đăng nhập ngay</a>';
            echo '</div>';
            require_once __DIR__ . '/../views/footer.php';
            return;
        }

        // Lấy giỏ hàng
        $cartItems = $this->cartModel->getItems();
        $total = $this->cartModel->getTotal();

        // Load wishlist count
        require_once __DIR__ . '/../models/Wishlist.php';
        $wishlistModel = new Wishlist();
        $wishlistCount = $wishlistModel->getCount($_SESSION['user_id']);

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/cart_view.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function add()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth', 'login');
        }

        if (isset($_POST['product_id'])) {
            $productId = intval($_POST['product_id']);
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            $this->cartModel->addItem($productId, $quantity);
        }
        $this->redirect('cart', 'view');
    }

    public function update()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth', 'login');
        }

        if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
            $productId = intval($_POST['product_id']);
            $quantity = intval($_POST['quantity']);
            $this->cartModel->updateQuantity($productId, $quantity);
        }
        $this->redirect('cart', 'view');
    }

    public function remove()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth', 'login');
        }

        if (isset($_GET['id'])) {
            $productId = intval($_GET['id']);
            $this->cartModel->removeItem($productId);
        }
        $this->redirect('cart', 'view');
    }

    public function clear()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth', 'login');
        }

        $this->cartModel->clear();
        $this->redirect('cart', 'view');
    }

    public function checkout()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth', 'login');
        }

        $cartItems = $this->cartModel->getItems();
        $total = $this->cartModel->getTotal();

        if (empty($cartItems)) {
            $this->redirect('cart', 'view');
        }

        // Lấy thông tin user
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);

        // Thông tin giao hàng
        $shippingInfo = [
            'name' => $user['full_name'] ?? '',
            'phone' => $user['phone'] ?? '',
            'address' => $_POST['address'] ?? 'Địa chỉ mặc định',
            'notes' => $_POST['notes'] ?? ''
        ];

        // Tạo đơn hàng
        $orderId = $this->orderModel->create($_SESSION['user_id'], $cartItems, $shippingInfo);

        if ($orderId) {
            // Xóa giỏ hàng
            $this->cartModel->clear();

            // Hiển thị thành công
            require_once __DIR__ . '/../views/header.php';
?>
            <style>
                .checkout-success-container {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 500px;
                    padding: 40px 20px;
                }

                .checkout-success-box {
                    background: white;
                    border-radius: 20px;
                    padding: 60px 40px;
                    text-align: center;
                    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                    max-width: 600px;
                }

                .checkout-success-box h2 {
                    font-size: 32px;
                    color: #28a745;
                    margin-bottom: 20px;
                }

                .checkout-success-box p {
                    font-size: 16px;
                    color: #666;
                    margin-bottom: 15px;
                }

                .checkout-success-total {
                    font-size: 24px;
                    color: #ff6b00;
                    font-weight: bold;
                    margin: 30px 0;
                }

                .checkout-success-box a {
                    display: inline-block;
                    padding: 15px 40px;
                    background: #0033a0;
                    color: white;
                    border-radius: 50px;
                    text-decoration: none;
                    font-weight: 600;
                    margin: 10px;
                    transition: all 0.3s ease;
                }

                .checkout-success-box a:hover {
                    background: #002780;
                    transform: translateY(-2px);
                }
            </style>
            <div class="checkout-success-container">
                <div class="checkout-success-box">
                    <h2>✅ Đặt hàng thành công!</h2>
                    <p>Mã đơn hàng: <strong>#<?php echo str_pad($orderId, 6, '0', STR_PAD_LEFT); ?></strong></p>
                    <p>Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ với bạn sớm nhất.</p>
                    <p class="checkout-success-total">Tổng tiền: <?php echo number_format($total, 0, ',', '.'); ?> VNĐ</p>
                    <a href="index.php?controller=order&action=history">📦 Xem đơn hàng</a>
                    <a href="index.php">🛍️ Tiếp tục mua sắm</a>
                </div>
            </div>
<?php
            require_once __DIR__ . '/../views/footer.php';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi đặt hàng';
            $this->redirect('cart', 'view');
        }
        exit;
    }
}
