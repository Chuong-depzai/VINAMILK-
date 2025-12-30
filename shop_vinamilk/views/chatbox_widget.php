<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vinabot - Chat với Vinamilk</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        /* Button nổi */
        #chatButton {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #0033a0 0%, #0055ff 100%);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(0, 51, 160, 0.3);
            font-size: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 9999;
        }

        #chatButton:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(0, 51, 160, 0.4);
        }

        /* Chat Container */
        #chatWindow {
            position: fixed;
            bottom: 120px;
            right: 30px;
            width: 420px;
            height: 600px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.15);
            display: none;
            flex-direction: column;
            overflow: hidden;
            animation: slideUp 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 10000;
        }

        #chatWindow.active {
            display: flex;
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

        /* Header */
        .chat-header {
            background: linear-gradient(135deg, #0033a0 0%, #0055ff 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .chat-header-info h3 {
            font-size: 16px;
            margin-bottom: 4px;
        }

        .chat-header-info p {
            font-size: 12px;
            opacity: 0.9;
        }

        .chat-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .chat-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Messages Area */
        #chatMessages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #fafafa;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .message {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
            animation: messageIn 0.3s ease;
        }

        @keyframes messageIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.bot {
            justify-content: flex-start;
        }

        .message.user {
            justify-content: flex-end;
        }

        .message-bubble {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            line-height: 1.5;
            word-wrap: break-word;
        }

        .bot .message-bubble {
            background: white;
            color: #333;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .user .message-bubble {
            background: linear-gradient(135deg, #0033a0 0%, #0055ff 100%);
            color: white;
        }

        /* Input Area */
        .chat-input-area {
            padding: 15px;
            background: white;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }

        #chatInput {
            flex: 1;
            border: 2px solid #e0e0e0;
            border-radius: 24px;
            padding: 10px 16px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s;
            font-family: inherit;
        }

        #chatInput:focus {
            border-color: #0033a0;
        }

        #chatSend {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #0033a0 0%, #0055ff 100%);
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s;
            flex-shrink: 0;
        }

        #chatSend:hover:not(:disabled) {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 51, 160, 0.3);
        }

        #chatSend:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Welcome Message */
        .welcome-message {
            text-align: center;
            padding: 30px 20px;
            color: #999;
        }

        .welcome-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .welcome-message h4 {
            color: #0033a0;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .welcome-message p {
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 4px;
        }

        /* Scrollbar */
        #chatMessages::-webkit-scrollbar {
            width: 6px;
        }

        #chatMessages::-webkit-scrollbar-track {
            background: transparent;
        }

        #chatMessages::-webkit-scrollbar-thumb {
            background: #0033a0;
            border-radius: 3px;
        }

        @media (max-width: 480px) {
            #chatWindow {
                width: calc(100vw - 20px);
                height: 70vh;
                bottom: 80px;
                right: 10px;
            }

            #chatButton {
                bottom: 20px;
                right: 20px;
            }
        }
    </style>
</head>

<body>


    <!-- Chat Button -->
    <button id="chatButton">💬</button>

    <!-- Chat Window -->
    <div id="chatWindow">



        <!-- Header -->
        <div class="chat-header">
            <div class="chat-header-info">
                <h3>Vinabot</h3>
                <p>🟢 Online - Sẵn sàng hỗ trợ</p>
            </div>
            <button class="chat-close" onclick="toggleChat()">&times;</button>
        </div>

        <!-- Messages -->
        <div id="chatMessages">
            <div class="message bot">
                <div class="message-bubble">
                    <div class="welcome-icon">🎉</div>
                    <h4>Xin chào! Mình là Vinabot</h4>
                    <p style="font-size: 12px; margin: 8px 0 0;">Hỏi mình bất cứ điều gì về sản phẩm sữa Vinamilk! 🥛</p>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div class="chat-input-area">
            <input
                type="text"
                id="chatInput"
                placeholder="Nhập câu hỏi..."
                onkeypress="if(event.key==='Enter') sendMessage()">
            <button id="chatSend" onclick="sendMessage()">➤</button>
        </div>
    </div>

    <script>
        let isLoading = false;

        function toggleChat() {
            const chatWindow = document.getElementById('chatWindow');
            chatWindow.classList.toggle('active');
            if (chatWindow.classList.contains('active')) {
                document.getElementById('chatInput').focus();
            }
        }

        function sendMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();

            if (!message || isLoading) return;

            // Hiển thị tin nhắn người dùng
            displayMessage(message, 'user');
            input.value = '';

            // Gửi đến server
            isLoading = true;
            document.getElementById('chatSend').disabled = true;

            fetch('index.php?controller=chat&action=send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        displayMessage(data.message, 'bot');
                    } else {
                        displayMessage('Xin lỗi, tôi không thể trả lời lúc này', 'bot');
                    }
                })
                .catch(err => {
                    displayMessage('Có lỗi xảy ra. Vui lòng thử lại!', 'bot');
                })
                .finally(() => {
                    isLoading = false;
                    document.getElementById('chatSend').disabled = false;
                    document.getElementById('chatInput').focus();
                });
        }

        function displayMessage(text, sender) {
            const messagesDiv = document.getElementById('chatMessages');

            // Xóa welcome message nếu có
            const welcome = messagesDiv.querySelector('.welcome-message');
            if (welcome) welcome.remove();

            const msgDiv = document.createElement('div');
            msgDiv.className = `message ${sender}`;
            msgDiv.innerHTML = `<div class="message-bubble">${escapeHtml(text)}</div>`;
            messagesDiv.appendChild(msgDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Toggle chat khi click button
        document.getElementById('chatButton').addEventListener('click', toggleChat);
    </script>

</body>

</html>