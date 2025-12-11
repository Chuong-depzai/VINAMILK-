<?php
return [
    'vnp_TmnCode' => 'DR9C2ZEG',
    'vnp_HashSecret' => '6G03ROJJICA1IEB8LIYQXR1MGD7UDLPF',
    'vnp_Url' => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
    'vnp_Returnurl' => 'http://localhost/shop_vinamilk/index.php?controller=payment&action=vnpayReturn',
    'vnp_apiUrl' => 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction',
    'banks' => [
        'VNPAYQR' => [
            'name' => 'Quét mã QR',
            'logo' => 'https://vnpay.vn/s1/statics.vnpay.vn/2023/9/06ncktiwd6dc1694418196384.png',
            'type' => 'qr'
        ],
        'VNBANK' => [
            'name' => 'Thẻ ATM nội địa',
            'logo' => 'https://vnpay.vn/s1/statics.vnpay.vn/2023/9/06ncktiwd6dc1694418196384.png',
            'type' => 'atm'
        ],
        'INTCARD' => [
            'name' => 'Thẻ Visa/Master/JCB',
            'logo' => 'https://vnpay.vn/s1/statics.vnpay.vn/2023/9/0oxhzjmxbksr1694418217820.png',
            'type' => 'card'
        ],
    ],
    'timeout' => 900,
    'environment' => 'sandbox',
];
