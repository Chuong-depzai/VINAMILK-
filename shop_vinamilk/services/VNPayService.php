<?php
// services/VNPayService.php
class VNPayService
{
    private $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/vnpay.php';
    }

    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPaymentUrl($orderId, $amount, $bankCode = '', $orderInfo = '')
    {
        $vnp_TmnCode = $this->config['vnp_TmnCode'];
        $vnp_HashSecret = $this->config['vnp_HashSecret'];
        $vnp_Url = $this->config['vnp_Url'];
        $vnp_Returnurl = $this->config['vnp_Returnurl'];

        // Dữ liệu gửi đến VNPay
        $vnp_TxnRef = $orderId; // Mã đơn hàng
        $vnp_OrderInfo = $orderInfo ?: "Thanh toan don hang #$orderId";
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount * 100; // VNPay yêu cầu số tiền x 100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $this->getClientIp();
        $vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes'));

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate
        ];

        // Thêm bank code nếu có
        if (!empty($bankCode)) {
            $inputData['vnp_BankCode'] = $bankCode;
        }

        // Sắp xếp dữ liệu theo key
        ksort($inputData);

        $query = "";
        $i = 0;
        $hashdata = "";

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;

        // Tạo secure hash
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    /**
     * Xác thực chữ ký từ VNPay return
     */
    public function verifyReturnUrl($vnpayData)
    {
        $vnp_SecureHash = $vnpayData['vnp_SecureHash'];
        unset($vnpayData['vnp_SecureHash']);
        unset($vnpayData['vnp_SecureHashType']);
        unset($vnpayData['controller']);
        unset($vnpayData['action']);

        ksort($vnpayData);

        $hashdata = "";
        $i = 0;

        foreach ($vnpayData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashdata, $this->config['vnp_HashSecret']);

        return $secureHash === $vnp_SecureHash;
    }

    /**
     * Lấy IP client
     */
    private function getClientIp()
    {
        $ipaddress = '';

        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    /**
     * Lấy mô tả lỗi từ response code
     */
    public function getResponseMessage($responseCode)
    {
        $messages = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường)',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa',
            '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP)',
            '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
            '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày',
            '75' => 'Ngân hàng thanh toán đang bảo trì',
            '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định',
            '99' => 'Các lỗi khác'
        ];

        return $messages[$responseCode] ?? 'Lỗi không xác định';
    }

    /**
     * Query transaction (kiểm tra trạng thái giao dịch)
     */
    public function queryTransaction($orderId, $transDate)
    {
        $vnp_RequestId = date("YmdHis");
        $vnp_Version = "2.1.0";
        $vnp_Command = "querydr";
        $vnp_TmnCode = $this->config['vnp_TmnCode'];
        $vnp_TxnRef = $orderId;
        $vnp_OrderInfo = "Kiem tra ket qua GD OrderId:" . $orderId;
        $vnp_TransactionDate = $transDate;
        $vnp_CreateDate = date('YmdHis');
        $vnp_IpAddr = $this->getClientIp();

        $hash_data = $vnp_RequestId . "|" . $vnp_Version . "|" . $vnp_Command . "|" .
            $vnp_TmnCode . "|" . $vnp_TxnRef . "|" . $vnp_TransactionDate . "|" .
            $vnp_CreateDate . "|" . $vnp_IpAddr . "|" . $vnp_OrderInfo;

        $vnp_SecureHash = hash_hmac('sha512', $hash_data, $this->config['vnp_HashSecret']);

        $data = [
            "vnp_RequestId" => $vnp_RequestId,
            "vnp_Version" => $vnp_Version,
            "vnp_Command" => $vnp_Command,
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_TransactionDate" => $vnp_TransactionDate,
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_SecureHash" => $vnp_SecureHash
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config['vnp_apiUrl']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
