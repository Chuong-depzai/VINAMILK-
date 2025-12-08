/* ============================================
   VINAMILK - MAIN JAVASCRIPT
   ============================================ */

// Initialize on DOM Ready
document.addEventListener('DOMContentLoaded', function () {
    initHeader();
    initSearch();
    initWishlist();
    initCart();
    initNotifications();
});

/* ============================================
   HEADER FUNCTIONS
   ============================================ */
function initHeader() {
    const header = document.querySelector('.site-header');
    let lastScrollTop = 0;
    const scrollThreshold = 50;

    window.addEventListener('scroll', function () {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > scrollThreshold) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        lastScrollTop = scrollTop;
    }, { passive: true });
}

/* ============================================
   SEARCH FUNCTIONS
   ============================================ */
function initSearch() {
    const searchBtn = document.querySelector('.search-btn');
    const searchOverlay = document.getElementById('searchOverlay');
    const searchClose = document.querySelector('.search-close');
    const searchInput = document.querySelector('.search-input');

    if (!searchBtn || !searchOverlay) return;

    searchBtn.addEventListener('click', toggleSearch);
    searchClose?.addEventListener('click', toggleSearch);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
            toggleSearch();
        }
    });

    searchOverlay?.addEventListener('click', function (e) {
        if (e.target.id === 'searchOverlay') {
            toggleSearch();
        }
    });
}

function toggleSearch() {
    const overlay = document.getElementById('searchOverlay');
    const input = document.querySelector('.search-input');

    overlay.classList.toggle('active');

    if (overlay.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
        setTimeout(() => input?.focus(), 100);
    } else {
        document.body.style.overflow = '';
    }
}

/* ============================================
   WISHLIST FUNCTIONS
   ============================================ */
function initWishlist() {
    loadWishlistCount();
}

function loadWishlistCount() {
    fetch('index.php?controller=wishlist&action=getCount')
        .then(response => response.json())
        .then(data => {
            updateWishlistCount(data.count);
        })
        .catch(error => console.error('Error loading wishlist count:', error));
}

function updateWishlistCount(count) {
    const badge = document.getElementById('wishlist-count-badge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }
}

async function toggleWishlist(button, productId) {
    button.disabled = true;

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
                button.textContent = '❤️';
                button.classList.add('active');
            } else {
                button.textContent = '🤍';
                button.classList.remove('active');
            }

            updateWishlistCount(data.count);
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
        button.disabled = false;
    }
}

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
                location.reload();
            } else {
                showNotification(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra khi xóa sản phẩm', 'error');
        });
}

/* ============================================
   CART FUNCTIONS
   ============================================ */
function initCart() {
    const cartBtn = document.querySelector('.cart-btn');
    if (cartBtn) {
        cartBtn.addEventListener('click', function (e) {
            // Redirect to cart view
            window.location.href = 'index.php?controller=cart&action=view';
        });
    }
}

function updateCartCount(count) {
    const badge = document.querySelector('.cart-btn .badge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }
}

/* ============================================
   NOTIFICATION FUNCTIONS
   ============================================ */
function initNotifications() {
    // Remove auto-generated notifications after 5s
    const notifications = document.querySelectorAll('.notification.show');
    notifications.forEach(notification => {
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    });
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
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
        border-left: 4px solid ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#ffc107'};
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.add('show');
    }, 100);

    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

/* ============================================
   SMOOTH SCROLL
   ============================================ */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            document.querySelector(href).scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

/* ============================================
   TAB SWITCHING
   ============================================ */
function switchTab(tabName) {
    const tabs = document.querySelectorAll('.tab-content');
    const buttons = document.querySelectorAll('.tab-btn');

    tabs.forEach(tab => tab.classList.remove('active'));
    buttons.forEach(btn => btn.classList.remove('active'));

    const activeTab = document.getElementById('tab-' + tabName);
    if (activeTab) {
        activeTab.classList.add('active');
    }

    event.target.classList.add('active');
}

/* ============================================
   FILTER FUNCTIONS
   ============================================ */
function filterOrders(status) {
    const cards = document.querySelectorAll('.order-card');
    const buttons = document.querySelectorAll('.filter-btn');

    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');

    cards.forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

/* ============================================
   FORM VALIDATION
   ============================================ */
function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validatePhone(phone) {
    const regex = /^[0-9]{10}$/;
    return regex.test(phone);
}

/* ============================================
   UTILITY FUNCTIONS
   ============================================ */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

function formatDate(date) {
    return new Intl.DateTimeFormat('vi-VN').format(new Date(date));
}