<?php
// Preserve all existing PHP logic
function display_stars($rating)
{
    $output = '';
    $rating = round($rating);
    for ($i = 1; $i <= 5; $i++) {
        $output .= ($i <= $rating) ? '<span class="star-filled">★</span>' : '<span class="star-empty">☆</span>';
    }
    return $output;
}

$averageRating = $ratingInfo['avg_rating'] ?? 0;
$totalReviews = $ratingInfo['total'] ?? 0;

$inWishlist = false;
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../models/Wishlist.php';
    $wishlistModel = new Wishlist();
    $inWishlist = $wishlistModel->isInWishlist($_SESSION['user_id'], $product['id']);
}

// Package options matching Figma design
$packageOptions = [
    [
        'id' => 'package-48',
        'label' => 'Thùng 48 hộp',
        'price' => $product['price'] * 48,
        'originalPrice' => $product['price'] * 48 * 1.06,
        'discount' => '-6%',
    ],
    [
        'id' => 'package-24',
        'label' => '6 lốc (24 hộp)',
        'price' => $product['price'] * 24,
        'originalPrice' => $product['price'] * 24 * 1.06,
        'discount' => '-6%',
    ],
    [
        'id' => 'package-16',
        'label' => '4 lốc',
        'price' => $product['price'] * 16,
        'originalPrice' => null,
        'discount' => null,
    ],
];

$sizeOptions = [
    ['id' => '180ml', 'label' => '180ml'],
    ['id' => '110ml', 'label' => '110ml'],
];

$selectedSize = '180ml';
$selectedPackage = 'package-48';
$quantity = 1;
?>

<style>
    /* Vinamilk Product Detail - Figma Design Match */
    :root {
        --vm-primary: #0213ae;
        --vm-primary-dark: #0213b0;
        --vm-bg: #fffff1;
        --vm-bg-light: #e9f0f8;
        --vm-text: #0213b0;
        --vm-orange: #ff6b00;
    }

    .product-detail-wrapper {
        background-color: var(--vm-bg);
        min-height: 100vh;
        padding: 120px 60px 60px;
    }

    .product-breadcrumb {
        max-width: 1000px;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--vm-primary);
        font-family: 'Montserrat', sans-serif;
    }

    .product-breadcrumb a {
        color: var(--vm-primary);
        text-decoration: none;
    }

    .product-breadcrumb a:hover {
        text-decoration: underline;
    }

    .product-layout {
        max-width: 1000px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        background: transparent;
        padding: 0;
    }

    .product-image-section {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        position: relative;
    }

    .product-main-image {
        width: 100%;
        max-width: 460px;
        aspect-ratio: 1;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 8px;
    }

    .product-info-section {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .product-badge-tag {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 16px;
        background-color: var(--vm-bg-light);
        width: fit-content;
        margin-bottom: 20px;
    }

    .badge-text {
        color: var(--vm-primary);
        font-size: 12px;
        font-weight: bold;
        font-family: 'Montserrat', sans-serif;
    }

    .product-category {
        color: var(--vm-primary);
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 10px;
        font-family: 'Montserrat', sans-serif;
    }

    .product-name-title {
        color: var(--vm-primary);
        font-size: 48px;
        font-weight: bold;
        margin-bottom: 16px;
        font-family: 'Montserrat', sans-serif;
        line-height: 1.2;
    }

    .product-delivery-info {
        color: #999999;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 24px;
        font-family: 'Montserrat', sans-serif;
    }

    .product-description-text {
        color: var(--vm-primary);
        font-size: 16px;
        font-weight: 500;
        line-height: 1.4;
        margin-bottom: 24px;
        font-family: 'Montserrat', sans-serif;
    }

    .product-info-bar {
        margin-bottom: 24px;
        padding: 12px;
        background-color: var(--vm-bg-light);
    }

    .size-selector {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0;
        padding: 12px;
        margin-bottom: 24px;
        background-color: white;
        border: 1px solid #ddd;
    }

    .size-btn {
        padding: 16px 12px;
        border: 1px solid #ddd;
        background: white;
        color: var(--vm-primary);
        cursor: pointer;
        font-weight: bold;
        font-size: 11px;
        font-family: 'Montserrat', sans-serif;
        position: relative;
        transition: background-color 0.2s;
    }

    .size-btn.active {
        background-color: var(--vm-bg-light);
    }

    .size-btn.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--vm-primary);
    }

    .package-options {
        display: flex;
        flex-direction: column;
        gap: 0;
        margin-bottom: 24px;
        border: 1px solid #ddd;
    }

    .package-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 42px;
        padding: 0 12px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .package-item:last-child {
        border-bottom: none;
    }

    .package-item.active {
        background-color: var(--vm-bg-light);
    }

    .package-item input[type="radio"] {
        appearance: none;
        width: 18px;
        height: 18px;
        border: 2px solid var(--vm-primary);
        border-radius: 50%;
        cursor: pointer;
        margin-right: 8px;
    }

    .package-item input[type="radio"]:checked {
        background-color: var(--vm-primary);
        box-shadow: inset 0 0 0 3px white;
    }

    .package-left {
        display: flex;
        align-items: center;
    }

    .package-label {
        color: var(--vm-primary);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        font-family: 'Montserrat', sans-serif;
    }

    .package-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .discount-badge {
        color: #f24e1e;
        font-weight: 600;
        font-size: 10px;
        font-family: 'Montserrat', sans-serif;
    }

    .original-price {
        color: #999999;
        font-weight: 600;
        font-size: 10px;
        text-decoration: line-through;
        font-family: 'Montserrat', sans-serif;
    }

    .package-price {
        color: var(--vm-primary);
        font-weight: 600;
        font-size: 14px;
        text-align: right;
        font-family: 'Montserrat', sans-serif;
    }

    .quantity-container {
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
        align-items: center;
    }

    .quantity-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 142px;
        height: 48px;
        border: 2px solid var(--vm-primary);
        border-radius: 4px;
        overflow: hidden;
    }

    .qty-btn {
        width: 40px;
        height: 100%;
        background: white;
        border: none;
        color: var(--vm-primary);
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Montserrat', sans-serif;
        transition: background-color 0.2s;
    }

    .qty-btn:hover {
        background-color: var(--vm-bg-light);
    }

    .qty-display {
        flex: 1;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--vm-primary);
        font-weight: 600;
        font-size: 14px;
        font-family: 'Montserrat', sans-serif;
        letter-spacing: 5px;
    }

    .add-cart-btn {
        flex: 1;
        height: 48px;
        background-color: var(--vm-primary);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Montserrat', sans-serif;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .add-cart-btn:hover {
        background-color: #0010a0;
        opacity: 0.9;
    }

    .nutrition-section {
        background-color: var(--vm-bg-light);
        padding: 12px 16px;
        margin-bottom: 24px;
    }

    .nutrition-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .nutrition-title {
        color: var(--vm-primary);
        font-weight: 600;
        font-size: 14px;
        font-family: 'Montserrat', sans-serif;
    }

    .nutrition-toggle {
        width: 40px;
        height: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .wishlist-btn-detail {
        width: 48px;
        height: 48px;
        background: white;
        border: 2px solid #ddd;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 24px;
        transition: all 0.3s;
    }

    .wishlist-btn-detail:hover {
        border-color: var(--vm-orange);
        transform: scale(1.05);
    }

    .wishlist-btn-detail.active {
        background-color: var(--vm-orange);
        border-color: var(--vm-orange);
    }

    /* Reviews Section */
    .reviews-container {
        max-width: 1000px;
        margin: 60px auto 0;
        padding: 30px;
        background: white;
        border-radius: 12px;
    }

    .reviews-title {
        color: var(--vm-primary);
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
        font-family: 'Montserrat', sans-serif;
    }

    .review-form-box {
        padding: 20px;
        background: var(--vm-bg-light);
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .review-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .review-card {
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }

    @media (max-width: 768px) {
        .product-layout {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .product-name-title {
            font-size: 32px;
        }

        .product-detail-wrapper {
            padding: 20px;
        }
    }
</style>

<div class="product-detail-wrapper">
    <br> <br>
    <div class="product-breadcrumb">
        <a href="index.php">🏠</a>
        <span>&gt;</span>
        <a href="index.php?controller=product&action=productList">Sản Phẩm</a>
        <span>&gt;</span>
        <span><?php echo htmlspecialchars($product['name']); ?></span>
    </div>

    <!-- Product Layout -->
    <div class="product-layout">
        <!-- Image Section -->
        <div class="product-image-section">
            <?php
            $imagePath = "uploads/" . htmlspecialchars($product['image'] ?? '');
            $imageUrl = (!empty($product['image']) && file_exists(__DIR__ . '/../uploads/' . $product['image']))
                ? $imagePath
                : '/frame-90.png';
            ?>
            <div class="product-main-image" style="background-image: url('<?php echo $imageUrl; ?>')"></div>
        </div>

        <!-- Info Section -->
        <div class="product-info-section">
            <!-- Badge -->
            <div class="product-badge-tag">
                <span class="badge-text">Purity Award</span>
            </div>

            <!-- Category -->
            <div class="product-category"><?php echo htmlspecialchars($product['type']); ?></div>

            <!-- Product Name -->
            <h1 class="product-name-title"><?php echo htmlspecialchars($product['name']); ?></h1>

            <!-- Delivery Info -->
            <div class="product-delivery-info">Giao 2H | Nhận tại cửa hàng</div>

            <!-- Description -->
            <p class="product-description-text">
                <?php echo htmlspecialchars($product['description'] ?? 'Sản phẩm chất lượng cao từ Vinamilk'); ?>
            </p>



            <!-- Size Selector -->
            <form method="POST" action="index.php?controller=cart&action=add" id="productForm">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="size" id="sizeInput" value="<?php echo $selectedSize; ?>">
                <input type="hidden" name="package" id="packageInput" value="<?php echo $selectedPackage; ?>">
                <input type="hidden" name="quantity" id="quantityInput" value="<?php echo $quantity; ?>">

                <div class="size-selector">
                    <?php foreach ($sizeOptions as $size): ?>
                        <button
                            type="button"
                            class="size-btn <?php echo $selectedSize === $size['id'] ? 'active' : ''; ?>"
                            onclick="selectSize('<?php echo $size['id']; ?>')">
                            <?php echo htmlspecialchars($size['label']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Package Options -->
                <div class="package-options">
                    <?php foreach ($packageOptions as $index => $option): ?>
                        <div class="package-item <?php echo $selectedPackage === $option['id'] ? 'active' : ''; ?>"
                            onclick="selectPackage('<?php echo $option['id']; ?>')">
                            <div class="package-left">
                                <input
                                    type="radio"
                                    name="package_radio"
                                    value="<?php echo htmlspecialchars($option['id']); ?>"
                                    id="<?php echo htmlspecialchars($option['id']); ?>"
                                    <?php echo $selectedPackage === $option['id'] ? 'checked' : ''; ?>>
                                <label for="<?php echo htmlspecialchars($option['id']); ?>" class="package-label">
                                    <?php echo htmlspecialchars($option['label']); ?>
                                </label>
                            </div>
                            <div class="package-right">
                                <?php if ($option['discount']): ?>
                                    <span class="discount-badge"><?php echo htmlspecialchars($option['discount']); ?></span>
                                <?php endif; ?>
                                <?php if ($option['originalPrice']): ?>
                                    <span class="original-price"><?php echo number_format($option['originalPrice'], 0, ',', '.'); ?>đ</span>
                                <?php endif; ?>
                                <span class="package-price"><?php echo number_format($option['price'], 0, ',', '.'); ?>đ</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Quantity and Add to Cart -->
                <div class="quantity-container">
                    <div class="quantity-box">
                        <button type="button" class="qty-btn" onclick="decreaseQuantity()">-</button>
                        <div class="qty-display" id="quantityDisplay"><?php echo $quantity; ?></div>
                        <button type="button" class="qty-btn" onclick="increaseQuantity()">+</button>
                    </div>
                    <button type="submit" class="add-cart-btn">
                        Thêm vào giỏ hàng | <?php echo number_format($product['price'], 0, ',', '.'); ?>đ
                    </button>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button type="button"
                            class="wishlist-btn-detail <?php echo $inWishlist ? 'active' : ''; ?>"
                            onclick="toggleWishlist(this, <?php echo $product['id']; ?>)">
                            <?php echo $inWishlist ? '❤️' : '🤍'; ?>
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Nutrition Section -->
                <div class="nutrition-section">
                    <div class="nutrition-header">
                        <span class="nutrition-title">Thành phần & Dinh dưỡng</span>
                        <div class="nutrition-toggle">
                            <span style="color: black; font-weight: 200; font-size: 14px;">+</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-container">
        <h2 class="reviews-title">Đánh giá & Nhận xét (<?php echo $totalReviews; ?>)</h2>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="review-form-box">
                <h3 style="margin-bottom: 15px; color: var(--vm-primary);">Gửi đánh giá của bạn</h3>
                <form action="index.php?controller=review&action=create" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Đánh giá:</label>
                        <select name="rating" class="form-select" required>
                            <option value="5">⭐⭐⭐⭐⭐</option>
                            <option value="4">⭐⭐⭐⭐</option>
                            <option value="3">⭐⭐⭐</option>
                            <option value="2">⭐⭐</option>
                            <option value="1">⭐</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nhận xét:</label>
                        <textarea name="comment" rows="4" class="form-textarea" required></textarea>
                    </div>
                    <button type="submit" class="add-cart-btn" style="width: auto; padding: 0 24px;">Gửi đánh giá</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="review-list">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <strong style="color: var(--vm-primary);"><?php echo htmlspecialchars($review['full_name']); ?></strong>
                            <span><?php echo display_stars($review['rating']); ?></span>
                        </div>
                        <p style="margin-bottom: 10px;"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                        <small style="color: #999;"><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #999;">Chưa có đánh giá nào.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Preserve all existing JavaScript functionality
    let currentQuantity = <?php echo $quantity; ?>;
    let currentSize = "<?php echo $selectedSize; ?>";
    let currentPackage = "<?php echo $selectedPackage; ?>";

    function selectSize(size) {
        currentSize = size;
        document.getElementById('sizeInput').value = size;

        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
    }

    function selectPackage(packageId) {
        currentPackage = packageId;
        document.getElementById('packageInput').value = packageId;

        document.querySelectorAll('.package-item').forEach(item => {
            item.classList.remove('active');
        });
        event.target.closest('.package-item').classList.add('active');

        document.getElementById(packageId).checked = true;
    }

    function increaseQuantity() {
        currentQuantity++;
        updateQuantityDisplay();
    }

    function decreaseQuantity() {
        if (currentQuantity > 1) {
            currentQuantity--;
            updateQuantityDisplay();
        }
    }

    function updateQuantityDisplay() {
        document.getElementById('quantityDisplay').textContent = currentQuantity;
        document.getElementById('quantityInput').value = currentQuantity;
    }

    // Wishlist function (preserved from original)
    async function toggleWishlist(btn, productId) {
        btn.disabled = true;
        try {
            const formData = new FormData();
            formData.append('product_id', productId);

            const response = await fetch('index.php?controller=wishlist&action=toggle', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                if (data.action === 'added') {
                    btn.textContent = '❤️';
                    btn.classList.add('active');
                } else {
                    btn.textContent = '🤍';
                    btn.classList.remove('active');
                }

                const badge = document.getElementById('wishlist-count-badge');
                if (badge) {
                    badge.textContent = data.count;
                    badge.style.display = data.count > 0 ? 'flex' : 'none';
                }

                showNotification(data.message, 'success');
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            btn.disabled = false;
        }
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type} show`;
        notification.textContent = message;
        notification.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        background: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        border-left: 4px solid ${type === 'success' ? '#28a745' : '#dc3545'};
    `;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
</script>