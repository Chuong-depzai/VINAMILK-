<style>
    .wishlist-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .wishlist-actions {
        display: flex;
        gap: 15px;
    }

    .btn-move-all-to-cart {
        padding: 12px 24px;
        background-color: #ff6b00;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        transition: background-color 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-move-all-to-cart:hover {
        background-color: #e55d00;
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
    }

    .wishlist-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }

    .wishlist-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .wishlist-remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .wishlist-remove-btn:hover {
        background-color: #dc3545;
        transform: scale(1.1);
    }

    .wishlist-added-date {
        font-size: 12px;
        color: #999;
        margin-top: 10px;
    }

    .empty-wishlist {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .empty-wishlist-icon {
        font-size: 64px;
        margin-bottom: 20px;
    }

    .empty-wishlist-text {
        font-size: 18px;
        color: #666;
        margin-bottom: 30px;
    }
</style>

<div class="page-container">
    <link rel="stylesheet" href="style.css">
    <div class="wishlist-header">
        <h1 class="page-title">❤️ Danh sách yêu thích</h1>

        <?php if (!empty($wishlistItems)): ?>
            <div class="wishlist-actions">
                <form method="POST" action="index.php?controller=wishlist&action=moveAllToCart" style="display: inline;">
                    <button type="submit" class="btn-move-all-to-cart" onclick="return confirm('Chuyển tất cả sản phẩm vào giỏ hàng?')">
                        🛒 Thêm tất cả vào giỏ
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <?php if (empty($wishlistItems)): ?>
        <div class="empty-wishlist">
            <div class="empty-wishlist-icon">💔</div>
            <p class="empty-wishlist-text">Danh sách yêu thích của bạn đang trống</p>
            <a href="index.php" class="btn-primary">Khám phá sản phẩm</a>
        </div>
    <?php else: ?>
        <div class="wishlist-grid">
            <?php foreach ($wishlistItems as $item): ?>
                <div class="wishlist-card">
                    <button class="wishlist-remove-btn"
                        onclick="removeFromWishlist(<?php echo $item['id']; ?>)"
                        title="Xóa khỏi yêu thích">
                        ✕
                    </button>

                    <div class="product-image-wrapper">
                        <?php
                        $imagePath = "uploads/" . htmlspecialchars($item['image']);
                        if (file_exists(__DIR__ . '/../uploads/' . $item['image'])):
                        ?>
                            <img src="<?php echo $imagePath; ?>"
                                alt="<?php echo htmlspecialchars($item['name']); ?>"
                                class="product-image">
                        <?php else: ?>
                            <div class="product-image-placeholder">
                                <span class="placeholder-text">Không có ảnh</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p class="product-type"><?php echo htmlspecialchars($item['type']); ?></p>
                        <p class="product-price"><?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</p>

                        <p class="wishlist-added-date">
                            Đã thêm: <?php echo date('d/m/Y', strtotime($item['added_at'])); ?>
                        </p>

                        <a href="index.php?controller=product&action=show&id=<?php echo $item['id']; ?>"
                            class="btn-primary">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function removeFromWishlist(productId) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
            return;
        }

        const formData = new FormData();
        formData.append('product_id', productId);

        fetch('index.php?controller=wishlist&action=remove', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload trang để cập nhật danh sách
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xóa sản phẩm');
            });
    }
</script>