<?php
// services/GeminiService.php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models/Product.php';

class GeminiService
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    /**
     * Phát hiện ý định người dùng
     */
    private function detectIntent($message)
    {
        $message = mb_strtolower($message, 'UTF-8');

        if (preg_match('/(chào|hello|hi|xin chào|hey)/i', $message)) {
            return 'greeting';
        }
        if (preg_match('/(tìm|gợi ý|tư vấn|cho|nên mua|giới thiệu|có)/i', $message)) {
            return 'product_search';
        }
        if (preg_match('/(giá|bao nhiêu|chi phí|đắt|rẻ)/i', $message)) {
            return 'price';
        }
        if (preg_match('/(giúp|hỗ trợ|làm gì|có thể)/i', $message)) {
            return 'help';
        }
        if (preg_match('/(đặt|mua|order|thanh toán)/i', $message)) {
            return 'order';
        }

        return 'general';
    }

    /**
     * Tìm sản phẩm phù hợp
     */
    private function findRelevantProducts($message, $limit = 3)
    {
        $message = mb_strtolower($message, 'UTF-8');
        $products = $this->productModel->getAll();
        $relevant = [];

        foreach ($products as $product) {
            $score = 0;
            $productName = mb_strtolower($product['name'], 'UTF-8');
            $productDesc = mb_strtolower($product['description'] ?? '', 'UTF-8');
            $productType = mb_strtolower($product['type'] ?? '', 'UTF-8');

            // Keywords matching
            $keywords = [
                'bé' => ['bé', 'trẻ em', 'baby', 'kid', 'con'],
                'tươi' => ['tươi', 'fresh', 'nguyên chất'],
                'chua' => ['chua', 'yogurt', 'sữa chua'],
                'bột' => ['bột', 'powder'],
                'đặc' => ['đặc', 'condensed'],
                'ít đường' => ['ít đường', 'không đường', 'giảm béo'],
                'dinh dưỡng' => ['dinh dưỡng', 'vitamin', 'canxi']
            ];

            foreach ($keywords as $category => $terms) {
                foreach ($terms as $term) {
                    if (strpos($message, $term) !== false) {
                        if (
                            strpos($productName, $category) !== false ||
                            strpos($productDesc, $category) !== false ||
                            strpos($productType, $category) !== false
                        ) {
                            $score += 15;
                        }
                    }
                }
            }

            // Word matching
            $words = preg_split('/\s+/', $message);
            foreach ($words as $word) {
                if (strlen($word) > 2) {
                    if (stripos($productName, $word) !== false) {
                        $score += 8;
                    }
                    if (stripos($productDesc, $word) !== false) {
                        $score += 3;
                    }
                }
            }

            if ($score > 0) {
                $product['relevance_score'] = $score;
                $relevant[] = $product;
            }
        }

        // Sort by score
        usort($relevant, function ($a, $b) {
            return $b['relevance_score'] - $a['relevance_score'];
        });

        return array_slice($relevant, 0, $limit);
    }

    /**
     * Tạo response thông minh
     */
    public function sendMessage($message, $context = [])
    {
        $intent = $this->detectIntent($message);
        $products = $this->findRelevantProducts($message);

        // Generate response based on intent
        $response = '';

        switch ($intent) {
            case 'greeting':
                $response = '👋 Xin chào! Tôi là Vinabot - trợ lý AI của Vinamilk. Tôi có thể giúp bạn:
                
🥛 Tìm kiếm sản phẩm phù hợp
💰 Tư vấn giá cả
📦 Hướng dẫn đặt hàng
🏪 Tìm cửa hàng gần bạn

Bạn đang cần tìm loại sữa nào?';
                break;

            case 'product_search':
                if (!empty($products)) {
                    $response = '✨ Tuyệt vời! Tôi đã tìm thấy ' . count($products) . ' sản phẩm phù hợp với yêu cầu của bạn. Dưới đây là những gợi ý tốt nhất:';
                } else {
                    $response = '🔍 Hmm, tôi chưa tìm thấy sản phẩm chính xác với yêu cầu đó. Đây là một số sản phẩm nổi bật bạn có thể quan tâm:';
                    $allProducts = $this->productModel->getAll();
                    $products = array_slice($allProducts, 0, 3);
                }
                break;

            case 'price':
                if (!empty($products)) {
                    $response = '💰 Tôi có thông tin giá của các sản phẩm sau đây. Tất cả đều có giá cả hợp lý và chất lượng đảm bảo:';
                } else {
                    $response = '💰 Giá sản phẩm Vinamilk rất đa dạng phù hợp mọi túi tiền. Đây là một số sản phẩm có giá tốt:';
                    $allProducts = $this->productModel->getAll();
                    $products = array_slice($allProducts, 0, 3);
                }
                break;

            case 'help':
                $response = '🤝 Tôi rất sẵn lòng hỗ trợ bạn! Tôi có thể giúp:

🥛 Tư vấn chọn sữa phù hợp (cho bé, người lớn, người ăn kiêng...)
🔍 So sánh sản phẩm và giá cả
📦 Hướng dẫn đặt hàng online
🏪 Tìm cửa hàng Vinamilk gần nhất
💬 Giải đáp thắc mắc về sản phẩm

Bạn muốn biết điều gì?';
                break;

            case 'order':
                $response = '🛒 Để đặt hàng rất đơn giản:

1️⃣ Chọn sản phẩm bạn thích
2️⃣ Click "Thêm vào giỏ hàng"
3️⃣ Vào giỏ hàng và click "Thanh toán"
4️⃣ Điền thông tin giao hàng
5️⃣ Hoàn tất đặt hàng!

✨ Miễn phí vận chuyển toàn quốc!

Tôi có thể gợi ý sản phẩm cho bạn không?';
                if (empty($products)) {
                    $allProducts = $this->productModel->getAll();
                    $products = array_slice($allProducts, 0, 3);
                }
                break;

            default:
                $response = '🤔 Tôi hiểu bạn đang quan tâm đến sản phẩm của Vinamilk. Bạn có thể hỏi tôi về:

• Sữa cho bé (sữa bột, sữa nước)
• Sữa tươi nguyên chất
• Sữa chua các loại
• Sữa đặc, kem, phô mai

Hoặc cho tôi biết cụ thể hơn bạn cần gì nhé! 😊';
                if (empty($products)) {
                    $allProducts = $this->productModel->getAll();
                    $products = array_slice($allProducts, 0, 3);
                }
                break;
        }

        return [
            'success' => true,
            'message' => $response,
            'products' => $products,
            'intent' => $intent
        ];
    }

    /**
     * Lưu lịch sử chat
     */
    public function saveChat($userId, $userMessage, $botResponse)
    {
        try {
            $db = getDB();
            $sql = "INSERT INTO chat_history (user_id, user_message, bot_response, created_at) 
                    VALUES (?, ?, ?, NOW())";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$userId, $userMessage, $botResponse]);
        } catch (Exception $e) {
            error_log("Error saving chat: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy lịch sử chat
     */
    public function getChatHistory($userId, $limit = 50)
    {
        try {
            $db = getDB();
            $sql = "SELECT * FROM chat_history WHERE user_id = ? 
                    ORDER BY created_at DESC LIMIT ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$userId, $limit]);
            return array_reverse($stmt->fetchAll());
        } catch (Exception $e) {
            return [];
        }
    }
}
