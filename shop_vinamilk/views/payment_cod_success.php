<style>
    .cod-success-container {
        max-width: 700px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .cod-success-card {
        background: white;
        border-radius: 16px;
        padding: 50px 40px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .cod-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 25px;
        background: linear-gradient(135deg, #28a745 0%, #34ce57 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 50px;
    }

    .cod-title {
        font-size: 32px;
        color: #28a745;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .cod-subtitle {
        font-size: 16px;
        color: #666;
        margin-bottom: 30px;
    }

    .order-timeline {
        background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 30px;
        margin: 30px 0;
        text-align: left;
    }

    .timeline-item {
        position: relative;
        padding-left: 40px;
        padding-bottom: 25px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 30px;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-dot {
        position: absolute;
        left: 0;
        top: 5px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: white;
        border: 3px solid #28a745;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .timeline-dot.pending {
        border-color: #e0e0e0;
        color: #999;
    }

    .timeline-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .timeline-desc {
        font-size: 13px;
        color: #666;
    }

    .payment-note {
        background: #fff9e6;
        border-left: 4px solid #ffc107;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
        text-align: left;
    }

    .payment-note-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        font-size: 15px;
    }

    .payment-note-text {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
    }

    .order-info-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 25px;
        margin: 30px 0;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #666;
        font-size: 14px;
    }

    .info-value {
        color: #333;
        font-weight: 600;
        font-size: 14px;
    }

    .info-value.highlight {
        color: #ff6b00;
        font-size: 20px;
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

    .btn-secondary-action {
        background: white;
        color: #0033a0;
        border: 2px solid #0033a0;
    }

    .btn-secondary-action:hover {
        background: #0033a0;
        color: white;
    }

    @media (max-width: 768px) {
        .cod-success-card {
            padding: 40px 20px;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="cod-success-container">
    <div class="cod-success-card">
        <div class="cod-icon">💵</div>

        <h1 class="cod-title">✅ Đặt hàng thành công!</h1>
        <p class="cod-subtitle">
            Cảm ơn bạn đã đặt hàng. Vui lòng chuẩn bị tiền mặt khi nhận hàng
        </p>

        <div class="order-info-box">
            <div class="info-row">
                <span class="info-label">📦 Mã đơn hàng:</span>
                <span class="info-value">#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></span>
            </div>

            <div class="info-row">
                <span class="info-label">💳 Phương thức thanh toán:</span>
                <span class="info-value">Thanh toán khi nhận hàng (COD)</span>
            </div>

            <div class="info-row">
                <span class="info-label">📍 Địa chỉ giao hàng:</span>
                <span class="info-value"><?php echo htmlspecialchars($order['shipping_address']); ?></span>
            </div>

            <div class="info-row">
                <span class="info-label">💰 Số tiền cần thanh toán:</span>
                <span class="info-value highlight"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>₫</span>
            </div>
        </div>

        <div class="payment-note">
            <div class="payment-note-title">💡 Lưu ý quan trọng:</div>
            <div class="payment-note-text">
                • Vui lòng chuẩn bị <strong>đúng số tiền mặt</strong> khi nhận hàng<br>
                • Kiểm tra kỹ sản phẩm trước khi thanh toán<br>
                • Yêu cầu nhân viên giao hàng xuất hóa đơn VAT (nếu cần)<br>
                • Đơn hàng sẽ được giao trong <strong>2-3 ngày làm việc</strong>
            </div>
        </div>

        <div class="order-timeline">
            <div class="timeline-item">
                <div class="timeline-dot">✓</div>
                <div class="timeline-title">Đơn hàng đã được tiếp nhận</div>
                <div class="timeline-desc">Ngay lập tức</div>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot pending">⏰</div>
                <div class="timeline-title">Đang xử lý đơn hàng</div>
                <div class="timeline-desc">Trong vòng 2-4 giờ</div>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot pending">📦</div>
                <div class="timeline-title">Đang giao hàng</div>
                <div class="timeline-desc">1-2 ngày làm việc</div>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot pending">💵</div>
                <div class="timeline-title">Giao hàng & Thanh toán</div>
                <div class="timeline-desc">Thanh toán tiền mặt khi nhận hàng</div>
            </div>
        </div>

        <div class="action-buttons">
            <a href="index.php?controller=order&action=detail&id=<?php echo $order['id']; ?>" class="btn-action btn-primary-action">
                📋 Xem chi tiết đơn hàng
            </a>
            <a href="index.php" class="btn-action btn-secondary-action">
                🛍️ Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>