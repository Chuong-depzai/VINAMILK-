<?php
// Hàm hiển thị sao
function display_stars($rating)
{
    $output = '';
    $rating = round($rating);
    for ($i = 1; $i <= 5; $i++) {
        $output .= ($i <= $rating) ? '<span class="star-filled">★</span>' : '<span class="star-empty">☆</span>';
    }
    return $output;
}

// Tính điểm trung bình và tổng số đánh giá
$averageRating = $ratingInfo['avg_rating'] ?? 0;
$totalReviews = $ratingInfo['total'] ?? 0;

// Kiểm tra sản phẩm có trong wishlist không
$inWishlist = false;
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../models/Wishlist.php';
    $wishlistModel = new Wishlist();
    $inWishlist = $wishlistModel->isInWishlist($_SESSION['user_id'], $product['id']);
}
?>

<div class="page-container">
    <div class="product-detail-container">
        <!-- Phần ảnh sản phẩm -->
        <div class="product-detail-image-section">
            <?php
            $imagePath = "uploads/" . htmlspecialchars($product['image'] ?? '');
            if (!empty($product['image']) && file_exists(__DIR__ . '/../uploads/' . $product['image'])):
            ?>
                <img src="<?php echo $imagePath; ?>"
                    alt="<?php echo htmlspecialchars($product['name'] ?? ''); ?>"
                    class="product-detail-image">
            <?php else: ?>
                <div class="product-image-placeholder-large">
                    <span class="placeholder-text">Không có ảnh</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Phần thông tin sản phẩm -->
        <div class="product-detail-info-section">
            <h1 class="product-detail-name"><?php echo htmlspecialchars($product['name'] ?? ''); ?></h1>

            <div class="product-detail-price-box">
                <span class="product-detail-price">
                    <?php echo number_format($product['price'] ?? 0, 0, ',', '.'); ?> VNĐ
                </span>
            </div>

            <!-- Đánh giá sao -->
            <div class="product-rating-summary" style="margin-bottom: 20px;">
                <span class="rating-stars-lg">
                    <?php echo display_stars($averageRating); ?>
                </span>
                <span class="rating-text">
                    (<?php echo number_format($averageRating, 1); ?>/5 sao từ <?php echo $totalReviews; ?> đánh giá)
                </span>
            </div>

            <!-- Thông tin meta -->
            <div class="product-detail-meta">
                <div class="product-meta-item">
                    <span class="product-meta-label">Loại sản phẩm:</span>
                    <span class="product-meta-value"><?php echo htmlspecialchars($product['type'] ?? ''); ?></span>
                </div>
                <div class="product-meta-item">
                    <span class="product-meta-label">Quy cách đóng gói:</span>
                    <span class="product-meta-value"><?php echo htmlspecialchars($product['packaging'] ?? 'Hộp'); ?></span>
                </div>
            </div>

            <!-- Mô tả sản phẩm -->
            <?php if (!empty($product['description'])): ?>
                <div class="product-detail-description">
                    <h3 class="section-title">Mô tả sản phẩm</h3>
                    <p class="product-section-text"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
            <?php endif; ?>

            <!-- Thành phần -->
            <?php if (!empty($product['ingredients'])): ?>
                <div class="product-detail-ingredients">
                    <h3 class="section-title">Thành phần</h3>
                    <p class="product-section-text"><?php echo nl2br(htmlspecialchars($product['ingredients'])); ?></p>
                </div>
            <?php endif; ?>

            <!-- Form thêm vào giỏ hàng + Nút Wishlist -->
            <div style="display: flex; gap: 15px; margin-top: 30px; align-items: center;">
                <!-- Form giỏ hàng -->
                <form method="POST" action="index.php?controller=cart&action=add" style="flex: 1;">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn-add-to-cart">
                        🛒 Thêm vào Giỏ hàng
                    </button>
                </form>

                <!-- Nút Wishlist -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button type="button"
                        id="wishlist-btn"
                        class="btn-wishlist <?php echo $inWishlist ? 'active' : ''; ?>"
                        onclick="toggleWishlist(this, <?php echo $product['id']; ?>)"
                        style="position: static; width: 60px; height: 60px; font-size: 28px; flex-shrink: 0;">
                        <?php echo $inWishlist ? '❤️' : '🤍'; ?>
                    </button>
                <?php else: ?>
                    <button type="button"
                        class="btn-wishlist"
                        onclick="alert('Vui lòng đăng nhập để thêm vào yêu thích'); window.location.href='index.php?controller=auth&action=showLogin';"
                        style="position: static; width: 60px; height: 60px; font-size: 28px; flex-shrink: 0;">
                        🤍
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Phần Đánh giá & Bình luận -->
    <div class="product-reviews-container">
        <h2 class="section-title-large">Đánh giá & Bình luận</h2>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="review-form-wrapper">
                <h3>Gửi đánh giá của bạn</h3>
                <form action="index.php?controller=review&action=create" method="POST" class="review-form">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                    <div class="form-group-review">
                        <label for="rating" class="form-label-review">Chọn số sao:</label>
                        <select name="rating" id="rating" class="form-select-review" required>
                            <option value="5">⭐⭐⭐⭐⭐ (Tuyệt vời)</option>
                            <option value="4">⭐⭐⭐⭐ (Tốt)</option>
                            <option value="3">⭐⭐⭐ (Bình thường)</option>
                            <option value="2">⭐⭐ (Tệ)</option>
                            <option value="1">⭐ (Rất tệ)</option>
                        </select>
                    </div>

                    <div class="form-group-review">
                        <label for="comment" class="form-label-review">Nhận xét:</label>
                        <textarea name="comment"
                            id="comment"
                            rows="4"
                            placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..."
                            class="form-textarea-review"
                            required></textarea>
                    </div>

                    <button type="submit" class="btn-submit-review">Gửi đánh giá</button>
                </form>
            </div>
        <?php else: ?>
            <div class="login-prompt-review">
                <p>Vui lòng <a href="index.php?controller=auth&action=showLogin">đăng nhập</a> để viết đánh giá.</p>
            </div>
        <?php endif; ?>

        <!-- Danh sách đánh giá -->
        <div class="reviews-list">
            <h3>Tất cả đánh giá (<?php echo $totalReviews; ?>)</h3>
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <strong class="review-user-name">
                                <?php echo htmlspecialchars($review['full_name'] ?? 'Ẩn danh'); ?>
                            </strong>
                            <span class="review-stars">
                                <?php echo display_stars($review['rating']); ?>
                            </span>
                        </div>
                        <p class="review-comment"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                        <small class="review-date">
                            <?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?>
                        </small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #999; text-align: center; padding: 20px;">
                    Chưa có đánh giá nào cho sản phẩm này.
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    async function toggleWishlist(btn, productId) {
        // Vô hiệu hóa nút để tránh click nhiều lần
        btn.disabled = true;

        try {
            const formData = new FormData();
            formData.append('product_id', productId);

            const response = await fetch('index.php?controller=wishlist&action=toggle', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();

            if (data.success) {
                // Cập nhật nút NGAY LẬP TỨC
                if (data.action === 'added') {
                    btn.textContent = '❤️';
                    btn.classList.add('active');
                } else {
                    btn.textContent = '🤍';
                    btn.classList.remove('active');
                }

                // Cập nhật badge đếm wishlist ở header
                const badge = document.getElementById('wishlist-count-badge');
                if (badge) {
                    badge.textContent = data.count;
                    badge.style.display = data.count > 0 ? 'flex' : 'none';
                }

                // Hiển thị thông báo
                showNotification(data.message, 'success');
            } else {
                if (data.redirect) {
                    showNotification(data.message, 'error');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    showNotification(data.message || 'Có lỗi xảy ra', 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra. Vui lòng thử lại!', 'error');
        } finally {
            // Kích hoạt lại nút
            btn.disabled = false;
        }
    }

    function showNotification(message, type) {
        // Tạo thông báo đơn giản
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

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>