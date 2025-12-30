<?php
// Xử lý bộ lọc
$filteredProducts = $products;

// Lọc theo loại sản phẩm (Dòng sản phẩm)
if (!empty($_GET['type']) && is_array($_GET['type'])) {
    $filteredProducts = array_filter($filteredProducts, function ($p) {
        return in_array($p['type'], $_GET['type']);
    });
}

// Lọc theo giá
if (!empty($_GET['price'])) {
    $filteredProducts = array_filter($filteredProducts, function ($p) {
        switch ($_GET['price']) {
            case 'under-50k':
                return $p['price'] < 50000;
            case '50k-100k':
                return $p['price'] >= 50000 && $p['price'] < 100000;
            case '100k-300k':
                return $p['price'] >= 100000 && $p['price'] < 300000;
            case 'over-300k':
                return $p['price'] >= 300000;
            default:
                return true;
        }
    });
}

$productsPerPage = 9;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$totalProducts = count($filteredProducts);
$totalPages = ceil($totalProducts / $productsPerPage);
$offset = ($currentPage - 1) * $productsPerPage;
$productsOnPage = array_slice($filteredProducts, $offset, $productsPerPage);

function buildPaginationUrl($page)
{
    $params = $_GET;
    $params['page'] = $page;
    return 'index.php?controller=product&action=productList&' . http_build_query($params);
}

$productTypes = [];
foreach ($products as $p) {
    if (!in_array($p['type'], $productTypes)) {
        $productTypes[] = $p['type'];
    }
}
sort($productTypes);

$priceRanges = [
    'under-50k' => 'Dưới 50.000đ',
    '50k-100k' => '50.000đ - 100.000đ',
    '100k-300k' => '100.000đ - 300.000đ',
    'over-300k' => 'Trên 300.000đ'
];
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
                <div class="filter-header" onclick="toggleFilter(this)">
                    <span>Dòng sản phẩm</span>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="filter-line"></div>
                <div class="filter-options show">
                    <?php foreach ($productTypes as $type): ?>
                        <label class="filter-option">
                            <input type="checkbox"
                                class="type-filter"
                                value="<?php echo htmlspecialchars($type); ?>"
                                <?php echo (isset($_GET['type']) && in_array($type, $_GET['type'])) ? 'checked' : ''; ?>
                                onchange="applyFilter()">
                            <span><?php echo htmlspecialchars($type); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-group">
                <div class="filter-header" onclick="toggleFilter(this)">
                    <span>Khoảng giá</span>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="filter-line"></div>
                <div class="filter-options">
                    <?php foreach ($priceRanges as $key => $label): ?>
                        <label class="filter-option">
                            <input type="radio"
                                class="price-filter"
                                name="price"
                                value="<?php echo htmlspecialchars($key); ?>"
                                <?php echo (isset($_GET['price']) && $_GET['price'] === $key) ? 'checked' : ''; ?>
                                onchange="applyFilter()">
                            <span><?php echo htmlspecialchars($label); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
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

            <?php if (empty($productsOnPage)): ?>
                <div class="empty-state">
                    <p>Chưa có sản phẩm nào phù hợp.</p>
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
    function toggleFilter(header) {
        const options = header.parentElement.querySelector('.filter-options');
        if (options) {
            options.classList.toggle('show');
        }
    }

    function applyFilter() {
        const types = [];
        document.querySelectorAll('.type-filter:checked').forEach(cb => {
            types.push('type[]=' + encodeURIComponent(cb.value));
        });

        const price = document.querySelector('.price-filter:checked');
        let url = 'index.php?controller=product&action=productList';

        if (types.length > 0) {
            url += '&' + types.join('&');
        }

        if (price) {
            url += '&price=' + encodeURIComponent(price.value);
        }

        window.location.href = url;
    }

    function addToCart(productId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?controller=cart&action=add';
        form.innerHTML = `
            <input type="hidden" name="product_id" value="${productId}">
            <input type="hidden" name="quantity" value="1">
        `;
        document.body.appendChild(form);
        form.submit();
    }
</script>