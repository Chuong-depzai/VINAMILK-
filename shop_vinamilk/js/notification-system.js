/**
 * FILE MỚI: shop_vinamilk/js/notification-system.js
 * Hệ thống thông báo real-time với animation xịn
 */

class NotificationSystem {
    constructor() {
        this.container = this.createContainer();
        this.queue = [];
        this.isProcessing = false;
    }

    createContainer() {
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 100000;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 400px;
            pointer-events: none;
        `;
        document.body.appendChild(container);
        return container;
    }

    show(message, type = 'info', duration = 3000, options = {}) {
        this.queue.push({ message, type, duration, options });
        if (!this.isProcessing) {
            this.processQueue();
        }
    }

    async processQueue() {
        if (this.queue.length === 0) {
            this.isProcessing = false;
            return;
        }

        this.isProcessing = true;
        const { message, type, duration, options } = this.queue.shift();

        const notification = this.createNotification(message, type, options);
        this.container.appendChild(notification);

        // Trigger entrance animation
        setTimeout(() => notification.classList.add('show'), 10);

        // Auto remove
        setTimeout(() => {
            notification.classList.remove('show');
            notification.classList.add('hide');
            setTimeout(() => {
                notification.remove();
                this.processQueue();
            }, 300);
        }, duration);
    }

    createNotification(message, type, options) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.style.cssText = `
            background: white;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 320px;
            transform: translateX(450px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            pointer-events: auto;
            cursor: pointer;
        `;

        // Icon & Color
        const config = {
            success: { icon: '✅', color: '#28a745', bg: '#d4edda' },
            error: { icon: '❌', color: '#dc3545', bg: '#f8d7da' },
            warning: { icon: '⚠️', color: '#ffc107', bg: '#fff3cd' },
            info: { icon: 'ℹ️', color: '#17a2b8', bg: '#d1ecf1' }
        };

        const { icon, color, bg } = config[type] || config.info;

        notification.innerHTML = `
            <div style="
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: ${bg};
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                flex-shrink: 0;
            ">${icon}</div>
            <div style="flex: 1;">
                <div style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 2px;">
                    ${options.title || this.getDefaultTitle(type)}
                </div>
                <div style="font-size: 13px; color: #666;">
                    ${message}
                </div>
            </div>
            <div style="
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: #f0f0f0;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s;
                flex-shrink: 0;
            " 
            class="close-btn"
            onmouseover="this.style.background='#e0e0e0'"
            onmouseout="this.style.background='#f0f0f0'"
            >×</div>
        `;

        notification.style.borderLeft = `4px solid ${color}`;

        // Show animation
        notification.classList.add('show');

        // Close button
        notification.querySelector('.close-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            notification.classList.remove('show');
            notification.classList.add('hide');
            setTimeout(() => notification.remove(), 300);
        });

        // Click to dismiss
        notification.addEventListener('click', () => {
            if (options.onClick) {
                options.onClick();
            }
        });

        return notification;
    }

    getDefaultTitle(type) {
        const titles = {
            success: 'Thành công!',
            error: 'Lỗi!',
            warning: 'Cảnh báo!',
            info: 'Thông tin'
        };
        return titles[type] || 'Thông báo';
    }

    success(message, options = {}) {
        this.show(message, 'success', 3000, options);
    }

    error(message, options = {}) {
        this.show(message, 'error', 4000, options);
    }

    warning(message, options = {}) {
        this.show(message, 'warning', 3500, options);
    }

    info(message, options = {}) {
        this.show(message, 'info', 3000, options);
    }
}

// Add animations
const style = document.createElement('style');
style.textContent = `
    .notification.show {
        transform: translateX(0) !important;
        opacity: 1 !important;
    }

    .notification.hide {
        transform: translateX(450px) scale(0.8) !important;
        opacity: 0 !important;
    }

    .notification:hover {
        transform: translateX(-5px) scale(1.02);
        box-shadow: 0 12px 32px rgba(0,0,0,0.2) !important;
    }

    @media (max-width: 768px) {
        #notification-container {
            left: 10px;
            right: 10px;
            max-width: unset;
        }

        .notification {
            min-width: unset !important;
        }
    }
`;
document.head.appendChild(style);

// Global instance
window.notify = new NotificationSystem();

