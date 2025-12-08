<style>
    .admin-dashboard {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .admin-header {
        margin-bottom: 40px;
    }

    .admin-title {
        font-size: 32px;
        color: #0033a0;
        margin-bottom: 10px;
    }

    .admin-subtitle {
        font-size: 16px;
        color: #666;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        font-size: 36px;
        margin-bottom: 15px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: bold;
        color: #0033a0;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
        font-weight: 500;
    }

    .stat-trend {
        font-size: 12px;
        margin-top: 10px;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
    }

    .trend-up {
        background: #d4edda;
        color: #155724;
    }

    .trend-down {
        background: #f8d7da;
        color: #721c24;
    }

    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 20px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        text-decoration: none;
        color: #333;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        border-color: #0033a0;
        background: #f5f8ff;
        transform: translateX(5px);
    }

    .action-icon {
        font-size: 24px;
    }

    /* Recent Orders & Top Products */
    .admin-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    .admin-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .section-title {
        font-size: 20px;
        color: #0033a0;
        font-weight: bold;
    }

    .section-link {
        font-size: 14px;
        color: #0033a0;
        text-decoration: none;
        font-weight: 600;
    }

    .section-link:hover {
        text-decoration: underline;
    }

    /* Orders Table */
    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table th {
        text-align: left;
        padding: 12px;
        background: #f9f9f9;
        font-size: 13px;
        font-weight: 600;
        color: #666;
        border-bottom: 2px solid #e0e0e0;
    }

    .orders-table td {
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
    }

    .orders-table tr:hover {
        background: #f9f9f9;
    }

    .order-status {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
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

    /* Top Products */
    .product-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .product-item {
        display: flex;
        gap: 15px;
        align-items: center;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        transition: background 0.3s ease;
    }

    .product-item:hover {
        background: #f0f0f0;
    }

    .product-rank {
        font-size: 24px;
        font-weight: bold;
        color: #0033a0;
        min-width: 35px;
    }

    .product-image {
        width: 50px;
        height: 50px;
        border-radius: 6px;
        object-fit: cover;
    }

    .product-info {
        flex: 1;
    }

    .product-name {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .product-sales {
        font-size: 12px;
        color: #666;
    }

    @media (max-width: 1024px) {
        .admin-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .quick-actions {
            grid-template-columns: 1fr;
        }

        .orders-table {
            font-size: 12px;
        }

        .orders-table th,
        .orders-table td {
            padding: 8px;
        }
    }
</style>

<div class="admin-dashboard">
    <!-- Header -->
    <div class="admin-header">
        <h1 class="admin-title">Admin Dashboard</h1>
        <p class="admin-subtitle">Xin chào, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! Đây là trang quản trị.</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-value"><?php echo number_format($stats['total_users']); ?></div>
            <div class="stat-label">Tổng người dùng</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-value"><?php echo number_format($stats['total_orders']); ?></div>
            <div class="stat-label">Tổng đơn hàng</div>
            <div class="stat-trend trend-up">⏳ <?php echo $stats['pending_orders']; ?> chờ xử lý</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🛍️</div>
            <div class="stat-value"><?php echo number_format($stats['total_products']); ?></div>
            <div class="stat-label">Tổng sản phẩm</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-value"><?php echo number_format($stats['total_revenue'], 0, ',', '.'); ?> ₫</div>
            <div class="stat-label">Tổng doanh thu</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="index.php?controller=admin&action=orders" class="action-btn">
            <span class="action-icon">📦</span>
            <span>Quản lý đơn hàng</span>
        </a>

        <a href="index.php?controller=product&action=admin" class="action-btn">
            <span class="action-icon">🛍️</span>
            <span>Quản lý sản phẩm</span>
        </a>

        <a href="index.php?controller=admin&action=users" class="action-btn">
            <span class="action-icon">👥</span>
            <span>Quản lý người dùng</span>
        </a>

        <a href="index.php?controller=admin&action=reports" class="action-btn">
            <span class="action-icon">📊</span>
            <span>Báo cáo thống kê</span>
        </a>
    </div>

    <!-- Main Content -->
    <div class="admin-content">
        <!-- Recent Orders -->
        <div class="admin-section">
            <div class="section-header">
                <h2 class="section-title">Đơn hàng gần đây</h2>
                <a href="index.php?controller=admin&action=orders" class="section-link">Xem tất cả →</a>
            </div>

            <?php if (empty($recentOrders)): ?>
                <p style="color: #999; text-align: center; padding: 20px;">Chưa có đơn hàng nào</p>
            <?php else: ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Khách hàng</th>
                            <th>Số tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr onclick="window.location='index.php?controller=admin&action=orderDetail&id=<?php echo $order['id']; ?>'" style="cursor: pointer;">
                                <td><strong>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                                <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                                <td><strong><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> ₫</strong></td>
                                <td>
                                    <span class="order-status status-<?php echo $order['status']; ?>">
                                        <?php
                                        $statusText = [
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                        echo $statusText[$order['status']] ?? '';
                                        ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Top Products -->
        <div class="admin-section">
            <div class="section-header">
                <h2 class="section-title">Sản phẩm bán chạy</h2>
                <a href="index.php?controller=admin&action=reports?type=products" class="section-link">Xem chi tiết →</a>
            </div>

            <?php if (empty($topProducts)): ?>
                <p style="color: #999; text-align: center; padding: 20px;">Chưa có dữ liệu</p>
            <?php else: ?>
                <div class="product-list">
                    <?php $rank = 1;
                    foreach ($topProducts as $product): ?>
                        <div class="product-item">
                            <div class="product-rank">#<?php echo $rank++; ?></div>

                            <?php if (!empty($product['image']) && file_exists(__DIR__ . '/../uploads/' . $product['image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                                    class="product-image">
                            <?php else: ?>
                                <div class="product-image" style="background: #e0e0e0; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #999;">N/A</div>
                            <?php endif; ?>

                            <div class="product-info">
                                <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                                <div class="product-sales">Đã bán: <?php echo $product['total_sold'] ?? 0; ?> sản phẩm</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>