<div class="page-container">
    <h1 class="page-title">Giỏ hàng của bạn</h1>

    <?php if (empty($cartItems)): ?>
        <div class="empty-cart">
            <p class="empty-cart-text">Giỏ hàng của bạn đang trống.</p>
            <a href="index.php" class="btn-primary">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="cart-container">
            <div class="cart-items-section">
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <div class="cart-item-image-wrapper">
                            <?php
                            $imagePath = "uploads/" . htmlspecialchars($item['image']);
                            if (file_exists(__DIR__ . '/../uploads/' . $item['image'])):
                            ?>
                                <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-item-image">
                            <?php else: ?>
                                <div class="cart-image-placeholder">
                                    <span class="placeholder-text-small">N/A</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="cart-item-info">
                            <h3 class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="cart-item-type"><?php echo htmlspecialchars($item['type']); ?></p>
                            <p class="cart-item-price"><?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</p>
                        </div>

                        <div class="cart-item-quantity">
                            <form method="POST" action="index.php?controller=cart&action=update" class="cart-quantity-form">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <label for="quantity_<?php echo $item['id']; ?>" class="quantity-label-cart">Số lượng:</label>
                                <input type="number" id="quantity_<?php echo $item['id']; ?>" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="100" class="cart-quantity-input">
                                <button type="submit" class="btn-update-quantity">Cập nhật</button>
                            </form>
                        </div>

                        <div class="cart-item-subtotal">
                            <p class="cart-subtotal-label">Tổng:</p>
                            <p class="cart-subtotal-value"><?php echo number_format($item['subtotal'], 0, ',', '.'); ?> VNĐ</p>
                        </div>

                        <div class="cart-item-actions">
                            <a href="index.php?controller=cart&action=remove&id=<?php echo $item['id']; ?>"
                                class="btn-remove-item"
                                onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                Xóa
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h2 class="cart-summary-title">Tổng đơn hàng</h2>
                <div class="cart-summary-row">
                    <span class="cart-summary-label">Tạm tính:</span>
                    <span class="cart-summary-value"><?php echo number_format($total, 0, ',', '.'); ?> VNĐ</span>
                </div>
                <div class="cart-summary-row">
                    <span class="cart-summary-label">Phí vận chuyển:</span>
                    <span class="cart-summary-value">Miễn phí</span>
                </div>
                <div class="cart-summary-total">
                    <span class="cart-total-label">Tổng cộng:</span>
                    <span class="cart-total-value"><?php echo number_format($total, 0, ',', '.'); ?> VNĐ</span>
                </div>

                <form method="POST" action="index.php?controller=cart&action=checkout" class="cart-checkout-form">
                    <button type="submit" class="btn-checkout">Thanh toán</button>
                </form>

                <a href="/" class="btn-continue-shopping">Tiếp tục mua sắm</a>

                <a href="index.php?controller=cart&action=clear"
                    class="btn-clear-cart"
                    onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                    Xóa giỏ hàng
                </a>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <p>Hãy đăng nhập để dùng giỏ hàng.</p>
        <?php return; ?>
    <?php endif; ?>
</div>