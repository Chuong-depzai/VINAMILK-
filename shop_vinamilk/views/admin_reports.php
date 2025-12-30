<style>
    .reports-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .reports-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .reports-title {
        font-size: 32px;
        color: #0033a0;
        margin: 0;
    }

    .report-controls {
        display: flex;
        gap: 15px;
    }

    .control-select {
        padding: 10px 15px;
        border: 2px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .btn-export {
        padding: 10px 20px;
        background: #28a745;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-export:hover {
        background: #218838;
    }

    .btn-export-pdf {
        background: #dc3545;
    }

    .btn-export-pdf:hover {
        background: #c82333;
    }

    .stats-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
        margin-bottom: 10px;
    }

    .stat-change {
        font-size: 12px;
        color: #28a745;
        font-weight: 600;
    }

    .report-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 24px;
        color: #0033a0;
        margin-bottom: 25px;
        font-weight: 700;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .chart-container {
        height: 400px;
        margin-bottom: 30px;
        position: relative;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .data-table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #0033a0;
    }

    .data-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #0033a0;
        font-size: 14px;
    }

    .data-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }

    .data-table tbody tr:hover {
        background: #f5f8ff;
    }

    .data-highlight {
        color: #ff6b00;
        font-weight: 700;
    }

    .period-info {
        background: #e6f0ff;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #0033a0;
    }

    .period-text {
        font-size: 14px;
        color: #0033a0;
        font-weight: 500;
    }

    .summary-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }

    .summary-label {
        font-weight: 600;
        color: #333;
    }

    .summary-value {
        text-align: right;
        color: #0033a0;
        font-weight: 700;
    }

    .export-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    @media (max-width: 768px) {
        .report-controls {
            flex-direction: column;
        }

        .control-select {
            width: 100%;
        }

        .chart-container {
            height: 300px;
        }

        .data-table {
            font-size: 12px;
        }

        .data-table th,
        .data-table td {
            padding: 10px;
        }
    }
</style>
<br><br>
<br><br>
<div class="reports-container">
    <div class="reports-header">
        <h1 class="reports-title">📊 Báo cáo thống kê</h1>
        <div class="report-controls">
            <select id="reportType" class="control-select" onchange="changeReport()">
                <option value="daily">📅 Doanh thu hàng ngày (30 ngày)</option>
                <option value="monthly">📈 Doanh thu hàng tháng (12 tháng)</option>
                <option value="products">🛍️ Thống kê sản phẩm</option>
            </select>
            <button class="btn-export" onclick="exportExcel()">📥 Export Excel</button>
            <button class="btn-export btn-export-pdf" onclick="exportPDF()">📄 Export PDF</button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-overview">
        <div class="stat-card">
            <div class="stat-value"><?php echo number_format($stats['total_revenue'] ?? 0, 0, ',', '.'); ?> ₫</div>
            <div class="stat-label">Tổng doanh thu</div>
            <div class="stat-change">💹 +12.5% so với tháng trước</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $stats['total_orders'] ?? 0; ?></div>
            <div class="stat-label">Tổng đơn hàng</div>
            <div class="stat-change">📦 <?php echo $stats['completed_orders'] ?? 0; ?> hoàn thành</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $stats['total_products'] ?? 0; ?></div>
            <div class="stat-label">Tổng sản phẩm</div>
            <div class="stat-change">🛍️ Đang bán</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $stats['total_users'] ?? 0; ?></div>
            <div class="stat-label">Tổng khách hàng</div>
            <div class="stat-change">👥 Tăng 5% tuần này</div>
        </div>
    </div>

    <!-- Report Content -->
    <div id="reportContent">
        <?php
        $type = isset($_GET['type']) ? $_GET['type'] : 'daily';

        switch ($type) {
            case 'daily':
        ?>
                <div class="report-section">
                    <h2 class="section-title">📅 Doanh thu hàng ngày (30 ngày gần nhất)</h2>

                    <div class="period-info">
                        <span class="period-text">📊 Từ <?php echo date('d/m/Y', strtotime('-30 days')); ?> đến <?php echo date('d/m/Y'); ?></span>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <canvas id="dailyChart"></canvas>
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Số đơn hàng</th>
                                <th>Doanh thu</th>
                                <th>Trung bình/đơn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalDaily = 0;
                            $totalOrdersDaily = 0;
                            if (!empty($data)):
                                foreach ($data as $row):
                                    $avgPerOrder = $row['order_count'] > 0 ? $row['revenue'] / $row['order_count'] : 0;
                                    $totalDaily += $row['revenue'];
                                    $totalOrdersDaily += $row['order_count'];
                            ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($row['date'])); ?></td>
                                        <td><?php echo $row['order_count']; ?></td>
                                        <td class="data-highlight"><?php echo number_format($row['revenue'], 0, ',', '.'); ?> ₫</td>
                                        <td><?php echo number_format($avgPerOrder, 0, ',', '.'); ?> ₫</td>
                                    </tr>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                        <tfoot style="background: #f8f9fa; font-weight: 700;">
                            <tr>
                                <td colspan="2">Cộng</td>
                                <td class="data-highlight"><?php echo number_format($totalDaily, 0, ',', '.'); ?> ₫</td>
                                <td><?php echo number_format($totalOrdersDaily > 0 ? $totalDaily / $totalOrdersDaily : 0, 0, ',', '.'); ?> ₫</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php
                break;

            case 'monthly':
            ?>
                <div class="report-section">
                    <h2 class="section-title">📈 Doanh thu hàng tháng (12 tháng gần nhất)</h2>

                    <div class="period-info">
                        <span class="period-text">📊 Từ <?php echo date('m/Y', strtotime('-12 months')); ?> đến <?php echo date('m/Y'); ?></span>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <canvas id="monthlyChart"></canvas>
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tháng</th>
                                <th>Số đơn hàng</th>
                                <th>Doanh thu</th>
                                <th>Trung bình/đơn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalMonthly = 0;
                            $totalOrdersMonthly = 0;
                            if (!empty($data)):
                                foreach ($data as $row):
                                    $avgPerOrder = $row['order_count'] > 0 ? $row['revenue'] / $row['order_count'] : 0;
                                    $totalMonthly += $row['revenue'];
                                    $totalOrdersMonthly += $row['order_count'];
                            ?>
                                    <tr>
                                        <td><?php echo $row['month']; ?></td>
                                        <td><?php echo $row['order_count']; ?></td>
                                        <td class="data-highlight"><?php echo number_format($row['revenue'], 0, ',', '.'); ?> ₫</td>
                                        <td><?php echo number_format($avgPerOrder, 0, ',', '.'); ?> ₫</td>
                                    </tr>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                        <tfoot style="background: #f8f9fa; font-weight: 700;">
                            <tr>
                                <td colspan="2">Cộng</td>
                                <td class="data-highlight"><?php echo number_format($totalMonthly, 0, ',', '.'); ?> ₫</td>
                                <td><?php echo number_format($totalOrdersMonthly > 0 ? $totalMonthly / $totalOrdersMonthly : 0, 0, ',', '.'); ?> ₫</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php
                break;

            case 'products':
            ?>
                <div class="report-section">
                    <h2 class="section-title">🛍️ Thống kê sản phẩm</h2>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Loại</th>
                                <th>Giá</th>
                                <th>Đã bán</th>
                                <th>Doanh số</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalProductRevenue = 0;
                            $totalProductsSold = 0;
                            if (!empty($data)):
                                foreach ($data as $row):
                                    $totalProductRevenue += $row['total_revenue'] ?? 0;
                                    $totalProductsSold += $row['total_sold'] ?? 0;
                            ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['type'] ?? 'N/A'); ?></td>
                                        <td><?php echo number_format($row['price'], 0, ',', '.'); ?> ₫</td>
                                        <td><?php echo $row['total_sold'] ?? 0; ?> sp</td>
                                        <td class="data-highlight"><?php echo number_format($row['total_revenue'] ?? 0, 0, ',', '.'); ?> ₫</td>
                                    </tr>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                        <tfoot style="background: #f8f9fa; font-weight: 700;">
                            <tr>
                                <td colspan="3">Tổng</td>
                                <td><?php echo $totalProductsSold; ?> sp</td>
                                <td class="data-highlight"><?php echo number_format($totalProductRevenue, 0, ',', '.'); ?> ₫</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
        <?php
                break;
        }
        ?>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function changeReport() {
        const type = document.getElementById('reportType').value;
        window.location.href = 'index.php?controller=admin&action=reports&type=' + type;
    }

    function exportExcel() {
        const type = document.getElementById('reportType').value;
        let filename = 'bao-cao-';

        if (type === 'daily') filename += 'doanh-thu-ngay.xlsx';
        else if (type === 'monthly') filename += 'doanh-thu-thang.xlsx';
        else filename += 'thong-ke-san-pham.xlsx';

        // Get table data
        const table = document.querySelector('.data-table');
        const rows = [];

        table.querySelectorAll('tr').forEach(tr => {
            const row = [];
            tr.querySelectorAll('th, td').forEach(td => {
                row.push(td.textContent.trim());
            });
            rows.push(row);
        });

        // Create CSV content
        let csv = '\uFEFF';
        rows.forEach(row => {
            csv += row.join(',') + '\n';
        });

        // Download
        const link = document.createElement('a');
        link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
        link.download = filename.replace('.xlsx', '.csv');
        link.click();
    }

    function exportPDF() {
        const element = document.querySelector('.report-section');
        const opt = {
            margin: 10,
            filename: 'bao-cao.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            }
        };

        html2pdf().set(opt).from(element).save();
    }
</script>