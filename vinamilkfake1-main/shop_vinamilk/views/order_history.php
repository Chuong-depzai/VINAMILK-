<style>
    .order-history-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .order-history-header {
        margin-bottom: 30px;
    }

    .order-history-title {
        font-size: 28px;
        color: #0033a0;
        margin-bottom: 10px;
    }

    .order-filters {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 10px 20px;
        background: white;
        border: 2px solid #ddd;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        color: #666;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: #0033a0;
        color: white;
        border-color: #0033a0;
    }

    .order-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .order-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 15px;
    }

    .order-id {
        font-size: 18px;
        font-weight: bold;
        color: #0033a0;
    }

    .order-date {
        font-size: 14px;
        color: #666;
    }

    .order-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
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

    .order-body {
        margin: 15px 0;
    }

    .order-info-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 14px;
    }

    .order-info-label {
        color: #666;
        font-weight: 500;
    }

    .order-info-value {
        color: #333;
        font-weight: 600;
    }

    .order-total {
        font-size: 18px;
        color: #ff6b00;
        font-weight: bold;
    }

    .order-footer {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
    }

    .btn-order-detail {
        flex: 1;
        padding: 10px 20px;
        background: #0033a0;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        text-align: center;
        font-weight: 600;
        transition: background 0.3s ease;
    }

    .btn-order-detail:hover {
        background: #002780;
    }

    .btn-cancel-order {
        padding: 10px 20px;
        background: #dc3545;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        text-align: center;
        font-weight: 600;
        transition: background 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-cancel-order:hover {
        background: #c82333;
    }

    .empty-orders {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .empty-orders-icon {
        font-size: 64px;
        margin-bottom: 20px;
    }

    .empty-orders-text {
        font-size: 18px;
        color: #666;
        margin-bottom: 30px;
    }
</style>

<div class="order-history-container">
    <div class="order-history-header">
        <h1 class="order-history-title">📦 Lịch sử đơn hàng</h1>
    </div>

    <div class="order-filters">
        <button class="filter-btn active" onclick="filterOrders('all')">Tất cả</button>
        <button class="filter-btn" onclick="filterOrders('pending')">Chờ xử lý</button>
        <button class="filter-btn" onclick="filterOrders('processing')">Đang xử lý</button>
        <button class="filter-btn" onclick="filterOrders('completed')">Hoàn thành</button>
        <button class="filter-btn" onclick="filterOrders('cancelled')">Đã hủy</button>
    </div>

    <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <div class="empty-orders-icon">📭</div>
            <p class="empty-orders-text">Bạn chưa có đơn hàng nào</p>
            <a href="index.php" class="btn-primary">Bắt đầu mua sắm</a>
        </div>
    <?php else: ?>
        <div class="order-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card" data-status="<?php echo $order['status']; ?>">
                    <div class="order-header">
                        <div>
                            <div class="order-id">Đơn hàng #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></div>
                            <div class="order-date"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></div>
                        </div>
                        <div class="order-status status-<?php echo $order['status']; ?>">
                            <?php
                            $statusText = [
                                'pending' => 'Chờ xử lý',
                                'processing' => 'Đang xử lý',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã hủy'
                            ];
                            echo $statusText[$order['status']] ?? 'Không xác định';
                            ?>
                        </div>
                    </div>

                    <div class="order-body">
                        <div class="order-info-row">
                            <span class="order-info-label">Người nhận:</span>
                            <span class="order-info-value"><?php echo htmlspecialchars($order['shipping_name'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="order-info-row">
                            <span class="order-info-label">Số điện thoại:</span>
                            <span class="order-info-value"><?php echo htmlspecialchars($order['shipping_phone'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="order-info-row">
                            <span class="order-info-label">Địa chỉ:</span>
                            <span class="order-info-value"><?php echo htmlspecialchars($order['shipping_address'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="order-info-row">
                            <span class="order-info-label">Tổng tiền:</span>
                            <span class="order-total"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VNĐ</span>
                        </div>
                    </div>

                    <div class="order-footer">
                        <a href="index.php?controller=order&action=detail&id=<?php echo $order['id']; ?>"
                            class="btn-order-detail">
                            Xem chi tiết
                        </a>
                        <?php if ($order['status'] == 'pending'): ?>
                            <a href="index.php?controller=order&action=cancel&id=<?php echo $order['id']; ?>"
                                class="btn-cancel-order"
                                onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                Hủy đơn
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function filterOrders(status) {
        const cards = document.querySelectorAll('.order-card');
        const buttons = document.querySelectorAll('.filter-btn');

        buttons.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        cards.forEach(card => {
            if (status === 'all' || card.dataset.status === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>