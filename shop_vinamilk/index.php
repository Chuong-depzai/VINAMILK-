<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/CartController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/StoreController.php';
require_once __DIR__ . '/controllers/WishlistController.php';
require_once __DIR__ . '/controllers/ReviewController.php';

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'product';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

switch ($controller) {

    // ========================================
    // PRODUCT CONTROLLER
    // ========================================
    case 'product':
        $productController = new ProductController();
        switch ($action) {
            case 'productList':
                $productController->productList();
                break;
            case 'show':
                if ($id) $productController->show($id);
                else $productController->index();
                break;
            case 'search':
                $productController->search();
                break;
            case 'admin':
                $productController->admin();
                break;
            case 'create':
                $productController->create();
                break;
            case 'edit':
                if ($id) $productController->edit($id);
                else $productController->admin();
                break;
            case 'delete':
                if ($id) $productController->delete($id);
                else $productController->admin();
                break;
            default:
                $productController->index();
                break;
        }
        break;

    // ========================================
    // CART CONTROLLER
    // ========================================
    case 'cart':
        $cartController = new CartController();
        switch ($action) {
            case 'view':
                $cartController->view();
                break;
            case 'add':
                $cartController->add();
                break;
            case 'update':
                $cartController->update();
                break;
            case 'remove':
                $cartController->remove();
                break;
            case 'clear':
                $cartController->clear();
                break;
            case 'checkout':
                $cartController->checkout();
                break;
            default:
                $cartController->view();
                break;
        }
        break;

    // ========================================
    // AUTH CONTROLLER
    // ========================================
    case 'auth':
        $authController = new AuthController();
        switch ($action) {
            case 'showLogin':
                $authController->showLogin();
                break;
            case 'login':
                $authController->login();
                break;
            case 'showRegister':
                $authController->showRegister();
                break;
            case 'register':
                $authController->register();
                break;
            case 'logout':
                $authController->logout();
                break;
            case 'showForgotPassword':
                $authController->showForgotPassword();
                break;
            case 'sendResetCode':
                $authController->sendResetCode();
                break;
            case 'showVerifyCode':
                $authController->showVerifyCode();
                break;
            case 'resetPassword':
                $authController->resetPassword();
                break;
            default:
                $authController->showLogin();
                break;
        }
        break;

    // ========================================
    // STORE CONTROLLER
    // ========================================
    case 'store':
        $storeController = new StoreController();
        switch ($action) {
            case 'api':
                $storeController->api();
                break;
            default:
                $storeController->index();
                break;
        }
        break;

    // ========================================
    // WISHLIST CONTROLLER
    // ========================================
    case 'wishlist':
        $wishlistController = new WishlistController();
        switch ($action) {
            case 'add':
                $wishlistController->add();
                break;
            case 'remove':
                $wishlistController->remove();
                break;
            case 'toggle':
                $wishlistController->toggle();
                break;
            case 'moveAllToCart':
                $wishlistController->moveAllToCart();
                break;
            case 'getCount':
                $wishlistController->getCount();
                break;
            default:
                $wishlistController->index();
                break;
        }
        break;

    // ========================================
    // REVIEW CONTROLLER
    // ========================================
    case 'review':
        $reviewController = new ReviewController();
        switch ($action) {
            case 'create':
                $reviewController->create();
                break;
            default:
                header("Location: index.php");
                break;
        }
        break;
    // ========================================
    // USER CONTROLLER
    // ========================================
    case 'user':
        require_once __DIR__ . '/controllers/UserController.php';
        $userController = new UserController();
        switch ($action) {
            case 'profile':
                $userController->profile();
                break;
            case 'updateProfile':
                $userController->updateProfile();
                break;
            case 'changePassword':
                $userController->changePassword();
                break;
            default:
                $userController->profile();
                break;
        }
        break;

    // ========================================
    // ORDER CONTROLLER
    // ========================================
    case 'order':
        require_once __DIR__ . '/controllers/OrderController.php';
        $orderController = new OrderController();
        switch ($action) {
            case 'history':
                $orderController->history();
                break;
            case 'detail':
                $orderController->detail();
                break;
            case 'cancel':
                $orderController->cancel();
                break;
            default:
                $orderController->history();
                break;
        }
        break;

    // ========================================
    // CHAT CONTROLLER
    // ========================================
    case 'chat':
        require_once __DIR__ . '/controllers/ChatController.php';
        $chatController = new ChatController();
        switch ($action) {
            case 'send':
                $chatController->send();
                break;
            case 'history':
                $chatController->history();
                break;
            case 'clearHistory':
                $chatController->clearHistory();
                break;
            default:
                header("Location: index.php");
                break;
        }
        break;

    // ========================================
    // ADMIN CONTROLLER  (ĐÃ CHÈN ĐÚNG VỊ TRÍ)
    // ========================================
    case 'admin':
        require_once __DIR__ . '/controllers/AdminController.php';
        $adminController = new AdminController();

        switch ($action) {
            case 'dashboard':
                $adminController->dashboard();
                break;
            case 'orders':
                $adminController->orders();
                break;
            case 'orderDetail':
                $adminController->orderDetail();
                break;
            case 'updateOrderStatus':
                $adminController->updateOrderStatus();
                break;
            case 'users':
                $adminController->users();
                break;
            case 'updateUserRole':
                $adminController->updateUserRole();
                break;
            case 'deleteUser':
                $adminController->deleteUser();
                break;
            case 'reports':
                $adminController->reports();
                break;
            default:
                $adminController->dashboard();
                break;
        }
        break;

    // ========================================
    // DEFAULT – TRANG CHỦ
    // ========================================
    default:
        $productController = new ProductController();
        $productController->index();
        break;
}
