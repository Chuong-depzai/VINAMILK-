<?php
// controllers/StoreController.php
require_once __DIR__ . '/../models/Store.php';

class StoreController
{
    private $storeModel;

    public function __construct()
    {
        $this->storeModel = new Store();
    }

    // Hiển thị trang bản đồ cửa hàng
    public function index()
    {
        $provinces = $this->storeModel->getProvinces();

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/store_locator.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // API: Trả về danh sách cửa hàng dạng JSON
    public function api()
    {
        header('Content-Type: application/json; charset=utf-8');

        $action = $_GET['api_action'] ?? 'all';

        switch ($action) {
            case 'all':
                $data = $this->storeModel->getStoresJSON();
                break;

            case 'search':
                $keyword = $_GET['keyword'] ?? '';
                $stores = $this->storeModel->search($keyword);
                $data = $this->formatStoresJSON($stores);
                break;

            case 'province':
                $province = $_GET['province'] ?? '';
                $stores = $this->storeModel->getByProvince($province);
                $data = $this->formatStoresJSON($stores);
                break;

            case 'nearby':
                $lat = floatval($_GET['lat'] ?? 0);
                $lng = floatval($_GET['lng'] ?? 0);
                $radius = floatval($_GET['radius'] ?? 50);
                $stores = $this->storeModel->getNearby($lat, $lng, $radius);
                $data = $this->formatStoresJSON($stores);
                break;

            default:
                $data = ['error' => 'Invalid action'];
        }

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    // Format stores thành GeoJSON
    private function formatStoresJSON($stores)
    {
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
                        'opening_hours' => $store['opening_hours'],
                        'distance' => isset($store['distance']) ? round($store['distance'], 2) : null
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
