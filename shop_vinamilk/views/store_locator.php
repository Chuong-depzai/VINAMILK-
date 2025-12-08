<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

<style>
    .store-locator-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }

    .store-locator-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .store-locator-title {
        font-size: 32px;
        color: #0033a0;
        margin-bottom: 10px;
    }

    .store-locator-subtitle {
        font-size: 16px;
        color: #666;
    }

    .store-controls {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }

    .control-group {
        flex: 1;
        min-width: 200px;
    }

    .control-label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .control-input,
    .control-select {
        width: 100%;
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .control-input:focus,
    .control-select:focus {
        outline: none;
        border-color: #0033a0;
    }

    .btn-search,
    .btn-locate {
        padding: 10px 20px;
        background-color: #0033a0;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
        white-space: nowrap;
    }

    .btn-search:hover,
    .btn-locate:hover {
        background-color: #002780;
    }

    .btn-locate {
        background-color: #ff6b00;
    }

    .btn-locate:hover {
        background-color: #e55d00;
    }

    .map-wrapper {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 20px;
        height: 600px;
    }

    .store-list {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        padding: 20px;
    }

    .store-list-title {
        font-size: 18px;
        color: #0033a0;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .store-count {
        font-size: 14px;
        color: #666;
        margin-bottom: 15px;
    }

    .store-item {
        padding: 15px;
        border: 1px solid #eee;
        border-radius: 8px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .store-item:hover {
        border-color: #0033a0;
        background-color: #f5f8ff;
    }

    .store-item-name {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .store-item-address {
        font-size: 13px;
        color: #666;
        margin-bottom: 5px;
    }

    .store-item-phone {
        font-size: 13px;
        color: #0033a0;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .btn-get-directions {
        display: inline-block;
        padding: 6px 12px;
        background: #ff6b00;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-get-directions:hover {
        background: #e55d00;
    }

    #map {
        height: 100%;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .leaflet-popup-content {
        min-width: 250px;
    }

    .popup-store-name {
        font-size: 16px;
        font-weight: bold;
        color: #0033a0;
        margin-bottom: 10px;
    }

    .popup-store-info {
        font-size: 13px;
        color: #666;
        line-height: 1.6;
    }

    .popup-store-info strong {
        color: #333;
    }

    .popup-distance {
        background-color: #ff6b00;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        display: inline-block;
        margin-top: 5px;
    }

    /* Custom routing control styles */
    .leaflet-routing-container {
        display: none;
    }

    @media (max-width: 768px) {
        .map-wrapper {
            grid-template-columns: 1fr;
            height: auto;
        }

        .store-list {
            height: 300px;
        }

        #map {
            height: 400px;
        }
    }
</style>

<div class="store-locator-container">
    <div class="store-locator-header">
        <h1 class="store-locator-title">Hệ Thống Cửa Hàng Vinamilk</h1>
        <p class="store-locator-subtitle">Tìm cửa hàng Vinamilk gần bạn nhất và nhận chỉ đường</p>
    </div>

    <div class="store-controls">
        <div class="control-group">
            <label class="control-label">Tìm kiếm</label>
            <input type="text"
                id="searchInput"
                class="control-input"
                placeholder="Nhập tên cửa hàng, địa chỉ...">
        </div>

        <div class="control-group">
            <label class="control-label">Tỉnh/Thành phố</label>
            <select id="provinceSelect" class="control-select">
                <option value="">Tất cả tỉnh/thành</option>
                <?php foreach ($provinces as $province): ?>
                    <option value="<?php echo htmlspecialchars($province); ?>">
                        <?php echo htmlspecialchars($province); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="control-group" style="display: flex; gap: 10px; align-items: flex-end;">
            <button id="btnSearch" class="btn-search">🔍 Tìm kiếm</button>
            <button id="btnLocate" class="btn-locate">📍 Vị trí của tôi</button>
        </div>
    </div>

    <div class="map-wrapper">
        <div class="store-list">
            <h3 class="store-list-title">Danh sách cửa hàng</h3>
            <div class="store-count" id="storeCount">Đang tải...</div>
            <div id="storeListContainer"></div>
        </div>

        <div id="map"></div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

<script>
    // Khởi tạo map
    const map = L.map('map').setView([16.0, 106.0], 6);

    // Thêm tile layer
    L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}.png', {
        attribution: '© OpenMapTiles © OpenStreetMap contributors',
        maxZoom: 20
    }).addTo(map);

    // Custom icons
    const vinamilkIcon = L.icon({
        iconUrl: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iNDIiIHZpZXdCb3g9IjAgMCAzMiA0MiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTYgMEMxMC41IDAgNiA0LjUgNiAxMGMwIDkgMTAgMjAgMTAgMjBzMTAtMTEgMTAtMjBjMC01LjUtNC41LTEwLTEwLTEweiIgZmlsbD0iIzAwMzNhMCIvPjxjaXJjbGUgY3g9IjE2IiBjeT0iMTAiIHI9IjUiIGZpbGw9IndoaXRlIi8+PC9zdmc+',
        iconSize: [32, 42],
        iconAnchor: [16, 42],
        popupAnchor: [0, -42]
    });

    const markers = L.markerClusterGroup({
        maxClusterRadius: 50,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false
    });

    let allStores = [];
    let currentUserLocation = null;
    let currentRouting = null;

    // Load stores
    async function loadStores() {
        try {
            const response = await fetch('index.php?controller=store&action=api&api_action=all');
            const data = await response.json();
            allStores = data.features;
            displayStores(allStores);
            updateStoreCount(allStores.length);
        } catch (error) {
            console.error('Lỗi:', error);
            document.getElementById('storeCount').textContent = 'Lỗi khi tải dữ liệu';
        }
    }

    // Display stores
    function displayStores(stores) {
        markers.clearLayers();

        stores.forEach(store => {
            const props = store.properties;
            const coords = store.geometry.coordinates;

            const marker = L.marker([coords[1], coords[0]], {
                icon: vinamilkIcon
            });

            let popupContent = `
                <div class="popup-store-name">${props.name}</div>
                <div class="popup-store-info">
                    <strong>Địa chỉ:</strong> ${props.address}, ${props.city}<br>
                    <strong>Điện thoại:</strong> ${props.phone || 'Chưa cập nhật'}<br>
                    <strong>Giờ mở cửa:</strong> ${props.opening_hours || 'Chưa cập nhật'}
            `;

            if (props.distance) {
                popupContent += `<br><span class="popup-distance">🚗 Cách ${props.distance} km</span>`;
            }

            popupContent += `<br><br><button class="btn-get-directions" onclick="getDirections(${coords[1]}, ${coords[0]}, '${props.name.replace(/'/g, "\\'")}')">🗺️ Chỉ đường</button>`;
            popupContent += `</div>`;

            marker.bindPopup(popupContent);
            markers.addLayer(marker);
        });

        map.addLayer(markers);

        if (stores.length > 0) {
            map.fitBounds(markers.getBounds(), {
                padding: [50, 50]
            });
        }

        updateStoreList(stores);
    }

    // Update store list
    function updateStoreList(stores) {
        const container = document.getElementById('storeListContainer');

        if (stores.length === 0) {
            container.innerHTML = '<p style="color: #999; text-align: center;">Không tìm thấy cửa hàng</p>';
            return;
        }

        container.innerHTML = stores.map(store => {
            const props = store.properties;
            const coords = store.geometry.coordinates;

            let distanceText = '';
            if (props.distance) {
                distanceText = `<br><span style="color: #ff6b00; font-weight: 600;">📍 ${props.distance} km</span>`;
            }

            return `
                <div class="store-item" onclick="focusStore(${coords[1]}, ${coords[0]})">
                    <div class="store-item-name">${props.name}</div>
                    <div class="store-item-address">${props.address}, ${props.city}</div>
                    <div class="store-item-phone">☎ ${props.phone || 'Chưa cập nhật'}${distanceText}</div>
                    <button class="btn-get-directions" onclick="event.stopPropagation(); getDirections(${coords[1]}, ${coords[0]}, '${props.name.replace(/'/g, "\\'")}')">
                        🗺️ Chỉ đường
                    </button>
                </div>
            `;
        }).join('');
    }

    // Get directions
    window.getDirections = function(destLat, destLng, storeName) {
        if (!currentUserLocation) {
            alert('Vui lòng bật vị trí của bạn trước!');
            document.getElementById('btnLocate').click();
            return;
        }

        // Remove old routing
        if (currentRouting) {
            map.removeControl(currentRouting);
        }

        // Create new routing
        currentRouting = L.Routing.control({
            waypoints: [
                L.latLng(currentUserLocation[0], currentUserLocation[1]),
                L.latLng(destLat, destLng)
            ],
            routeWhileDragging: false,
            showAlternatives: false,
            lineOptions: {
                styles: [{
                    color: '#0033a0',
                    opacity: 0.8,
                    weight: 6
                }]
            },
            createMarker: function() {
                return null;
            }
        }).addTo(map);

        // Zoom to route
        map.setView([destLat, destLng], 14);

        alert(`Đang chỉ đường đến: ${storeName}`);
    };

    // Focus store
    function focusStore(lat, lng) {
        map.setView([lat, lng], 15);

        markers.eachLayer(layer => {
            const markerLatLng = layer.getLatLng();
            if (markerLatLng.lat === lat && markerLatLng.lng === lng) {
                layer.openPopup();
            }
        });
    }

    // Update count
    function updateStoreCount(count) {
        document.getElementById('storeCount').textContent = `Tìm thấy ${count} cửa hàng`;
    }

    // Search stores
    async function searchStores() {
        const keyword = document.getElementById('searchInput').value.trim();
        const province = document.getElementById('provinceSelect').value;

        if (!keyword && !province) {
            displayStores(allStores);
            updateStoreCount(allStores.length);
            return;
        }

        try {
            let url = 'index.php?controller=store&action=api';

            if (keyword) {
                url += '&api_action=search&keyword=' + encodeURIComponent(keyword);
            } else if (province) {
                url += '&api_action=province&province=' + encodeURIComponent(province);
            }

            const response = await fetch(url);
            const data = await response.json();

            displayStores(data.features);
            updateStoreCount(data.features.length);
        } catch (error) {
            console.error('Lỗi:', error);
        }
    }

    // Locate user
    function locateUser() {
        if (!navigator.geolocation) {
            alert('Trình duyệt không hỗ trợ định vị!');
            return;
        }

        document.getElementById('storeCount').textContent = 'Đang xác định vị trí...';

        const options = {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        };

        navigator.geolocation.getCurrentPosition(
            async (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    currentUserLocation = [lat, lng];

                    if (window.userMarker) {
                        map.removeLayer(window.userMarker);
                    }

                    window.userMarker = L.marker([lat, lng], {
                        icon: L.icon({
                            iconUrl: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iNDIiIHZpZXdCb3g9IjAgMCAzMiA0MiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTYgMEMxMC41IDAgNiA0LjUgNiAxMGMwIDkgMTAgMjAgMTAgMjBzMTAtMTEgMTAtMjBjMC01LjUtNC41LTEwLTEwLTEweiIgZmlsbD0iI2ZmNmIwMCIvPjxjaXJjbGUgY3g9IjE2IiBjeT0iMTAiIHI9IjUiIGZpbGw9IndoaXRlIi8+PC9zdmc+',
                            iconSize: [32, 42],
                            iconAnchor: [16, 42],
                            popupAnchor: [0, -42]
                        })
                    }).addTo(map).bindPopup('📍 Vị trí của bạn').openPopup();

                    map.setView([lat, lng], 13);

                    try {
                        const response = await fetch(
                            `index.php?controller=store&action=api&api_action=nearby&lat=${lat}&lng=${lng}&radius=50`
                        );
                        const data = await response.json();
                        displayStores(data.features);
                        updateStoreCount(data.features.length);
                    } catch (error) {
                        console.error('Lỗi:', error);
                    }
                },
                (error) => {
                    alert('Không thể xác định vị trí. Vui lòng bật GPS!');
                    document.getElementById('storeCount').textContent = 'Không thể xác định vị trí';
                },
                options
        );
    }

    // Event listeners
    document.getElementById('btnSearch').addEventListener('click', searchStores);
    document.getElementById('btnLocate').addEventListener('click', locateUser);
    document.getElementById('searchInput').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') searchStores();
    });
    document.getElementById('provinceSelect').addEventListener('change', searchStores);

    // Load initial data
    loadStores();
</script>