<!-- views/chatbox_widget.php -->
<style>
    /* ===== CHATBOX WIDGET ===== */
    #chat-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        font-family: 'Segoe UI', Arial, sans-serif;
    }

    #chat-button {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0066FF 0%, #00CCFF 100%);
        color: white;
        border: none;
        box-shadow: 0 8px 30px rgba(0, 102, 255, 0.4);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        transition: all 0.3s ease;
        animation: pulse 2s infinite;
    }

    #chat-button:hover {
        transform: scale(1.1);
        box-shadow: 0 10px 40px rgba(0, 102, 255, 0.6);
    }

    @keyframes pulse {

        0%,
        100% {
            box-shadow: 0 8px 30px rgba(0, 102, 255, 0.4);
        }

        50% {
            box-shadow: 0 8px 40px rgba(0, 102, 255, 0.7);
        }
    }

    #chat-window {
        position: fixed;
        bottom: 100px;
        right: 20px;
        width: 420px;
        height: 600px;
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: slideUp 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.9);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    #chat-window.active {
        display: flex;
    }

    /* ===== CHAT HEADER ===== */
    .chat-header {
        background: linear-gradient(135deg, #0066FF 0%, #00CCFF 100%);
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chat-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .chat-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .chat-status {
        font-size: 12px;
        opacity: 0.95;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #4ade80;
        border-radius: 50%;
        animation: blink 2s infinite;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.3;
        }
    }

    .chat-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s;
    }

    .chat-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    /* ===== CHAT MESSAGES ===== */
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: linear-gradient(to bottom, #f8fafc 0%, #e2e8f0 100%);
    }

    .chat-message {
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        animation: messageSlide 0.4s ease;
    }

    @keyframes messageSlide {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .user-message {
        flex-direction: row-reverse;
        animation: messageSlideRight 0.4s ease;
    }

    @keyframes messageSlideRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .message-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .bot-avatar {
        background: linear-gradient(135deg, #0066FF 0%, #00CCFF 100%);
        color: white;
    }

    .user-avatar {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
    }

    .message-content {
        flex: 1;
        max-width: 75%;
    }

    .message-bubble {
        padding: 14px 18px;
        border-radius: 20px;
        max-width: 100%;
        word-wrap: break-word;
        line-height: 1.6;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .bot-message .message-bubble {
        background: white;
        color: #1e293b;
        border: 1px solid #e2e8f0;
    }

    .user-message .message-bubble {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
    }

    .message-time {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
        padding: 0 4px;
    }

    /* ===== PRODUCT CARDS ===== */
    .product-cards {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 12px;
    }

    .product-card {
        background: white;
        border-radius: 16px;
        padding: 14px;
        display: flex;
        gap: 14px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .product-card:hover {
        border-color: #0066FF;
        box-shadow: 0 6px 20px rgba(0, 102, 255, 0.15);
        transform: translateY(-2px);
    }

    .product-image {
        width: 75px;
        height: 75px;
        border-radius: 12px;
        object-fit: cover;
        background: #f1f5f9;
        flex-shrink: 0;
    }

    .product-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .product-name {
        font-weight: 600;
        font-size: 14px;
        color: #1e293b;
        margin: 0;
        line-height: 1.3;
    }

    .product-desc {
        font-size: 12px;
        color: #64748b;
        margin: 0;
        line-height: 1.4;
    }

    .product-price {
        font-size: 16px;
        font-weight: 700;
        color: #0066FF;
        margin-top: 4px;
    }

    .product-actions {
        display: flex;
        gap: 8px;
        margin-top: 8px;
    }

    .btn-view {
        flex: 1;
        padding: 8px 12px;
        background: #f1f5f9;
        color: #334155;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-view:hover {
        background: #e2e8f0;
    }

    .btn-add-cart {
        flex: 1;
        padding: 8px 12px;
        background: linear-gradient(135deg, #0066FF 0%, #00CCFF 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-add-cart:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 102, 255, 0.3);
    }

    /* ===== TYPING INDICATOR ===== */
    .typing-indicator {
        display: none;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        background: linear-gradient(to bottom, #f8fafc 0%, #e2e8f0 100%);
    }

    .typing-indicator.active {
        display: flex;
    }

    .typing-dots {
        display: flex;
        gap: 6px;
    }

    .typing-dots span {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #0066FF;
        animation: typingBounce 1.4s infinite;
    }

    .typing-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typingBounce {

        0%,
        60%,
        100% {
            transform: translateY(0);
        }

        30% {
            transform: translateY(-12px);
        }
    }

    /* ===== CHAT INPUT ===== */
    .chat-input-area {
        padding: 16px;
        background: white;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 12px;
    }

    #chat-input {
        flex: 1;
        padding: 14px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 24px;
        font-size: 14px;
        outline: none;
        transition: all 0.3s;
        font-family: inherit;
    }

    #chat-input:focus {
        border-color: #0066FF;
        box-shadow: 0 0 0 4px rgba(0, 102, 255, 0.1);
    }

    #chat-send {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0066FF 0%, #00CCFF 100%);
        color: white;
        border: none;
        cursor: pointer;
        font-size: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        flex-shrink: 0;
    }

    #chat-send:hover:not(:disabled) {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 102, 255, 0.4);
    }

    #chat-send:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* ===== WELCOME MESSAGE ===== */
    .welcome-message {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }

    .welcome-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .welcome-message h4 {
        color: #0066FF;
        margin: 0 0 12px 0;
        font-size: 20px;
    }

    .welcome-message p {
        margin: 8px 0;
        line-height: 1.6;
    }

    .welcome-features {
        text-align: left;
        display: inline-block;
        margin-top: 20px;
        background: white;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .welcome-features li {
        margin: 8px 0;
        color: #475569;
    }

    /* ===== SCROLLBAR ===== */
    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: #0066FF;
        border-radius: 3px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    /* ===== MOBILE RESPONSIVE ===== */
    @media (max-width: 480px) {
        #chat-window {
            width: calc(100vw - 20px);
            height: calc(100vh - 120px);
            right: 10px;
            bottom: 90px;
        }

        .product-card {
            flex-direction: column;
        }

        .product-image {
            width: 100%;
            height: 150px;
        }
    }
</style>

<!-- Chat Widget HTML -->
<div id="chat-widget">
    <button id="chat-button" aria-label="Mở chat">
        💬
    </button>

    <div id="chat-window">
        <div class="chat-header">
            <div class="chat-header-info">
                <div class="chat-avatar">🤖</div>
                <div>
                    <h3>Trợ lý AI Vinamilk</h3>
                    <div class="chat-status">
                        <span class="status-dot"></span>
                        Đang online
                    </div>
                </div>
            </div>
            <button class="chat-close" aria-label="Đóng chat">×</button>
        </div>

        <div class="chat-messages" id="chat-messages">
            <div class="welcome-message">
                <div class="welcome-icon">🎉</div>
                <h4>Xin chào! Mình là Vinabot</h4>
                <p>Tôi có thể giúp bạn:</p>
                <div class="welcome-features">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li>🥛 Tư vấn sản phẩm sữa</li>
                        <li>🔍 Tìm kiếm & gợi ý</li>
                        <li>🛒 Hỗ trợ đặt hàng</li>
                        <li>💬 Giải đáp thắc mắc</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="typing-indicator" id="typing-indicator">
            <div class="message-avatar bot-avatar">🤖</div>
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <div class="chat-input-area">
            <input
                type="text"
                id="chat-input"
                placeholder="Hỏi tôi bất cứ điều gì..."
                autocomplete="off">
            <button id="chat-send" aria-label="Gửi">
                ➤
            </button>
        </div>
    </div>
</div>

<script>
    // Chat Widget JavaScript
    (function() {
        const chatButton = document.getElementById('chat-button');
        const chatWindow = document.getElementById('chat-window');
        const chatClose = document.querySelector('.chat-close');
        const chatMessages = document.getElementById('chat-messages');
        const chatInput = document.getElementById('chat-input');
        const chatSend = document.getElementById('chat-send');
        const typingIndicator = document.getElementById('typing-indicator');

        // Toggle chat window
        chatButton.addEventListener('click', () => {
            chatWindow.classList.toggle('active');
            if (chatWindow.classList.contains('active')) {
                chatInput.focus();
            }
        });

        chatClose.addEventListener('click', () => {
            chatWindow.classList.remove('active');
        });

        // Send message
        async function sendMessage() {
            const message = chatInput.value.trim();
            if (!message) return;

            chatInput.disabled = true;
            chatSend.disabled = true;

            addMessage(message, 'user');
            chatInput.value = '';

            typingIndicator.classList.add('active');
            scrollToBottom();

            try {
                const response = await fetch('index.php?controller=chat&action=send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message
                    })
                });

                const data = await response.json();
                typingIndicator.classList.remove('active');

                if (data.success) {
                    addMessage(data.message, 'bot', data.products);
                } else {
                    addMessage('Xin lỗi, tôi không thể trả lời lúc này.', 'bot');
                }
            } catch (error) {
                console.error('Error:', error);
                typingIndicator.classList.remove('active');
                addMessage('Có lỗi xảy ra. Vui lòng thử lại.', 'bot');
            }

            chatInput.disabled = false;
            chatSend.disabled = false;
            chatInput.focus();
        }

        // Add message
        function addMessage(text, sender, products = []) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `chat-message ${sender}-message`;

            const avatar = document.createElement('div');
            avatar.className = `message-avatar ${sender}-avatar`;
            avatar.textContent = sender === 'bot' ? '🤖' : '👤';

            const contentDiv = document.createElement('div');
            contentDiv.className = 'message-content';

            const bubble = document.createElement('div');
            bubble.className = 'message-bubble';
            bubble.textContent = text;

            const time = document.createElement('div');
            time.className = 'message-time';
            time.textContent = new Date().toLocaleTimeString('vi-VN', {
                hour: '2-digit',
                minute: '2-digit'
            });

            contentDiv.appendChild(bubble);
            contentDiv.appendChild(time);

            // Add product cards if exists
            if (products && products.length > 0) {
                const productsDiv = document.createElement('div');
                productsDiv.className = 'product-cards';

                products.forEach(product => {
                    const card = createProductCard(product);
                    productsDiv.appendChild(card);
                });

                contentDiv.appendChild(productsDiv);
            }

            messageDiv.appendChild(avatar);
            messageDiv.appendChild(contentDiv);

            chatMessages.appendChild(messageDiv);
            scrollToBottom();
        }

        // Create product card
        function createProductCard(product) {
            const card = document.createElement('div');
            card.className = 'product-card';

            card.innerHTML = `
            <img src="uploads/${product.image}" alt="${product.name}" class="product-image">
            <div class="product-info">
                <h4 class="product-name">${product.name}</h4>
                <p class="product-desc">${product.description}</p>
                <div class="product-price">${formatPrice(product.price)} VNĐ</div>
                <div class="product-actions">
                    <button class="btn-view" onclick="viewProduct(${product.id})">
                        👁️ Xem chi tiết
                    </button>
                    <button class="btn-add-cart" onclick="addToCart(${product.id})">
                        🛒 Thêm giỏ hàng
                    </button>
                </div>
            </div>
        `;

            return card;
        }

        // Format price
        function formatPrice(price) {
            return new Intl.NumberFormat('vi-VN').format(price);
        }

        // Global functions
        window.viewProduct = function(productId) {
            window.location.href = `index.php?controller=product&action=show&id=${productId}`;
        };

        window.addToCart = function(productId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php?controller=cart&action=add';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'product_id';
            input.value = productId;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        };

        // Scroll to bottom
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Event listeners
        chatSend.addEventListener('click', sendMessage);

        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        // Auto-focus
        const observer = new MutationObserver(() => {
            if (chatWindow.classList.contains('active')) {
                chatInput.focus();
            }
        });

        observer.observe(chatWindow, {
            attributes: true,
            attributeFilter: ['class']
        });
    })();
</script>