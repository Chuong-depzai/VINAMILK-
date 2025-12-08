<?php
$productsPerPage = 9;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$totalProducts = count($products);
$totalPages = ceil($totalProducts / $productsPerPage);
$offset = ($currentPage - 1) * $productsPerPage;
$productsOnPage = array_slice($products, $offset, $productsPerPage);

function buildPaginationUrl($page)
{
    $params = $_GET;
    $params['page'] = $page;
    return '?' . http_build_query($params);
}

$productTypes = [];
foreach ($products as $p) {
    if (!in_array($p['type'], $productTypes)) {
        $productTypes[] = $p['type'];
    }
}
?>

<div class="products-page">
    <div class="breadcrumb">
        <a href="index.php">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
        </a>
        <span class="sep">&gt;</span>
        <a href="index.php?controller=product&action=productList">Sản Phẩm</a>
        <span class="sep">&gt;</span>
        <span class="current">Tất Cả Sản Phẩm</span>
    </div>

    <div class="products-header">
        <h1 class="products-title">Tất Cả Sản Phẩm</h1>
        <span class="products-count"><?php echo $totalProducts; ?></span>
    </div>

    <div class="products-layout">
        <aside class="products-sidebar">
            <div class="filter-group">
                <div class="filter-header">
                    <span>Danh mục</span>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="filter-line"></div>
            </div>

            <div class="filter-group">
                <div class="filter-header">
                    <span>Dòng sản phẩm</span>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="filter-line"></div>
                <div class="filter-options">
                    <?php foreach ($productTypes as $type): ?>
                        <label class="filter-option">
                            <input type="checkbox" name="type[]" value="<?php echo htmlspecialchars($type); ?>">
                            <span><?php echo htmlspecialchars($type); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-group">
                <div class="filter-header">
                    <span>Thương hiệu</span>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="filter-line"></div>
            </div>

            <div class="filter-group">
                <div class="filter-header">
                    <span>Hương vị</span>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="filter-line"></div>
            </div>

            <div class="filter-group">
                <div class="filter-header">
                    <span>Thể tích / Khối lượng</span>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="filter-line"></div>
            </div>

            <div class="filter-group">
                <div class="filter-header">
                    <span>Nhu cầu dinh dưỡng</span>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="filter-line"></div>
            </div>

            <div class="filter-group">
                <div class="filter-header">
                    <span>Mức đường</span>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="filter-line"></div>
            </div>
        </aside>

        <main class="products-main">
            <div class="products-toolbar">
                <div class="toolbar-badges">
                    <span class="badge-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="3" width="15" height="13" />
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                            <circle cx="5.5" cy="18.5" r="2.5" />
                            <circle cx="18.5" cy="18.5" r="2.5" />
                        </svg>
                        Giao hàng toàn quốc
                    </span>
                    <span class="badge-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        Đảm bảo chất lượng
                    </span>
                    <span class="badge-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        </svg>
                        Hỗ trợ 24/7
                    </span>
                </div>
            </div>

            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <p>Chưa có sản phẩm nào.</p>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($productsOnPage as $product): ?>
                        <div class="product-card">
                            <a href="index.php?controller=product&action=show&id=<?php echo $product['id']; ?>" class="product-link">
                                <div class="product-badge">Purity Award</div>
                                <div class="product-image-container">
                                    <?php
                                    $imagePath = "uploads/" . htmlspecialchars($product['image']);
                                    if (file_exists(__DIR__ . '/../uploads/' . $product['image'])):
                                    ?>
                                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img">
                                    <?php else: ?>
                                        <div class="product-img-placeholder">
                                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1">
                                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                                <circle cx="8.5" cy="8.5" r="1.5" />
                                                <path d="m21 15-5-5L5 21" />
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="product-type-tag"><?php echo htmlspecialchars($product['type']); ?></div>
                                <div class="product-title-row">
                                    <h3 class="product-name">Vinamilk • <?php echo htmlspecialchars($product['name']); ?></h3>
                                    <button class="btn-cart" onclick="event.preventDefault(); addToCart(<?php echo $product['id']; ?>)">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="9" cy="21" r="1" />
                                            <circle cx="20" cy="21" r="1" />
                                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="product-desc">
                                    <?php
                                    $desc = htmlspecialchars($product['description'] ?? '');
                                    echo mb_strlen($desc) > 80 ? mb_substr($desc, 0, 80) . '..' : $desc;
                                    ?>
                                </p>
                                <div class="product-footer">
                                    <span class="product-packaging"><?php echo htmlspecialchars($product['packaging'] ?? 'Hộp'); ?></span>
                                    <div class="product-price-wrap">
                                        <span class="product-price"><?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                                        <span class="product-currency">đ</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="<?php echo buildPaginationUrl($currentPage - 1); ?>" class="page-btn">&larr;</a>
                        <?php endif; ?>

                        <?php
                        $range = 2;
                        $start = max(1, $currentPage - $range);
                        $end = min($totalPages, $currentPage + $range);

                        if ($start > 1): ?>
                            <a href="<?php echo buildPaginationUrl(1); ?>" class="page-btn">1</a>
                            <?php if ($start > 2): ?><span class="page-dots">...</span><?php endif; ?>
                        <?php endif; ?>

                        <?php for ($i = $start; $i <= $end; $i++): ?>
                            <a href="<?php echo buildPaginationUrl($i); ?>" class="page-btn <?php echo $i == $currentPage ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>

                        <?php if ($end < $totalPages): ?>
                            <?php if ($end < $totalPages - 1): ?><span class="page-dots">...</span><?php endif; ?>
                            <a href="<?php echo buildPaginationUrl($totalPages); ?>" class="page-btn"><?php echo $totalPages; ?></a>
                        <?php endif; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="<?php echo buildPaginationUrl($currentPage + 1); ?>" class="page-btn">&rarr;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
    function addToCart(productId) {
        fetch('index.php?controller=cart&action=add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'product_id=' + productId + '&quantity=1'
        }).then(r => r.json()).then(data => {
            if (data.success) {
                alert('Đã thêm vào giỏ hàng!');
                location.reload();
            }
        }).catch(() => {
            window.location.href = 'index.php?controller=cart&action=add&product_id=' + productId;
        });
    }
</script>