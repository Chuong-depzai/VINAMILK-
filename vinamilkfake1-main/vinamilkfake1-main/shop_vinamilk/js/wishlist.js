// js/wishlist.js - Global Wishlist Functions

/**
 * Toggle wishlist (thêm/xóa)
 */
function toggleWishlist(productId, buttonElement) {
    const formData = new FormData();
    formData.append('product_id', productId);

    // Disable button
    buttonElement.disabled = true;

    fetch('index.php?controller=wishlist&action=toggle', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update button state
                if (data.action === 'added') {
                    buttonElement.classList.add('active');
                    buttonElement.innerHTML = '❤️ Đã yêu thích';
                } else {
                    buttonElement.classList.remove('active');
                    buttonElement.innerHTML = '🤍 Yêu thích';
                }

                // Update wishlist count in header
                updateWishlistCount(data.count);

                // Show notification
                showNotification(data.message, 'success');
            } else {
                if (data.redirect) {
                    // Redirect to login
                    window.location.href = data.redirect;
                } else {
                    showNotification(data.message, 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra', 'error');
        })
        .finally(() => {
            buttonElement.disabled = false;
        });
}

/**
 * Update wishlist count badge
 */
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

/**
 * Show notification toast
 */
function showNotification(message, type = 'success') {
    // Tạo notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    // Thêm vào body
    document.body.appendChild(notification);

    // Hiển thị
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);

    // Tự động ẩn sau 3s
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

/**
 * Load wishlist count when page loads
 */
document.addEventListener('DOMContentLoaded', function () {
    fetch('index.php?controller=wishlist&action=getCount')
        .then(response => response.json())
        .then(data => {
            updateWishlistCount(data.count);
        })
        .catch(error => console.error('Error loading wishlist count:', error));
});