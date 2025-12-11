<style>
    .success-container {
        max-width: 700px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .success-card {
        background: white;
        border-radius: 16px;
        padding: 50px 40px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .success-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 25px;
        background: linear-gradient(135deg, #28a745 0%, #34ce57 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: scaleIn 0.5s ease-out 0.3s both;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
        }

        to {
            transform: scale(1);
        }
    }

    .success-icon svg {
        width: 50px;
        height: 50px;
        stroke: white;
        stroke-width: 3;
        fill: none;
    }

    .success-title {
        font-size: 32px;
        color: #28a745;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .success-subtitle {
        font-size: 16px;
        color: #666;
        margin-bottom: 30px;
    }

    .order-info-grid {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 25px;
        margin: 30px 0;
        text-align: left;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #666;
        font-size: 14px;
    }

    .info-value {
        color: #333;
        font-weight: 600;
        font-size: 14px;
    }

    .info-value.highlight {
        color: #ff6b00;
        font-size: 20px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-action {
        flex: 1;
        padding: 15px 30px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
    }

    .btn-primary-action {
        background: #0033a0;
        color: white;
    }

    .btn-primary-action:hover {
        background: #002780;
        transform: translateY(-2px);
    }

    .btn-secondary-action {
        background: white;
        color: #0033a0;
        border: 2px solid #0033a0;
    }

    .btn-secondary-action:hover {
        background: #0033a0;
        color: white;
    }

    .confetti {
        position: fixed;
        width: 10px;
        height: 10px;
        background: #ff6b00;
        position: absolute;
        animation: confetti-fall 3s linear;
    }

    @keyframes confetti-fall {
        to {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }

    @media (max-width: 768px) {
        .success-card {
            padding: 40px 20px;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="success-container">
    <div class="success-card">
        <div class="success-icon">
            <svg viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <h1 class="success-title">✅ Thanh toán thành công!</h1>
        <p class="success-subtitle">
            Cảm ơn bạn đã tin tưởng và mua sắm tại Vinamilk
        </p>

        <div class="order-info-grid">
            <div class="info-row">
                <span class="info-label">📦 Mã đơn hàng:</span>
                <span class="info-value">#<?php echo str_pad($paymentData['order_id'], 6, '0', STR_PAD_LEFT); ?></span>
            </div>

            <div class="info-row">
                <span class="info-label">💳 Mã giao dịch:</span>
                <span class="info-value"><?php echo htmlspecialchars($paymentData['transaction_no']); ?></span>
            </div>

            <div class="info-row">
                <span class="info-label">🏦 Ngân hàng:</span>
                <span class="info-value"><?php echo htmlspecialchars($paymentData['bank_code']); ?></span>
            </div>

            <div class="info-row">
                <span class="info-label">🕐 Thời gian:</span>
                <span class="info-value"><?php echo date('d/m/Y H:i:s'); ?></span>
            </div>

            <div class="info-row">
                <span class="info-label">💰 Số tiền:</span>
                <span class="info-value highlight"><?php echo number_format($paymentData['amount'], 0, ',', '.'); ?>₫</span>
            </div>
        </div>

        <div class="order-info-grid" style="background: #e6f7ff; border: 2px dashed #0033a0;">
            <p style="margin: 0; font-size: 14px; color: #0033a0; line-height: 1.6;">
                <strong>📧 Thông báo đã được gửi đến email của bạn</strong><br>
                Đơn hàng sẽ được xử lý và giao đến bạn trong 2-3 ngày làm việc
            </p>
        </div>

        <div class="action-buttons">
            <a href="index.php?controller=order&action=detail&id=<?php echo $paymentData['order_id']; ?>" class="btn-action btn-primary-action">
                📋 Xem chi tiết đơn hàng
            </a>
            <a href="index.php" class="btn-action btn-secondary-action">
                🛍️ Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>

<script>
    // Create confetti effect
    function createConfetti() {
        const colors = ['#ff6b00', '#0033a0', '#28a745', '#ffc107'];
        const confettiCount = 50;

        for (let i = 0; i < confettiCount; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * window.innerWidth + 'px';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 3 + 's';
                document.body.appendChild(confetti);

                setTimeout(() => confetti.remove(), 3000);
            }, i * 30);
        }
    }

    // Trigger confetti on load
    window.addEventListener('load', createConfetti);
</script>