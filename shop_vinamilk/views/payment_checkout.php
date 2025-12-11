<style>
    .checkout-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 30px;
    }

    .checkout-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 20px;
        color: #0033a0;
        margin-bottom: 20px;
        font-weight: 700;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 30px;
    }

    .payment-method {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .payment-method:hover {
        border-color: #0033a0;
        background: #f8f9ff;
    }

    .payment-method.selected {
        border-color: #0033a0;
        background: #e6f0ff;
    }

    .payment-method input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .payment-method-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
    }

    .payment-icon {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: contain;
        background: #f9f9f9;
        padding: 5px;
    }

    .payment-name {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .payment-desc {
        font-size: 13px;
        color: #666;
        margin-left: 65px;
    }

    .bank-selection {
        display: none;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e0e0e0;
    }

    .payment-method.selected .bank-selection {
        display: block;
    }

    .bank-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 10px;
    }

    .bank-item {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .bank-item:hover {
        border-color: #0033a0;
        transform: scale(1.05);
    }

    .bank-item.selected {
        border-color: #0033a0;
        background: #e6f0ff;
    }

    .bank-item input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .bank-logo {
        width: 40px;
        height: 40px;
        object-fit: contain;
        margin: 0 auto 5px;
    }

    .bank-name {
        font-size: 11px;
        color: #666;
    }

    .order-summary {
        position: sticky;
        top: 100px;
    }

    .summary-items {
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 20px;
    }

    .summary-item {
        display: flex;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .summary-item-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        background: #f9f9f9;
    }

    .summary-item-info {
        flex: 1;
    }

    .summary-item-name {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .summary-item-price {
        font-size: 13px;
        color: #666;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        font-size: 15px;
    }

    .summary-total {
        border-top: 2px solid #0033a0;
        margin-top: 15px;
        padding-top: 20px;
        font-size: 18px;
        font-weight: 700;
        color: #0033a0;
    }

    .btn-submit-payment {
        width: 100%;
        padding: 18px;
        background: linear-gradient(135deg, #0033a0 0%, #0055ff 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 20px;
    }

    .btn-submit-payment:hover {
        background: linear-gradient(135deg, #002780 0%, #0044dd 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 51, 160, 0.3);
    }

    .btn-submit-payment:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .security-note {
        background: #f0f8ff;
        border-left: 4px solid #0033a0;
        padding: 15px;
        margin-top: 20px;
        font-size: 13px;
        color: #666;
        border-radius: 6px;
    }

    @media (max-width: 768px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }

        .order-summary {
            position: static;
        }

        .bank-grid {
            grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
        }
    }
</style>

<div class="checkout-container">
    <h1 style="text-align: center; color: #0033a0; margin-bottom: 40px;">🛒 Thanh toán đơn hàng</h1>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($_SESSION['error_message']);
            unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <div class="checkout-grid">
        <!-- Left: Payment Methods -->
        <div class="checkout-section">
            <h2 class="section-title">📱 Chọn phương thức thanh toán</h2>

            <form id="paymentForm" method="POST">
                <!-- Thông tin giao hàng -->
                <div style="margin-bottom: 30px;">
                    <h3 style="font-size: 16px; color: #333; margin-bottom: 15px;">📦 Thông tin giao hàng</h3>

                    <div class="form-group">
                        <label class="form-label">Họ tên người nhận <span style="color: red;">*</span></label>
                        <input type="text" name="shipping_name" class="form-input"
                            value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Số điện thoại <span style="color: red;">*</span></label>
                        <input type="tel" name="shipping_phone" class="form-input"
                            value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Địa chỉ giao hàng <span style="color: red;">*</span></label>
                        <textarea name="shipping_address" class="form-textarea" rows="3" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-textarea" rows="2" placeholder="Ghi chú cho đơn hàng..."></textarea>
                    </div>
                </div>

                <h3 style="font-size: 16px; color: #333; margin-bottom: 15px;">💳 Phương thức thanh toán</h3>

                <div class="payment-methods">
                    <!-- VNPay QR -->
                    <label class="payment-method" data-method="vnpay">
                        <input type="radio" name="payment_method" value="vnpay" checked>
                        <div class="payment-method-header">
                            <img src="https://vnpay.vn/s1/statics.vnpay.vn/2023/9/06ncktiwd6dc1694418196384.png" class="payment-icon">
                            <div>
                                <div class="payment-name">💳 Thanh toán VNPay</div>
                                <div class="payment-desc">Quét mã QR hoặc thanh toán bằng thẻ/tài khoản</div>
                            </div>
                        </div>

                        <div class="bank-selection">
                            <div class="bank-grid">
                                <label class="bank-item selected">
                                    <input type="radio" name="bank_code" value="VNPAYQR" checked>
                                    <img src="https://vnpay.vn/s1/statics.vnpay.vn/2023/9/06ncktiwd6dc1694418196384.png" class="bank-logo">
                                    <div class="bank-name">QR Code</div>
                                </label>

                                <label class="bank-item">
                                    <input type="radio" name="bank_code" value="VNBANK">
                                    <img src="https://api.vietqr.io/img/ICB.png" class="bank-logo">
                                    <div class="bank-name">ATM</div>
                                </label>

                                <label class="bank-item">
                                    <input type="radio" name="bank_code" value="INTCARD">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="bank-logo">
                                    <div class="bank-name">Visa/Master</div>
                                </label>

                                <?php foreach ($banks as $code => $bank): ?>
                                    <?php if (!in_array($code, ['VNPAYQR', 'VNBANK', 'INTCARD'])): ?>
                                        <label class="bank-item">
                                            <input type="radio" name="bank_code" value="<?php echo $code; ?>">
                                            <img src="<?php echo $bank['logo']; ?>" class="bank-logo">
                                            <div class="bank-name"><?php echo substr($bank['name'], 0, 15); ?></div>
                                        </label>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </label>

                    <!-- COD -->
                    <label class="payment-method" data-method="cod">
                        <input type="radio" name="payment_method" value="cod">
                        <div class="payment-method-header">
                            <div style="width: 50px; height: 50px; border-radius: 8px; background: #f9f9f9; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                💵
                            </div>
                            <div>
                                <div class="payment-name">Thanh toán khi nhận hàng (COD)</div>
                                <div class="payment-desc">Thanh toán bằng tiền mặt khi nhận hàng</div>
                            </div>
                        </div>
                    </label>
                </div>

                <div class="security-note">
                    🔒 <strong>Giao dịch bảo mật:</strong> Thông tin thanh toán của bạn được mã hóa và bảo mật tuyệt đối
                </div>

                <button type="submit" class="btn-submit-payment" id="btnSubmit">
                    <span id="btnText">Xác nhận thanh toán</span>
                </button>
            </form>
        </div>

        <!-- Right: Order Summary -->
        <div class="checkout-section order-summary">
            <h2 class="section-title">📋 Chi tiết đơn hàng</h2>

            <div class="summary-items">
                <?php foreach ($cartItems as $item): ?>
                    <div class="summary-item">
                        <?php if (!empty($item['image']) && file_exists(__DIR__ . '/../uploads/' . $item['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" class="summary-item-image">
                        <?php else: ?>
                            <div class="summary-item-image" style="background: #e0e0e0; display: flex; align-items: center; justify-content: center; color: #999;">N/A</div>
                        <?php endif; ?>

                        <div class="summary-item-info">
                            <div class="summary-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="summary-item-price">
                                <?php echo number_format($item['price'], 0, ',', '.'); ?>₫ × <?php echo $item['quantity']; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="summary-row">
                <span>Tạm tính:</span>
                <span><?php echo number_format($total, 0, ',', '.'); ?>₫</span>
            </div>

            <div class="summary-row">
                <span>Phí vận chuyển:</span>
                <span style="color: #28a745;">Miễn phí</span>
            </div>

            <div class="summary-row summary-total">
                <span>Tổng cộng:</span>
                <span style="color: #ff6b00;"><?php echo number_format($total, 0, ',', '.'); ?>₫</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('paymentForm');
        const btnSubmit = document.getElementById('btnSubmit');
        const btnText = document.getElementById('btnText');

        // Handle payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input[type="radio"]').checked = true;

                const selectedMethod = this.querySelector('input[name="payment_method"]').value;
                updateSubmitButton(selectedMethod);
            });
        });

        // Handle bank selection
        document.querySelectorAll('.bank-item').forEach(bank => {
            bank.addEventListener('click', function(e) {
                e.stopPropagation();
                document.querySelectorAll('.bank-item').forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input[type="radio"]').checked = true;
            });
        });

        // Update submit button text
        function updateSubmitButton(method) {
            if (method === 'vnpay') {
                btnText.textContent = '🔐 Thanh toán với VNPay';
            } else {
                btnText.textContent = '✅ Xác nhận đặt hàng';
            }
        }

        // Handle form submit
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

            // Change action based on payment method
            if (selectedMethod === 'vnpay') {
                form.action = 'index.php?controller=payment&action=processVNPay';
            } else {
                form.action = 'index.php?controller=payment&action=processCOD';
            }

            // Disable submit button
            btnSubmit.disabled = true;
            btnText.textContent = '⏳ Đang xử lý...';

            // Submit form
            form.submit();
        });
    });
</script>