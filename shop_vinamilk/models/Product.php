<?php
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

    public function create($data)
    {
        $sql = "INSERT INTO products (name, price, image, type, description, ingredients, packaging) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['image'],
            $data['type'],
            $data['description'],
            $data['ingredients'],
            $data['packaging']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE products SET name = ?, price = ?, image = ?, type = ?, 
                description = ?, ingredients = ?, packaging = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['image'],
            $data['type'],
            $data['description'],
            $data['ingredients'],
            $data['packaging'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function search($keyword)
    {
        $sql = "SELECT * FROM products 
                WHERE name LIKE ? OR description LIKE ? 
                ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $searchTerm = "%{$keyword}%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    /* --------------------------------------------------------
        🔥 Thêm 3 hàm mới
    ---------------------------------------------------------*/

    // Đếm tổng số sản phẩm
    public function countProducts()
    {
        $sql = "SELECT COUNT(*) FROM products";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn();
    }

    // Top bán chạy
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

    // Thống kê doanh số từng sản phẩm
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
