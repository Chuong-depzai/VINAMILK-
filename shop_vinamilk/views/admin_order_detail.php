<style>
    .order-detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .detail-title {
        font-size: 28px;
        color: #0033a0;
        margin: 0;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }

    .detail-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 20px;
        color: #0033a0;
        margin-bottom: 20px;
        font-weight: 700;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .info-row {
        display: grid;
        grid-template-columns: 150px 1fr;
        gap: 20px;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #666;
        font-size: 14px;
    }

    .info-value {
        color: #333;
        font-size: 14px;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
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

    .order-items {
        margin-top: 20px;
    }

    .item-row {
        display: grid;
        grid-template-columns: 100px 2fr 100px 100px 100px;
        gap: 15px;
        align-items: center;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .item-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }

    .item-name {
        font-weight: 600;
        color: #333;
    }

    .item-price {
        text-align: center;
        color: #0033a0;
    }

    .item-quantity {
        text-align: center;
        font-weight: 600;
    }

    .item-total {
        text-align: center;
        color: #ff6b00;
        font-weight: 700;
    }

    .summary-box {
        background: linear-gradient(135deg, #f5f8ff 0%, #e6f0ff 100%);
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #0033a0;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 14px;
    }

    .summary-total {
        padding-top: 15px;
        border-top: 2px solid #0033a0;
        font-size: 18px;
        font-weight: bold;
        color: #0033a0;
    }

    .status-change-form {
        background: #fff5e6;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #ff6b00;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 15px;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .form-select {
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .form-textarea {
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        resize: vertical;
        font-family: inherit;
    }

    .btn-update-status {
        padding: 12px 24px;
        background: #ff6b00;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-update-status:hover {
        background: #e55d00;
    }

    .btn-back {
        padding: 12px 24px;
        background: #0033a0;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        margin-top: 20px;
    }

    .btn-back:hover {
        background: #002780;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        position: relative;
        padding: 15px 0;
        border-left: 2px solid #e0e0e0;
        margin-left: -16px;
    }

    .timeline-item:first-child {
        border-left-color: #28a745;
    }

    .timeline-dot {
        position: absolute;
        left: -14px;
        top: 18px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #e0e0e0;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #e0e0e0;
    }

    .timeline-item:first-child .timeline-dot {
        background: #28a745;
        box-shadow: 0 0 0 2px #28a745;
    }

    .timeline-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .timeline-time {
        font-size: 12px;
        color: #999;
    }

    .timeline-note {
        font-size: 13px;
        color: #666;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .item-row {
            grid-template-columns: 1fr;
        }

        .item-image {
            width: 100%;
            height: 150px;
        }
    }
</style>
<br><br>

<div class="order-detail-container">
    <!-- Header -->
    <div class="detail-header">
        <div>
            <h1 class="detail-title">
                Đơn hàng #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?>
            </h1>
            <p style="color: #666; margin: 10px 0; font-size: 14px;">
                Ngày đặt: <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
            </p>
        </div>
        <span class="status-badge status-<?php echo $order['status']; ?>">
            <?php
            $statusText = [
                'pending' => '⏳ Chờ xử lý',
                'processing' => '📦 Đang xử lý',
                'completed' => '✅ Hoàn thành',
                'cancelled' => '❌ Đã hủy'
            ];
            echo $statusText[$order['status']] ?? 'N/A';
            ?>
        </span>
    </div>

    <!-- Main Content -->
    <div class="detail-grid">
        <div>
            <!-- Customer Info -->
            <div class="detail-section">
                <h3 class="section-title">👤 Thông tin khách hàng</h3>

                <div class="info-row">
                    <span class="info-label">Tên khách</span>
                    <span class="info-value"><?php echo htmlspecialchars($order['full_name'] ?? 'N/A'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Điện thoại</span>
                    <span class="info-value"><?php echo htmlspecialchars($order['user_phone'] ?? 'N/A'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?php echo htmlspecialchars($order['email'] ?? 'N/A'); ?></span>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="detail-section">
                <h3 class="section-title">📦 Thông tin giao hàng</h3>

                <div class="info-row">
                    <span class="info-label">Người nhận</span>
                    <span class="info-value"><?php echo htmlspecialchars($order['shipping_name'] ?? 'N/A'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Điện thoại</span>
                    <span class="info-value"><?php echo htmlspecialchars($order['shipping_phone'] ?? 'N/A'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Địa chỉ</span>
                    <span class="info-value"><?php echo htmlspecialchars($order['shipping_address'] ?? 'N/A'); ?></span>
                </div>
                <?php if (!empty($order['notes'])): ?>
                    <div class="info-row">
                        <span class="info-label">Ghi chú</span>
                        <span class="info-value"><?php echo htmlspecialchars($order['notes']); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Order Items -->
            <div class="detail-section">
                <h3 class="section-title">📋 Sản phẩm đã đặt</h3>

                <div class="order-items">
                    <div style="display: grid; grid-template-columns: 100px 2fr 100px 100px 100px; gap: 15px; padding: 15px; background: #f8f9fa; font-weight: 600; border-radius: 8px; margin-bottom: 10px; font-size: 13px;">
                        <div>Ảnh</div>
                        <div>Tên sản phẩm</div>
                        <div style="text-align: center;">Đơn giá</div>
                        <div style="text-align: center;">Số lượng</div>
                        <div style="text-align: center;">Thành tiền</div>
                    </div>

                    <?php foreach ($orderItems as $item): ?>
                        <div class="item-row">
                            <div>
                                <?php
                                require_once __DIR__ . '/../models/Product.php';
                                $productModel = new Product();
                                $product = $productModel->getById($item['product_id']);

                                if ($product && !empty($product['image']) && file_exists(__DIR__ . '/../uploads/' . $product['image'])):
                                ?>
                                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                        alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                        class="item-image">
                                <?php else: ?>
                                    <div class="item-image" style="background: #e0e0e0; display: flex; align-items: center; justify-content: center; color: #999;">N/A</div>
                                <?php endif; ?>
                            </div>
                            <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                            <div class="item-price"><?php echo number_format($item['product_price'], 0, ',', '.'); ?> ₫</div>
                            <div class="item-quantity">x<?php echo $item['quantity']; ?></div>
                            <div class="item-total"><?php echo number_format($item['subtotal'], 0, ',', '.'); ?> ₫</div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Summary -->
                <div class="summary-box" style="margin-top: 20px;">
                    <div class="summary-row">
                        <span>Tạm tính:</span>
                        <span><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> ₫</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển:</span>
                        <span>Miễn phí</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Tổng cộng:</span>
                        <span><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> ₫</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Status Update Form -->
            <?php if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): ?>
                <div class="detail-section">
                    <h3 class="section-title">⚙️ Cập nhật trạng thái</h3>

                    <form method="POST" action="index.php?controller=admin&action=updateOrderStatus" class="status-change-form">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

                        <div class="form-group">
                            <label class="form-label">Trạng thái mới:</label>
                            <select name="status" class="form-select" required>
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>⏳ Chờ xử lý</option>
                                <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>📦 Đang xử lý</option>
                                <option value="completed">✅ Hoàn thành</option>
                                <option value="cancelled">❌ Đã hủy</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ghi chú (tùy chọn):</label>
                            <textarea name="note" class="form-textarea" rows="3" placeholder="Ghi chú về đơn hàng..."></textarea>
                        </div>

                        <button type="submit" class="btn-update-status">💾 Cập nhật trạng thái</button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Status History -->
            <div class="detail-section">
                <h3 class="section-title">📝 Lịch sử cập nhật</h3>

                <div class="timeline">
                    <?php if (!empty($statusHistory)): ?>
                        <?php foreach ($statusHistory as $history): ?>
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-title">
                                    <?php
                                    $statusMap = [
                                        'pending' => '⏳ Chờ xử lý',
                                        'processing' => '📦 Đang xử lý',
                                        'completed' => '✅ Hoàn thành',
                                        'cancelled' => '❌ Đã hủy'
                                    ];
                                    echo $statusMap[$history['new_status']] ?? $history['new_status'];
                                    ?>
                                </div>
                                <div class="timeline-time">
                                    <?php echo date('d/m/Y H:i', strtotime($history['created_at'])); ?>
                                    bởi <?php echo htmlspecialchars($history['changed_by_name']); ?>
                                </div>
                                <?php if (!empty($history['note'])): ?>
                                    <div class="timeline-note">📌 <?php echo htmlspecialchars($history['note']); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #999; font-size: 14px;">Chưa có cập nhật nào</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <a href="index.php?controller=admin&action=orders" class="btn-back">← Quay lại danh sách</a>
</div>