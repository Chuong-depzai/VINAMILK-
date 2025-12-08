<style>
    .order-detail-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .order-detail-header {
        background: white;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .order-detail-title {
        font-size: 28px;
        color: #0033a0;
        margin-bottom: 10px;
    }

    .order-status-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-processing {
        background: #cce5ff;
        color: #004085;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .order-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .info-label {
        font-size: 13px;
        color: #666;
        font-weight: 500;
    }

    .info-value {
        font-size: 16px;
        color: #333;
        font-weight: 600;
    }

    .order-items-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 20px;
        color: #0033a0;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .order-item-row {
        display: grid;
        grid-template-columns: 80px 2fr 1fr 1fr 1fr;
        gap: 20px;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .order-item-row:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        background: #f9f9f9;
    }

    .item-name {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .item-price,
    .item-quantity,
    .item-subtotal {
        font-size: 15px;
        color: #666;
        text-align: center;
    }

    .item-subtotal {
        font-weight: 700;
        color: #ff6b00;
    }

    .order-summary {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        font-size: 15px;
        color: #666;
    }

    .summary-row.total {
        border-top: 2px solid #0033a0;
        margin-top: 15px;
        padding-top: 20px;
    }

    .summary-label {
        font-weight: 500;
    }

    .summary-value {
        font-weight: 700;
        color: #333;
    }

    .summary-row.total .summary-value {
        font-size: 24px;
        color: #ff6b00;
    }

    .order-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-back-orders {
        flex: 1;
        padding: 15px 30px;
        background: #0033a0;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-back-orders:hover {
        background: #002780;
    }

    .btn-cancel-order-detail {
        padding: 15px 30px;
        background: #dc3545;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-cancel-order-detail:hover {
        background: #c82333;
    }

    @media (max-width: 768px) {
        .order-info-grid {
            grid-template-columns: 1fr;
        }

        .order-item-row {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .order-actions {
            flex-direction: column;
        }
    }
</style>

<div class="order-detail-container">
    <!-- Header -->
    <div class="order-detail-header">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 class="order-detail-title">
                Chi tiết đơn hàng #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?>
            </h1>
            <span class="order-status-badge status-<?php echo $order['status']; ?>">
                <?php
                $statusText = [
                    'pending' => '⏳ Chờ xử lý',
                    'processing' => '📦 Đang xử lý',
                    'completed' => '✅ Hoàn thành',
                    'cancelled' => '❌ Đã hủy'
                ];
                echo $statusText[$order['status']] ?? 'Không xác định';
                ?>
            </span>
        </div>

        <div class="order-info-grid">
            <div class="info-item">
                <span class="info-label">Ngày đặt hàng</span>
                <span class="info-value"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
            </div>

            <div class="info-item">
                <span class="info-label">Người nhận</span>
                <span class="info-value"><?php echo htmlspecialchars($order['shipping_name'] ?? 'N/A'); ?></span>
            </div>

            <div class="info-item">
                <span class="info-label">Số điện thoại</span>
                <span class="info-value"><?php echo htmlspecialchars($order['shipping_phone'] ?? 'N/A'); ?></span>
            </div>

            <div class="info-item">
                <span class="info-label">Địa chỉ giao hàng</span>
                <span class="info-value"><?php echo htmlspecialchars($order['shipping_address'] ?? 'N/A'); ?></span>
            </div>

            <div class="info-item">
                <span class="info-label">Phương thức thanh toán</span>
                <span class="info-value"><?php echo htmlspecialchars($order['payment_method'] ?? 'COD'); ?></span>
            </div>

            <?php if (!empty($order['notes'])): ?>
                <div class="info-item">
                    <span class="info-label">Ghi chú</span>
                    <span class="info-value"><?php echo htmlspecialchars($order['notes']); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Order Items -->
    <div class="order-items-section">
        <h2 class="section-title">Sản phẩm đã đặt</h2>

        <div>
            <!-- Header row -->
            <div class="order-item-row" style="background: #f9f9f9; font-weight: 600; color: #666;">
                <div>Hình ảnh</div>
                <div>Tên sản phẩm</div>
                <div style="text-align: center;">Đơn giá</div>
                <div style="text-align: center;">Số lượng</div>
                <div style="text-align: center;">Thành tiền</div>
            </div>

            <!-- Items -->
            <?php foreach ($orderItems as $item): ?>
                <div class="order-item-row">
                    <div>
                        <?php
                        // Tìm sản phẩm để lấy ảnh
                        require_once __DIR__ . '/../models/Product.php';
                        $productModel = new Product();
                        $product = $productModel->getById($item['product_id']);

                        if ($product && !empty($product['image']) && file_exists(__DIR__ . '/../uploads/' . $product['image'])):
                        ?>
                            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                class="item-image">
                        <?php else: ?>
                            <div class="item-image" style="display: flex; align-items: center; justify-content: center; background: #e0e0e0; color: #999; font-size: 12px;">
                                N/A
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                    </div>

                    <div class="item-price">
                        <?php echo number_format($item['product_price'], 0, ',', '.'); ?> ₫
                    </div>

                    <div class="item-quantity">
                        x<?php echo $item['quantity']; ?>
                    </div>

                    <div class="item-subtotal">
                        <?php echo number_format($item['subtotal'], 0, ',', '.'); ?> ₫
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="order-summary">
        <h2 class="section-title">Tổng kết đơn hàng</h2>

        <div class="summary-row">
            <span class="summary-label">Tạm tính</span>
            <span class="summary-value"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> ₫</span>
        </div>

        <div class="summary-row">
            <span class="summary-label">Phí vận chuyển</span>
            <span class="summary-value">Miễn phí</span>
        </div>

        <div class="summary-row total">
            <span class="summary-label" style="font-size: 18px;">Tổng cộng</span>
            <span class="summary-value"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> ₫</span>
        </div>
    </div>

    <!-- Actions -->
    <div class="order-actions">
        <a href="index.php?controller=order&action=history" class="btn-back-orders">
            ← Quay lại danh sách đơn hàng
        </a>

        <?php if ($order['status'] == 'pending'): ?>
            <a href="index.php?controller=order&action=cancel&id=<?php echo $order['id']; ?>"
                class="btn-cancel-order-detail"
                onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                Hủy đơn hàng
            </a>
        <?php endif; ?>
    </div>
</div>