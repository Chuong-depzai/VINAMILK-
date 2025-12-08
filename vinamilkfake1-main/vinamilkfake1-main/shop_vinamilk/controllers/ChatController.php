<?php
// controllers/ChatController.php
require_once __DIR__ . '/../services/GeminiService.php';
require_once __DIR__ . '/../db.php';

class ChatController
{
    private $geminiService;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->geminiService = new GeminiService();
    }

    /**
     * API: Gửi tin nhắn
     */
    public function send()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $message = trim($input['message'] ?? '');

        if (empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập tin nhắn']);
            exit;
        }

        // Gửi tới Gemini Service
        $response = $this->geminiService->sendMessage($message);

        // Format sản phẩm để trả về frontend
        $products = [];
        if (!empty($response['products'])) {
            foreach ($response['products'] as $p) {
                $products[] = [
                    'id' => $p['id'],
                    'name' => $p['name'],
                    'price' => $p['price'],
                    'image' => $p['image'],
                    'description' => mb_substr($p['description'] ?? '', 0, 100) . '...',
                    'type' => $p['type'] ?? ''
                ];
            }
        }

        // Lưu lịch sử nếu đã đăng nhập
        if (isset($_SESSION['user_id']) && $response['success']) {
            $this->geminiService->saveChat(
                $_SESSION['user_id'],
                $message,
                $response['message']
            );
        }

        echo json_encode([
            'success' => $response['success'],
            'message' => $response['message'],
            'products' => $products,
            'intent' => $response['intent'] ?? 'general'
        ]);
        exit;
    }

    /**
     * Lấy lịch sử chat
     */
    public function history()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'history' => []]);
            exit;
        }

        $history = $this->geminiService->getChatHistory($_SESSION['user_id']);
        echo json_encode(['success' => true, 'history' => $history]);
        exit;
    }

    /**
     * Xóa lịch sử
     */
    public function clearHistory()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }

        try {
            $db = getDB();
            $sql = "DELETE FROM chat_history WHERE user_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$_SESSION['user_id']]);
            echo json_encode(['success' => true, 'message' => 'Đã xóa lịch sử']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
        exit;
    }
}
