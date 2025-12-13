<style>
    .orders-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .orders-title {
        font-size: 32px;
        color: #0033a0;
        margin: 0;
    }

    .filter-controls {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 10px 20px;
        background: white;
        border: 2px solid #ddd;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: #0033a0;
        color: white;
        border-color: #0033a0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-value {
        font-size: 32px;
        font-weight: bold;
        color: #0033a0;
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
    }

    .orders-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .order-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .order-header {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 20px;
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
        font-size: 13px;
        color: #666;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
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
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 20px;
        margin-bottom: 15px;
    }

    .customer-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .info-label {
        font-size: 12px;
        color: #999;
        font-weight: 600;
        text-transform: uppercase;
    }

    .info-value {
        font-size: 14px;
        color: #333;
        font-weight: 500;
    }

    .order-amount {
        background: #fff5e6;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
    }

    .amount-label {
        font-size: 12px;
        color: #666;
    }

    .amount-value {
        font-size: 24px;
        font-weight: bold;
        color: #ff6b00;
    }

    .order-footer {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
    }

    .btn-view-detail {
        padding: 10px 20px;
        background: #0033a0;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-view-detail:hover {
        background: #002780;
    }

    .btn-change-status {
        padding: 10px 20px;
        background: #ffc107;
        color: #333;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-change-status:hover {
        background: #e0a800;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 30px;
    }

    .page-link {
        padding: 10px 15px;
        background: white;
        border: 2px solid #ddd;
        border-radius: 6px;
        text-decoration: none;
        color: #0033a0;
        font-weight: 600;
        transition: all 0.3s;
    }

    .page-link:hover {
        background: #0033a0;
        color: white;
        border-color: #0033a0;
    }

    .page-link.active {
        background: #0033a0;
        color: white;
        border-color: #0033a0;
    }
</style>
<br> <br> <br><br> <br> <br>

<div class="orders-container">
    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?php echo number_format($totalOrders ?? 0); ?></div>
            <div class="stat-label">Tổng đơn hàng</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo isset($stats) ? $stats['pending_orders'] : 0; ?></div>
            <div class="stat-label">Chờ xử lý</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo isset($stats) ? $stats['processing_orders'] : 0; ?></div>
            <div class="stat-label">Đang xử lý</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo isset($stats) ? $stats['completed_orders'] : 0; ?></div>
            <div class="stat-label">Hoàn thành</div>
        </div>
    </div>

    <!-- Header -->
    <div class="orders-header">
        <h1 class="orders-title">📦 Quản lý đơn hàng</h1>
        <div class="filter-controls">
            <a href="index.php?controller=admin&action=orders&status=" class="filter-btn">Tất cả</a>
            <a href="index.php?controller=admin&action=orders&status=pending" class="filter-btn">⏳ Chờ xử lý</a>
            <a href="index.php?controller=admin&action=orders&status=processing" class="filter-btn">📦 Đang xử lý</a>
            <a href="index.php?controller=admin&action=orders&status=completed" class="filter-btn">✅ Hoàn thành</a>
            <a href="index.php?controller=admin&action=orders&status=cancelled" class="filter-btn">❌ Đã hủy</a>
        </div>
    </div>

    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <p style="font-size: 18px; color: #666;">Không có đơn hàng nào</p>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-id">Đơn hàng #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></div>
                            <div class="order-date"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></div>
                        </div>
                        <div></div>
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
                        <div></div>
                    </div>

                    <div class="order-body">
                        <div class="customer-info">
                            <span class="info-label">👤 Khách hàng</span>
                            <span class="info-value"><?php echo htmlspecialchars($order['full_name'] ?? 'N/A'); ?></span>
                            <span class="info-label" style="margin-top: 10px;">📞 Điện thoại</span>
                            <span class="info-value"><?php echo htmlspecialchars($order['user_phone'] ?? 'N/A'); ?></span>
                            <span class="info-label" style="margin-top: 10px;">📧 Email</span>
                            <span class="info-value"><?php echo htmlspecialchars($order['email'] ?? 'N/A'); ?></span>
                        </div>

                        <div class="customer-info">
                            <span class="info-label">📍 Địa chỉ giao</span>
                            <span class="info-value"><?php echo htmlspecialchars($order['shipping_address'] ?? 'N/A'); ?></span>
                            <span class="info-label" style="margin-top: 10px;">👤 Người nhận</span>
                            <span class="info-value"><?php echo htmlspecialchars($order['shipping_name'] ?? 'N/A'); ?></span>
                            <span class="info-label" style="margin-top: 10px;">📞 SĐT nhận</span>
                            <span class="info-value"><?php echo htmlspecialchars($order['shipping_phone'] ?? 'N/A'); ?></span>
                        </div>

                        <div class="order-amount">
                            <div class="amount-label">Tổng tiền</div>
                            <div class="amount-value">
                                <?php echo number_format($order['total_amount'], 0, ',', '.'); ?> ₫
                            </div>
                        </div>
                    </div>

                    <div class="order-footer">
                        <a href="index.php?controller=admin&action=orderDetail&id=<?php echo $order['id']; ?>"
                            class="btn-view-detail">
                            👁️ Xem chi tiết
                        </a>
                        <?php if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): ?>
                            <button class="btn-change-status"
                                onclick="location.href='index.php?controller=admin&action=orderDetail&id=<?php echo $order['id']; ?>#status'">
                                ⚙️ Thay đổi trạng thái
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="index.php?controller=admin&action=orders&page=<?php echo $i; ?>"
                        class="page-link <?php echo ($i == ($currentPage ?? 1)) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>