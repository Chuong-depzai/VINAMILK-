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

        // Chuyển hướng đến payment checkout thay vì tạo đơn hàng ngay
        // Vì bây giờ payment controller sẽ xử lý việc tạo đơn hàng
        header('Location: index.php?controller=payment&action=checkout');
        exit;
    }
}
