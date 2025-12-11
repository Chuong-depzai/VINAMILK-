<style>
    .failure-container {
        max-width: 700px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .failure-card {
        background: white;
        border-radius: 16px;
        padding: 50px 40px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .failure-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 25px;
        background: linear-gradient(135deg, #dc3545 0%, #ff4757 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .failure-icon svg {
        width: 50px;
        height: 50px;
        stroke: white;
        stroke-width: 3;
        fill: none;
    }

    .failure-title {
        font-size: 32px;
        color: #dc3545;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .failure-subtitle {
        font-size: 16px;
        color: #666;
        margin-bottom: 30px;
    }

    .error-info {
        background: #fff3f3;
        border-left: 4px solid #dc3545;
        border-radius: 8px;
        padding: 20px;
        margin: 30px 0;
        text-align: left;
    }

    .error-code {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }

    .error-message {
        font-size: 15px;
        color: #dc3545;
        font-weight: 600;
        line-height: 1.6;
    }

    .order-ref {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin: 20px 0;
        font-size: 14px;
        color: #666;
    }

    .order-ref strong {
        color: #333;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-action {
        flex: 1;
        padding: 15px 30px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
    }

    .btn-primary-action {
        background: #0033a0;
        color: white;
    }

    .btn-primary-action:hover {
        background: #002780;
    }

    .btn-danger-action {
        background: white;
        color: #dc3545;
        border: 2px solid #dc3545;
    }

    .btn-danger-action:hover {
        background: #dc3545;
        color: white;
    }

    .help-section {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
        font-size: 14px;
        color: #666;
    }

    .help-section strong {
        color: #0033a0;
    }

    @media (max-width: 768px) {
        .failure-card {
            padding: 40px 20px;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="failure-container">
    <div class="failure-card">
        <div class="failure-icon">
            <svg viewBox="0 0 24 24">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>

        <h1 class="failure-title">❌ Thanh toán không thành công</h1>
        <p class="failure-subtitle">
            Rất tiếc, giao dịch của bạn không thể hoàn tất
        </p>

        <div class="error-info">
            <div class="error-code">
                Mã lỗi: <strong><?php echo htmlspecialchars($errorData['error_code']); ?></strong>
            </div>
            <div class="error-message">
                <?php echo htmlspecialchars($errorData['error_message']); ?>
            </div>
        </div>

        <div class="order-ref">
            <strong>📦 Mã đơn hàng:</strong> #<?php echo str_pad($errorData['order_id'], 6, '0', STR_PAD_LEFT); ?><br>
            <strong>🕐 Thời gian:</strong> <?php echo date('d/m/Y H:i:s'); ?>
        </div>

        <div class="action-buttons">
            <a href="index.php?controller=payment&action=checkout" class="btn-action btn-primary-action">
                🔄 Thử lại thanh toán
            </a>
            <a href="index.php?controller=order&action=history" class="btn-action btn-danger-action">
                📋 Xem đơn hàng
            </a>
        </div>

        <div class="help-section">
            <p>
                <strong>💡 Một số nguyên nhân thường gặp:</strong>
            </p>
            <ul style="text-align: left; margin: 15px 0; line-height: 1.8;">
                <li>Số dư tài khoản không đủ</li>
                <li>Thẻ/tài khoản chưa đăng ký dịch vụ thanh toán online</li>
                <li>Nhập sai mật khẩu OTP</li>
                <li>Hết thời gian thanh toán (15 phút)</li>
                <li>Ngân hàng đang bảo trì</li>
            </ul>
            <p>
                <strong>📞 Cần hỗ trợ?</strong> Liên hệ hotline: <strong style="color: #0033a0;">1900 636 979</strong>
            </p>
        </div>
    </div>
</div>
<style>
    .failure-container {
        max-width: 700px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .failure-card {
        background: white;
        border-radius: 16px;
        padding: 50px 40px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .failure-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 25px;
        background: linear-gradient(135deg, #dc3545 0%, #ff4757 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .failure-icon svg {
        width: 50px;
        height: 50px;
        stroke: white;
        stroke-width: 3;
        fill: none;
    }

    .failure-title {
        font-size: 32px;
        color: #dc3545;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .failure-subtitle {
        font-size: 16px;
        color: #666;
        margin-bottom: 30px;
    }

    .error-info {
        background: #fff3f3;
        border-left: 4px solid #dc3545;
        border-radius: 8px;
        padding: 20px;
        margin: 30px 0;
        text-align: left;
    }

    .error-code {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }

    .error-message {
        font-size: 15px;
        color: #dc3545;
        font-weight: 600;
        line-height: 1.6;
    }

    .order-ref {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin: 20px 0;
        font-size: 14px;
        color: #666;
    }

    .order-ref strong {
        color: #333;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-action {
        flex: 1;
        padding: 15px 30px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
    }

    .btn-primary-action {
        background: #0033a0;
        color: white;
    }

    .btn-primary-action:hover {
        background: #002780;
    }

    .btn-danger-action {
        background: white;
        color: #dc3545;
        border: 2px solid #dc3545;
    }

    .btn-danger-action:hover {
        background: #dc3545;
        color: white;
    }

    .help-section {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
        font-size: 14px;
        color: #666;
    }

    .help-section strong {
        color: #0033a0;
    }

    @media (max-width: 768px) {
        .failure-card {
            padding: 40px 20px;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="failure-container">
    <div class="failure-card">
        <div class="failure-icon">
            <svg viewBox="0 0 24 24">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>

        <h1 class="failure-title">❌ Thanh toán không thành công</h1>
        <p class="failure-subtitle">
            Rất tiếc, giao dịch của bạn không thể hoàn tất
        </p>

        <div class="error-info">
            <div class="error-code">
                Mã lỗi: <strong><?php echo htmlspecialchars($errorData['error_code']); ?></strong>
            </div>
            <div class="error-message">
                <?php echo htmlspecialchars($errorData['error_message']); ?>
            </div>
        </div>

        <div class="order-ref">
            <strong>📦 Mã đơn hàng:</strong> #<?php echo str_pad($errorData['order_id'], 6, '0', STR_PAD_LEFT); ?><br>
            <strong>🕐 Thời gian:</strong> <?php echo date('d/m/Y H:i:s'); ?>
        </div>

        <div class="action-buttons">
            <a href="index.php?controller=payment&action=checkout" class="btn-action btn-primary-action">
                🔄 Thử lại thanh toán
            </a>
            <a href="index.php?controller=order&action=history" class="btn-action btn-danger-action">
                📋 Xem đơn hàng
            </a>
        </div>

        <div class="help-section">
            <p>
                <strong>💡 Một số nguyên nhân thường gặp:</strong>
            </p>
            <ul style="text-align: left; margin: 15px 0; line-height: 1.8;">
                <li>Số dư tài khoản không đủ</li>
                <li>Thẻ/tài khoản chưa đăng ký dịch vụ thanh toán online</li>
                <li>Nhập sai mật khẩu OTP</li>
                <li>Hết thời gian thanh toán (15 phút)</li>
                <li>Ngân hàng đang bảo trì</li>
            </ul>
            <p>
                <strong>📞 Cần hỗ trợ?</strong> Liên hệ hotline: <strong style="color: #0033a0;">1900 636 979</strong>
            </p>
        </div>
    </div>
</div>