/**
 * FILE MỚI: shop_vinamilk/js/cart-counter.js
 * Tự động cập nhật số lượng giỏ hàng & wishlist
 */

class CartWishlistCounter {
    constructor() {
        this.cartBadge = document.getElementById('cart-count-badge');
        this.wishlistBadge = document.getElementById('wishlist-count-badge');
        this.init();
    }

    init() {
        // Update counters on page load
        this.updateAllCounters();

        // Setup event listeners
        this.setupEventListeners();

        // Auto-refresh every 30 seconds
        setInterval(() => this.updateAllCounters(), 30000);
    }

    setupEventListeners() {
        // Listen for cart/wishlist updates
        document.addEventListener('cart:updated', () => this.updateCartCount());
        document.addEventListener('wishlist:updated', () => this.updateWishlistCount());

        // Intercept add to cart buttons
        document.querySelectorAll('[data-action="add-to-cart"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = btn.dataset.productId || btn.closest('[data-product-id]')?.dataset.productId;
                if (productId) {
                    this.addToCart(productId);
                }
            });
        });
    }

    async updateAllCounters() {
        await Promise.all([
            this.updateCartCount(),
            this.updateWishlistCount()
        ]);
    }

    async updateCartCount() {
        try {
            const response = await fetch('index.php?controller=cart&action=getCount');
            const data = await response.json();

            if (this.cartBadge) {
                this.cartBadge.textContent = data.count;
                this.cartBadge.style.display = data.count > 0 ? 'flex' : 'none';
            }

            // Update cart panel if exists
            const cartPanel = document.getElementById('cartPanel');
            if (cartPanel) {
                const cartTitle = cartPanel.querySelector('.cart-panel-title');
                if (cartTitle) {
                    cartTitle.innerHTML = `🛒 Giỏ hàng (${data.count})`;
                }
            }

            return data.count;
        } catch (error) {
            console.error('Cart count update error:', error);
            return 0;
        }
    }

    async updateWishlistCount() {
        try {
            const response = await fetch('index.php?controller=wishlist&action=getCount');
            const data = await response.json();

            if (this.wishlistBadge) {
                this.wishlistBadge.textContent = data.count;
                this.wishlistBadge.style.display = data.count > 0 ? 'flex' : 'none';
            }

            // Update wishlist panel if exists
            const wishlistPanel = document.getElementById('wishlistPanel');
            if (wishlistPanel) {
                const wishlistTitle = wishlistPanel.querySelector('.cart-panel-title');
                if (wishlistTitle) {
                    wishlistTitle.innerHTML = `❤️ Yêu thích (${data.count})`;
                }
            }

            return data.count;
        } catch (error) {
            console.error('Wishlist count update error:', error);
            return 0;
        }
    }

    async addToCart(productId) {
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', 1);

        try {
            const response = await fetch('index.php?controller=cart&action=add', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                await this.updateCartCount();
                this.showNotification('✅ Đã thêm vào giỏ hàng', 'success');
                document.dispatchEvent(new Event('cart:updated'));
            } else {
                this.showNotification('❌ Không thể thêm vào giỏ hàng', 'error');
            }
        } catch (error) {
            console.error('Add to cart error:', error);
            this.showNotification('❌ Có lỗi xảy ra', 'error');
        }
    }

    showNotification(message, type = 'success') {
        // Remove existing notifications
        document.querySelectorAll('.cart-notification').forEach(n => n.remove());

        const notification = document.createElement('div');
        notification.className = `cart-notification cart-notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-icon">${type === 'success' ? '✅' : '❌'}</span>
                <span class="notification-message">${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        // Trigger animation
        setTimeout(() => notification.classList.add('show'), 10);

        // Auto remove
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
}

// Add CSS for notifications
const style = document.createElement('style');
style.textContent = `
    .cart-notification {
        position: fixed;
        top: 80px;
        right: -400px;
        background: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        z-index: 100000;
        transition: right 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        min-width: 300px;
        max-width: 400px;
    }

    .cart-notification.show {
        right: 20px;
    }

    .cart-notification-success {
        border-left: 4px solid #28a745;
    }

    .cart-notification-error {
        border-left: 4px solid #dc3545;
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .notification-icon {
        font-size: 24px;
    }

    .notification-message {
        font-size: 14px;
        font-weight: 500;
        color: #333;
    }

    @media (max-width: 768px) {
        .cart-notification {
            left: 10px;
            right: 10px;
            min-width: unset;
            max-width: unset;
        }

        .cart-notification.show {
            right: 10px;
        }
    }
`;
document.head.appendChild(style);

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.cartWishlistCounter = new CartWishlistCounter();
    });
} else {
    window.cartWishlistCounter = new CartWishlistCounter();
}