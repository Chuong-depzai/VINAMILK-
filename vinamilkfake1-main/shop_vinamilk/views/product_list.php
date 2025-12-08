<?php
// Pagination settings
$productsPerPage = 9;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$totalProducts = count($products);
$totalPages = ceil($totalProducts / $productsPerPage);

// Get products for current page
$offset = ($currentPage - 1) * $productsPerPage;
$productsOnPage = array_slice($products, $offset, $productsPerPage);

// Build query string while preserving other parameters
function buildPaginationUrl($page)
{
    $params = $_GET;
    $params['page'] = $page;
    return '?' . http_build_query($params);
}
?>

<style>
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-top: 40px;
        padding: 20px 0;
    }

    .pagination-btn {
        padding: 10px 16px;
        background: white;
        border: 2px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        color: #333;
        text-decoration: none;
        display: inline-block;
        min-width: 44px;
        text-align: center;
    }

    .pagination-btn:hover:not(.active):not(.disabled) {
        background: #0033a0;
        color: white;
        border-color: #0033a0;
        transform: translateY(-2px);
    }

    .pagination-btn.active {
        background: #0033a0;
        color: white;
        border-color: #0033a0;
        cursor: default;
    }

    .pagination-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .pagination-info {
        color: #666;
        font-size: 14px;
        margin: 0 15px;
    }
</style>

<div class="page-container">
    <h1 class="page-title">Sản phẩm Vinamilk</h1>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <p class="empty-state-text">Chưa có sản phẩm nào.</p>
        </div>
    <?php else: ?>
        <!-- Product Grid -->
        <div class="product-grid">
            <?php foreach ($productsOnPage as $product): ?>
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <?php
                        $imagePath = "uploads/" . htmlspecialchars($product['image']);
                        if (file_exists(__DIR__ . '/../uploads/' . $product['image'])):
                        ?>
                            <img src="<?php echo $imagePath; ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>"
                                class="product-image">
                        <?php else: ?>
                            <div class="product-image-placeholder">
                                <span class="placeholder-text">Không có ảnh</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-type"><?php echo htmlspecialchars($product['type']); ?></p>
                        <p class="product-price"><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</p>

                        <p class="product-description-short">
                            <?php
                            $desc = htmlspecialchars($product['description']);
                            echo mb_strlen($desc) > 100 ? mb_substr($desc, 0, 100) . '...' : $desc;
                            ?>
                        </p>

                        <a href="index.php?controller=product&action=show&id=<?php echo $product['id']; ?>"
                            class="btn-primary">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <!-- Previous Button -->
                <?php if ($currentPage > 1): ?>
                    <a href="<?php echo buildPaginationUrl($currentPage - 1); ?>" class="pagination-btn">
                        ← Trước
                    </a>
                <?php else: ?>
                    <span class="pagination-btn disabled">← Trước</span>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php
                $range = 2; // Số trang hiển thị mỗi bên
                $start = max(1, $currentPage - $range);
                $end = min($totalPages, $currentPage + $range);

                // First page
                if ($start > 1): ?>
                    <a href="<?php echo buildPaginationUrl(1); ?>" class="pagination-btn">1</a>
                    <?php if ($start > 2): ?>
                        <span class="pagination-btn disabled">...</span>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Middle pages -->
                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="pagination-btn active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="<?php echo buildPaginationUrl($i); ?>" class="pagination-btn"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Last page -->
                <?php if ($end < $totalPages): ?>
                    <?php if ($end < $totalPages - 1): ?>
                        <span class="pagination-btn disabled">...</span>
                    <?php endif; ?>
                    <a href="<?php echo buildPaginationUrl($totalPages); ?>" class="pagination-btn"><?php echo $totalPages; ?></a>
                <?php endif; ?>

                <!-- Next Button -->
                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?php echo buildPaginationUrl($currentPage + 1); ?>" class="pagination-btn">
                        Sau →
                    </a>
                <?php else: ?>
                    <span class="pagination-btn disabled">Sau →</span>
                <?php endif; ?>

                <!-- Info -->
                <span class="pagination-info">
                    Trang <?php echo $currentPage; ?> / <?php echo $totalPages; ?>
                    (<?php echo $totalProducts; ?> sản phẩm)
                </span>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>