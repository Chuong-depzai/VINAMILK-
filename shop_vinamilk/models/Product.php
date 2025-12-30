<?php
// models/Product.php
require_once __DIR__ . '/../db.php';

class Product
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM products ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ==========================================
    // LỌC THEO NHIỀU TIÊU CHÍ
    // ==========================================
    public function getFiltered($filters = [])
    {
        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];

        // Lọc theo danh mục
        if (!empty($filters['category'])) {
            $sql .= " AND category = ?";
            $params[] = $filters['category'];
        }

        // Lọc theo dòng sản phẩm (loại)
        if (!empty($filters['types']) && is_array($filters['types'])) {
            $placeholders = implode(',', array_fill(0, count($filters['types']), '?'));
            $sql .= " AND type IN ($placeholders)";
            $params = array_merge($params, $filters['types']);
        }

        // Lọc theo thương hiệu
        if (!empty($filters['brand'])) {
            $sql .= " AND brand = ?";
            $params[] = $filters['brand'];
        }

        // Lọc theo giá
        if (!empty($filters['price_min'])) {
            $sql .= " AND price >= ?";
            $params[] = $filters['price_min'];
        }
        if (!empty($filters['price_max'])) {
            $sql .= " AND price <= ?";
            $params[] = $filters['price_max'];
        }

        // Lọc theo hương vị
        if (!empty($filters['flavor'])) {
            $sql .= " AND flavor = ?";
            $params[] = $filters['flavor'];
        }

        // Sắp xếp
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY price ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY price DESC";
                break;
            case 'name':
                $sql .= " ORDER BY name ASC";
                break;
            default:
                $sql .= " ORDER BY id DESC";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // ==========================================
    // LẤY DANH SÁCH DANH MỤC
    // ==========================================
    public function getCategories()
    {
        $stmt = $this->db->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // ==========================================
    // LẤY DANH SÁCH THƯƠNG HIỆU
    // ==========================================
    public function getBrands()
    {
        $stmt = $this->db->query("SELECT DISTINCT brand FROM products WHERE brand IS NOT NULL AND brand != '' ORDER BY brand ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // ==========================================
    // LẤY DANH SÁCH HƯƠNG VỊ
    // ==========================================
    public function getFlavors()
    {
        $stmt = $this->db->query("SELECT DISTINCT flavor FROM products WHERE flavor IS NOT NULL AND flavor != '' ORDER BY flavor ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // ==========================================
    // LẤY DANH SÁCH LOẠI SẢN PHẨM
    // ==========================================
    public function getTypes()
    {
        $stmt = $this->db->query("SELECT DISTINCT type FROM products WHERE type IS NOT NULL AND type != '' ORDER BY type ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // ==========================================
    // LẤY KHOẢNG GIÁ MIN/MAX
    // ==========================================
    public function getPriceRange()
    {
        $stmt = $this->db->query("SELECT MIN(price) as min_price, MAX(price) as max_price FROM products");
        return $stmt->fetch();
    }

    // ==========================================
    // THÊM SẢN PHẨM MỚI
    // ==========================================
    public function create($data)
    {
        $sql = "INSERT INTO products (name, price, image, type, description, ingredients, packaging, category, brand, flavor, volume) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['image'],
            $data['type'],
            $data['description'],
            $data['ingredients'],
            $data['packaging'],
            $data['category'] ?? null,
            $data['brand'] ?? 'Vinamilk',
            $data['flavor'] ?? null,
            $data['volume'] ?? null
        ]);
    }

    // ==========================================
    // CẬP NHẬT SẢN PHẨM
    // ==========================================
    public function update($id, $data)
    {
        $sql = "UPDATE products SET name = ?, price = ?, image = ?, type = ?, 
                description = ?, ingredients = ?, packaging = ?, category = ?, 
                brand = ?, flavor = ?, volume = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['image'],
            $data['type'],
            $data['description'],
            $data['ingredients'],
            $data['packaging'],
            $data['category'] ?? null,
            $data['brand'] ?? 'Vinamilk',
            $data['flavor'] ?? null,
            $data['volume'] ?? null,
            $id
        ]);
    }

    // ==========================================
    // XÓA SẢN PHẨM
    // ==========================================
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ==========================================
    // TÌM KIẾM SẢN PHẨM
    // ==========================================
    public function search($keyword)
    {
        $sql = "SELECT * FROM products 
                WHERE name LIKE ? OR description LIKE ? OR brand LIKE ?
                ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $searchTerm = "%{$keyword}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    // ==========================================
    // THỐNG KÊ VÀ BÁO CÁO
    // ==========================================
    public function countProducts()
    {
        $sql = "SELECT COUNT(*) FROM products";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn();
    }

    public function getTopSelling($limit = 5)
    {
        $sql = "SELECT p.*, 
                       COALESCE(SUM(oi.quantity), 0) AS total_sold
                FROM products p
                LEFT JOIN order_items oi ON p.id = oi.product_id
                LEFT JOIN orders o ON oi.order_id = o.id 
                     AND o.status IN ('completed', 'processing')
                GROUP BY p.id
                ORDER BY total_sold DESC
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getProductStats()
    {
        $sql = "SELECT p.*, 
                       COALESCE(SUM(oi.quantity), 0) AS total_sold,
                       COALESCE(SUM(oi.subtotal), 0) AS total_revenue
                FROM products p
                LEFT JOIN order_items oi ON p.id = oi.product_id
                LEFT JOIN orders o ON oi.order_id = o.id
                     AND o.status IN ('completed', 'processing')
                GROUP BY p.id
                ORDER BY total_sold DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
