<?php
// controllers/PaymentController.php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../services/VNPayService.php';

class PaymentController
{
    private $orderModel;
    private $cartModel;
    private $userModel;
    private $vnpayService;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $this->orderModel = new Order();
        $this->cartModel = new Cart();
        $this->userModel = new User();
        $this->vnpayService = new VNPayService();
    }

    /**
     * Trang chọn phương thức thanh toán
     */
    public function checkout()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để thanh toán';
            header('Location: index.php?controller=auth&action=showLogin');
            exit;
        }

        // Lấy giỏ hàng
        $cartItems = $this->cartModel->getItems();
        if (empty($cartItems)) {
            $_SESSION['error_message'] = 'Giỏ hàng trống';
            header('Location: index.php?controller=cart&action=view');
            exit;
        }

        $total = $this->cartModel->getTotal();

        // Lấy thông tin user
        $user = $this->userModel->findById($_SESSION['user_id']);

        // Lấy danh sách ngân hàng
        $vnpayConfig = require __DIR__ . '/../config/vnpay.php';
        $banks = $vnpayConfig['banks'];

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/payment_checkout.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    /**
     * Xử lý thanh toán VNPay
     */
    public function processVNPay()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=payment&action=checkout');
            exit;
        }

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập';
            header('Location: index.php?controller=auth&action=showLogin');
            exit;
        }

        // Lấy thông tin từ form
        $bankCode = $_POST['bank_code'] ?? 'VNPAYQR';
        $shippingName = trim($_POST['shipping_name'] ?? '');
        $shippingPhone = trim($_POST['shipping_phone'] ?? '');
        $shippingAddress = trim($_POST['shipping_address'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        // Validate
        if (empty($shippingName) || empty($shippingPhone) || empty($shippingAddress)) {
            $_SESSION['error_message'] = 'Vui lòng điền đầy đủ thông tin giao hàng';
            header('Location: index.php?controller=payment&action=checkout');
            exit;
        }

        // Lấy giỏ hàng
        $cartItems = $this->cartModel->getItems();
        if (empty($cartItems)) {
            $_SESSION['error_message'] = 'Giỏ hàng trống';
            header('Location: index.php?controller=cart&action=view');
            exit;
        }

        $total = $this->cartModel->getTotal();

        // Tạo đơn hàng với trạng thái pending_payment
        $shippingInfo = [
            'name' => $shippingName,
            'phone' => $shippingPhone,
            'address' => $shippingAddress,
            'notes' => $notes
        ];

        $orderId = $this->orderModel->createWithPayment(
            $_SESSION['user_id'],
            $cartItems,
            $shippingInfo,
            'vnpay',
            'pending_payment'
        );

        if (!$orderId) {
            $_SESSION['error_message'] = 'Lỗi khi tạo đơn hàng';
            header('Location: index.php?controller=payment&action=checkout');
            exit;
        }

        // Xóa giỏ hàng
        $this->cartModel->clear();

        // Tạo URL thanh toán VNPay
        $paymentUrl = $this->vnpayService->createPaymentUrl(
            $orderId,
            $total,
            $bankCode,
            "Thanh toan don hang #$orderId"
        );

        // Chuyển hướng đến VNPay
        header('Location: ' . $paymentUrl);
        exit;
    }

    /**
     * Xử lý kết quả trả về từ VNPay
     */
    public function vnpayReturn()
    {
        // Verify chữ ký từ VNPay
        $vnpayData = $_GET;

        if (!$this->vnpayService->verifyReturnUrl($vnpayData)) {
            $_SESSION['error_message'] = 'Dữ liệu không hợp lệ';
            header('Location: index.php');
            exit;
        }

        $orderId = $vnpayData['vnp_TxnRef'];
        $responseCode = $vnpayData['vnp_ResponseCode'];
        $transactionNo = $vnpayData['vnp_TransactionNo'] ?? '';
        $bankCode = $vnpayData['vnp_BankCode'] ?? '';
        $amount = $vnpayData['vnp_Amount'] / 100; // VNPay trả về số tiền x100

        // Lấy thông tin đơn hàng
        $order = $this->orderModel->getById($orderId);

        if (!$order) {
            $_SESSION['error_message'] = 'Không tìm thấy đơn hàng';
            header('Location: index.php');
            exit;
        }

        // Kiểm tra kết quả giao dịch
        if ($responseCode == '00') {
            // Thanh toán thành công
            $this->orderModel->updatePaymentStatus(
                $orderId,
                'paid',
                'processing',
                $transactionNo,
                $bankCode
            );

            $_SESSION['payment_success'] = [
                'order_id' => $orderId,
                'amount' => $amount,
                'transaction_no' => $transactionNo,
                'bank_code' => $bankCode
            ];

            header('Location: index.php?controller=payment&action=success');
        } else {
            // Thanh toán thất bại
            $errorMessage = $this->vnpayService->getResponseMessage($responseCode);

            $this->orderModel->updatePaymentStatus(
                $orderId,
                'failed',
                'cancelled',
                $transactionNo,
                $bankCode,
                $errorMessage
            );

            $_SESSION['payment_error'] = [
                'order_id' => $orderId,
                'error_code' => $responseCode,
                'error_message' => $errorMessage
            ];

            header('Location: index.php?controller=payment&action=failure');
        }
        exit;
    }

    /**
     * Trang thanh toán thành công
     */
    public function success()
    {
        if (!isset($_SESSION['payment_success'])) {
            header('Location: index.php');
            exit;
        }

        $paymentData = $_SESSION['payment_success'];
        unset($_SESSION['payment_success']);

        $order = $this->orderModel->getById($paymentData['order_id']);

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/payment_success.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    /**
     * Trang thanh toán thất bại
     */
    public function failure()
    {
        if (!isset($_SESSION['payment_error'])) {
            header('Location: index.php');
            exit;
        }

        $errorData = $_SESSION['payment_error'];
        unset($_SESSION['payment_error']);

        $order = $this->orderModel->getById($errorData['order_id']);

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/payment_failure.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    /**
     * Xử lý thanh toán COD (Ship COD)
     */
    public function processCOD()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=payment&action=checkout');
            exit;
        }

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập';
            header('Location: index.php?controller=auth&action=showLogin');
            exit;
        }

        // Lấy thông tin
        $shippingName = trim($_POST['shipping_name'] ?? '');
        $shippingPhone = trim($_POST['shipping_phone'] ?? '');
        $shippingAddress = trim($_POST['shipping_address'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        // Validate
        if (empty($shippingName) || empty($shippingPhone) || empty($shippingAddress)) {
            $_SESSION['error_message'] = 'Vui lòng điền đầy đủ thông tin giao hàng';
            header('Location: index.php?controller=payment&action=checkout');
            exit;
        }

        // Lấy giỏ hàng
        $cartItems = $this->cartModel->getItems();
        if (empty($cartItems)) {
            $_SESSION['error_message'] = 'Giỏ hàng trống';
            header('Location: index.php?controller=cart&action=view');
            exit;
        }

        // Tạo đơn hàng COD
        $shippingInfo = [
            'name' => $shippingName,
            'phone' => $shippingPhone,
            'address' => $shippingAddress,
            'notes' => $notes
        ];

        $orderId = $this->orderModel->createWithPayment(
            $_SESSION['user_id'],
            $cartItems,
            $shippingInfo,
            'cod',
            'pending' // COD thì trạng thái là pending
        );

        if ($orderId) {
            // Xóa giỏ hàng
            $this->cartModel->clear();

            $_SESSION['cod_success'] = [
                'order_id' => $orderId
            ];

            header('Location: index.php?controller=payment&action=codSuccess');
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi đặt hàng';
            header('Location: index.php?controller=payment&action=checkout');
        }
        exit;
    }

    /**
     * Trang đặt hàng COD thành công
     */
    public function codSuccess()
    {
        if (!isset($_SESSION['cod_success'])) {
            header('Location: index.php');
            exit;
        }

        $data = $_SESSION['cod_success'];
        unset($_SESSION['cod_success']);

        $order = $this->orderModel->getById($data['order_id']);

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/payment_cod_success.php';
        require_once __DIR__ . '/../views/footer.php';
    }
}
