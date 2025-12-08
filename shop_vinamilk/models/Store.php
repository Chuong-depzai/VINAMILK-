<?php
// models/Store.php
require_once __DIR__ . '/../db.php';

class Store
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // Lấy tất cả cửa hàng
    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM stores ORDER BY province, city");
        return $stmt->fetchAll();
    }

    // Lấy cửa hàng theo ID
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM stores WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy cửa hàng theo tỉnh/thành
    public function getByProvince($province)
    {
        $stmt = $this->db->prepare("SELECT * FROM stores WHERE province LIKE ? ORDER BY city");
        $stmt->execute(['%' . $province . '%']);
        return $stmt->fetchAll();
    }

    // Tìm kiếm cửa hàng
    public function search($keyword)
    {
        $sql = "SELECT * FROM stores 
                WHERE name LIKE ? 
                OR address LIKE ? 
                OR city LIKE ? 
                OR province LIKE ?
                ORDER BY province, city";
        $stmt = $this->db->prepare($sql);
        $searchTerm = '%' . $keyword . '%';
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    // Lấy danh sách tỉnh/thành (không trùng)
    public function getProvinces()
    {
        $stmt = $this->db->query("SELECT DISTINCT province FROM stores ORDER BY province");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Lấy cửa hàng gần nhất (theo tọa độ)
    public function getNearby($lat, $lng, $radius = 50)
    {
        // Tính khoảng cách bằng công thức Haversine
        $sql = "SELECT *, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
                sin(radians(latitude)))) AS distance 
                FROM stores 
                HAVING distance < ? 
                ORDER BY distance 
                LIMIT 20";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$lat, $lng, $lat, $radius]);
        return $stmt->fetchAll();
    }

    // API: Trả về JSON cho map
    public function getStoresJSON()
    {
        $stores = $this->getAll();
        $features = [];

        foreach ($stores as $store) {
            if ($store['latitude'] && $store['longitude']) {
                $features[] = [
                    'type' => 'Feature',
                    'properties' => [
                        'id' => $store['id'],
                        'name' => $store['name'],
                        'address' => $store['address'],
                        'city' => $store['city'],
                        'province' => $store['province'],
                        'phone' => $store['phone'],
                        'opening_hours' => $store['opening_hours']
                    ],
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            floatval($store['longitude']),
                            floatval($store['latitude'])
                        ]
                    ]
                ];
            }
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
    }
}
