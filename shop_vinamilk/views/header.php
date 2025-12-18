<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$cartModel = new Cart();
$cartCount = $cartModel->getCount();
$cartItems = $cartModel->getItems();
$cartTotal = $cartModel->getTotal();

$isLoggedIn = AuthController::isLoggedIn();
$currentUser = AuthController::getCurrentUser();

// Load wishlist
$wishlistCount = 0;
$wishlistItems = [];
if ($isLoggedIn) {
    require_once __DIR__ . '/../models/Wishlist.php';
    $wishlistModel = new Wishlist();
    $wishlistCount = $wishlistModel->getCount($_SESSION['user_id']);
    $wishlistItems = $wishlistModel->getByUserId($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sữa Tươi | Vinamilk</title>
    <link rel="icon" type="image/png" href="uploads/logo.png">
    <link rel="stylesheet" href="css/new-style.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* ===== HEADER - Z-INDEX FIX ===== */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999999 !important;
            /* CRITICAL: !important để override mọi z-index khác */
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
        }

        /* Force tất cả children của header có z-index cao */
        .site-header * {
            position: relative;
            z-index: inherit;
        }

        .site-header.scrolled {
            background: rgba(255, 255, 255, 0.98) !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(20px);
        }

        /* ===== USER DROPDOWN FIX - CLICK VERSION ===== */
        .header-top {
            position: relative;
            z-index: 999999 !important;
        }

        .header-top-right {
            position: relative;
            z-index: 999999 !important;
        }

        .user-dropdown-wrapper {
            position: relative;
            z-index: 999999 !important;
        }

        .user-greeting {
            color: white;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
            padding: 10px 16px;
            display: block;
            position: relative;
            z-index: 999999 !important;
            background: transparent;
            border-radius: 6px;
            user-select: none;
        }

        .user-greeting:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .user-greeting:active {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(0.98);
        }

        .site-header.scrolled .user-greeting {
            color: #0033a0 !important;
        }

        .site-header.scrolled .user-greeting:hover {
            background: rgba(0, 51, 160, 0.1);
        }

        .user-dropdown-menu {
            position: absolute !important;
            top: calc(100% + 12px);
            right: 0;
            background: white;
            min-width: 240px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
            border-radius: 12px;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-20px) scale(0.95);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 999999 !important;
            border: 2px solid rgba(0, 51, 160, 0.15);
            pointer-events: none;
        }

        .user-dropdown-menu.show {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) scale(1) !important;
            pointer-events: auto !important;
        }

        .user-dropdown-menu a {
            display: block;
            padding: 16px 24px;
            color: #333;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
            font-weight: 500;
            border-bottom: 1px solid #f5f5f5;
            position: relative;
            z-index: 999999 !important;
        }

        .user-dropdown-menu a:last-child {
            border-bottom: none;
        }

        .user-dropdown-menu a:hover {
            background: linear-gradient(to right, #f0f5ff, #e6f0ff);
            color: #0033a0;
            padding-left: 32px;
        }

        .user-dropdown-menu a::before {
            content: '›';
            position: absolute;
            left: 16px;
            opacity: 0;
            transition: all 0.2s;
        }

        .user-dropdown-menu a:hover::before {
            opacity: 1;
        }

        .user-dropdown-menu a:active {
            background: #d6e8ff;
            transform: scale(0.98);
        }

        /* Overlay để đóng dropdown khi click bên ngoài */
        .dropdown-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 999998;
            display: none;
        }

        .dropdown-overlay.show {
            display: block;
        }

        /* ===== SLIDE-IN CART PANEL ===== */
        .cart-panel-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .cart-panel-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .cart-panel {
            position: fixed;
            top: 0;
            right: -450px;
            width: 450px;
            height: 100%;
            background: white;
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .cart-panel.active {
            right: 0;
        }

        .cart-panel-header {
            padding: 20px;
            background: #0033a0;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-panel-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }

        .cart-panel-close {
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.3s;
        }

        .cart-panel-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .cart-panel-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .cart-panel-empty {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .cart-panel-empty-icon {
            font-size: 64px;
            margin-bottom: 15px;
        }

        .cart-panel-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .cart-panel-item-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            background: #f9f9f9;
        }

        .cart-panel-item-info {
            flex: 1;
        }

        .cart-panel-item-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .cart-panel-item-price {
            font-size: 14px;
            color: #ff6b00;
            font-weight: 700;
        }

        .cart-panel-item-quantity {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }

        .cart-panel-footer {
            padding: 20px;
            border-top: 2px solid #f0f0f0;
            background: white;
        }

        .cart-panel-total {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        .cart-panel-total-value {
            color: #ff6b00;
        }

        .cart-panel-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-cart-action {
            padding: 14px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .btn-view-cart {
            background: #0033a0;
            color: white;
        }

        .btn-view-cart:hover {
            background: #002780;
        }

        .btn-checkout {
            background: #ff6b00;
            color: white;
        }

        .btn-checkout:hover {
            background: #e55d00;
        }

        /* ===== WISHLIST PANEL (tương tự Cart) ===== */
        .wishlist-panel {
            position: fixed;
            top: 0;
            right: -450px;
            width: 450px;
            height: 100%;
            background: white;
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .wishlist-panel.active {
            right: 0;
        }

        .wishlist-panel-header {
            padding: 20px;
            background: linear-gradient(135deg, #ff6b00 0%, #ff8533 100%);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 768px) {

            .cart-panel,
            .wishlist-panel {
                width: 100%;
                right: -100%;
            }
        }

        /* Rest of your existing header styles... */
        .header-top {
            background: rgba(0, 51, 160, 0.95);
            backdrop-filter: blur(10px);
            padding: 8px 0;
            transition: all 0.4s ease;
            opacity: 1;
            max-height: 40px;
            overflow: hidden;
        }

        .site-header.scrolled .header-top {
            opacity: 0;
            max-height: 0;
            padding: 0;
        }

        .header-top .container-header {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }

        .header-top-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: white;
        }

        .header-top-left,
        .header-top-right {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .header-top a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .header-top a:hover {
            opacity: 0.8;
        }

        .header-main {
            background: transparent;
            transition: all 0.4s ease;
        }

        .header-main .container-header {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }

        .header-main-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .site-header.scrolled .header-main-content {
            height: 60px;
        }

        .site-logo {
            display: flex;
            align-items: center;
            transition: all 0.4s ease;
        }

        .logo-img {
            height: 45px;
            width: auto;
            transition: all 0.4s ease;
        }

        .site-header.scrolled .logo-img {
            height: 38px;
        }

        .main-nav {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .nav-menu {
            display: flex;
            gap: 30px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 8px 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .site-header.scrolled .nav-link {
            color: #0033a0 !important;
        }

        .nav-link:hover {
            opacity: 0.8;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .action-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 6px;
            position: relative;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .site-header.scrolled .action-btn {
            color: #333 !important;
        }

        .action-btn:hover {
            opacity: 0.8;
        }

        .action-btn svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .badge {
            position: absolute;
            top: -3px;
            right: -3px;
            background: #ff4444;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 1px 5px;
            border-radius: 10px;
        }

        .user-avatar,
        .user-avatar-placeholder {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .user-avatar {
            object-fit: cover;
        }

        .user-avatar-placeholder {
            background: white;
            color: #0033a0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 2px;
        }

        .user-name-text {
            font-size: 13px;
            font-weight: 600;
            color: white;
        }

        .site-header.scrolled .user-name-text {
            color: #0033a0;
        }

        .user-role-badge {
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 10px;
            background: rgba(255, 107, 0, 0.3);
            color: #ff6b00;
            font-weight: 700;
        }

        .dropdown-user-header {
            padding: 20px;
            background: linear-gradient(135deg, #0033a0 0%, #005ce6 100%);
            color: white;
            text-align: center;
        }

        .dropdown-avatar-large,
        .dropdown-avatar-placeholder-large {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            margin: 0 auto 10px;
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .dropdown-avatar-large {
            object-fit: cover;
        }

        .dropdown-avatar-placeholder-large {
            background: white;
            color: #0033a0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 28px;
        }

        .dropdown-user-name {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .dropdown-user-email {
            font-size: 12px;
            opacity: 0.9;
        }

        .dropdown-menu-items {
            padding: 8px 0;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
            font-weight: 500;
            border-bottom: 1px solid #f5f5f5;
            position: relative;
        }

        .dropdown-item:hover {
            background: linear-gradient(to right, #f0f5ff, #e6f0ff);
            color: #0033a0;
            padding-left: 28px;
        }

        .dropdown-item-icon {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        .dropdown-badge {
            margin-left: auto;
            background: #ff6b00;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }

        .dropdown-item.admin-item {
            background: #fff5e6;
            border-top: 2px solid #ffeaa7;
            border-bottom: 2px solid #ffeaa7;
        }

        .dropdown-item.logout {
            color: #dc3545;
            border-top: 2px solid #f0f0f0;
            border-bottom: none;
        }

        .dropdown-item.logout:hover {
            background: #fff5f5;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="site-header">
        <!-- Top bar -->
        <div class="header-top">
            <div class="container-header">
                <div class="header-top-content">
                    <div class="header-top-left">
                        <span>📞 Hotline: 1900 636 979</span>
                        <span>|</span>
                        <a href="index.php?controller=store">🏪 Hệ thống cửa hàng</a>
                    </div>

                    <div class="header-top-right">
                        <?php if ($isLoggedIn): ?>
                            <div class="user-dropdown-wrapper">
                                <div class="user-greeting" onclick="toggleUserDropdown(event)">
                                    <!-- Avatar -->
                                    <?php if (!empty($currentUser['avatar'])): ?>
                                        <img src="uploads/avatars/<?php echo htmlspecialchars($currentUser['avatar']); ?>"
                                            class="user-avatar"
                                            alt="Avatar">
                                    <?php else: ?>
                                        <div class="user-avatar-placeholder">
                                            <?php echo strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- User Info -->
                                    <div class="user-info">
                                        <span class="user-name-text">
                                            <?php echo htmlspecialchars($currentUser['name'] ?? $currentUser['phone']); ?>
                                        </span>
                                        <?php if ($currentUser['role'] === 'admin'): ?>
                                            <span class="user-role-badge">👑 ADMIN</span>
                                        <?php endif; ?>
                                    </div>
                                    <span style="margin-left: 4px;">▾</span>
                                </div>

                                <div class="user-dropdown-menu" id="userDropdownMenu">
                                    <!-- Dropdown Header -->
                                    <div class="dropdown-user-header">
                                        <?php if (!empty($currentUser['avatar'])): ?>
                                            <img src="uploads/avatars/<?php echo htmlspecialchars($currentUser['avatar']); ?>"
                                                class="dropdown-avatar-large"
                                                alt="Avatar">
                                        <?php else: ?>
                                            <div class="dropdown-avatar-placeholder-large">
                                                <?php echo strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="dropdown-user-name">
                                            <?php echo htmlspecialchars($currentUser['name'] ?? 'Người dùng'); ?>
                                        </div>
                                        <div class="dropdown-user-email">
                                            <?php echo htmlspecialchars($currentUser['email'] ?? $currentUser['phone']); ?>
                                        </div>
                                    </div>

                                    <!-- Menu Items -->
                                    <div class="dropdown-menu-items">
                                        <a href="index.php?controller=user&action=profile" class="dropdown-item">
                                            <span class="dropdown-item-icon">👤</span>
                                            <span>Thông tin cá nhân & Avatar</span>
                                        </a>

                                        <a href="index.php?controller=order&action=history" class="dropdown-item">
                                            <span class="dropdown-item-icon">📦</span>
                                            <span>Đơn hàng của tôi</span>
                                        </a>

                                        <a href="index.php?controller=wishlist" class="dropdown-item">
                                            <span class="dropdown-item-icon">❤️</span>
                                            <span>Danh sách yêu thích</span>
                                            <?php if ($wishlistCount > 0): ?>
                                                <span class="dropdown-badge"><?php echo $wishlistCount; ?></span>
                                            <?php endif; ?>
                                        </a>

                                        <a href="index.php?controller=cart&action=view" class="dropdown-item">
                                            <span class="dropdown-item-icon">🛒</span>
                                            <span>Giỏ hàng</span>
                                            <?php if ($cartCount > 0): ?>
                                                <span class="dropdown-badge"><?php echo $cartCount; ?></span>
                                            <?php endif; ?>
                                        </a>

                                        <?php if ($currentUser['role'] === 'admin'): ?>
                                            <a href="index.php?controller=admin&action=dashboard" class="dropdown-item admin-item">
                                                <span class="dropdown-item-icon">⚙️</span>
                                                <span>Quản trị hệ thống</span>
                                            </a>
                                        <?php endif; ?>

                                        <a href="index.php?controller=auth&action=logout" class="dropdown-item logout">
                                            <span class="dropdown-item-icon">🚪</span>
                                            <span>Đăng xuất</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-overlay" id="dropdownOverlay" onclick="closeUserDropdown()"></div>
                        <?php else: ?>
                            <a href="index.php?controller=auth&action=showLogin">Đăng nhập</a>
                            <span>|</span>
                            <a href="index.php?controller=auth&action=showRegister">Đăng ký</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main header -->
        <div class="header-main">
            <div class="container-header">
                <div class="header-main-content">
                    <a href="index.php" class="site-logo">
                        <img src="uploads/vinamilk-logo_brandlogos.net_quayf.png" alt="Vinamilk" class="logo-img">
                    </a>

                    <nav class="main-nav">
                        <ul class="nav-menu">
                            <li class="nav-item">
                                <a href="index.php" class="nav-link">Trang chủ</a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?controller=product&action=productList" class="nav-link">Sản phẩm</a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?controller=store" class="nav-link">Cửa hàng</a>
                            </li>
                            <?php if (AuthController::isAdmin()): ?>
                                <li class="nav-item">
                                    <a href="index.php?controller=admin&action=dashboard" class="nav-link">Admin</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>

                    <div class="header-actions">
                        <?php if ($isLoggedIn): ?>
                            <button class="action-btn wishlist-btn" onclick="toggleWishlistPanel()" aria-label="Yêu thích">
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                                    <path d="M10 18L2 10C0.5 8.5 0 6.5 1 4.5C2 2.5 4 2 6 3C7 3.5 8 4.5 10 7C12 4.5 13 3.5 14 3C16 2 18 2.5 19 4.5C20 6.5 19.5 8.5 18 10L10 18Z" stroke="currentColor" stroke-width="2" />
                                </svg>
                                <?php if ($wishlistCount > 0): ?>
                                    <span class="badge" id="wishlist-count-badge"><?php echo $wishlistCount; ?></span>
                                <?php endif; ?>
                            </button>
                        <?php endif; ?>

                        <button class="action-btn cart-btn" onclick="toggleCartPanel()" aria-label="Giỏ hàng">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                                <path d="M1 1H4L6 13H16L18 5H5" stroke="currentColor" stroke-width="2" />
                                <circle cx="7" cy="17" r="1" fill="currentColor" />
                                <circle cx="15" cy="17" r="1" fill="currentColor" />
                            </svg>
                            <?php if ($cartCount > 0): ?>
                                <span class="badge" id="cart-count-badge"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Cart Panel Overlay -->
    <div class="cart-panel-overlay" id="cartOverlay" onclick="toggleCartPanel()"></div>

    <!-- Cart Panel -->
    <div class="cart-panel" id="cartPanel">
        <div class="cart-panel-header">
            <h3 class="cart-panel-title">🛒 Giỏ hàng (<?php echo $cartCount; ?>)</h3>
            <button class="cart-panel-close" onclick="toggleCartPanel()">×</button>
        </div>

        <div class="cart-panel-body">
            <?php if (empty($cartItems)): ?>
                <div class="cart-panel-empty">
                    <div class="cart-panel-empty-icon">🛒</div>
                    <p>Giỏ hàng trống</p>
                </div>
            <?php else: ?>
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-panel-item">
                        <?php if (!empty($item['image']) && file_exists(__DIR__ . '/../uploads/' . $item['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" class="cart-panel-item-image">
                        <?php else: ?>
                            <div class="cart-panel-item-image" style="background: #e0e0e0;"></div>
                        <?php endif; ?>

                        <div class="cart-panel-item-info">
                            <div class="cart-panel-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="cart-panel-item-price"><?php echo number_format($item['price'], 0, ',', '.'); ?>₫</div>
                            <div class="cart-panel-item-quantity">Số lượng: <?php echo $item['quantity']; ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($cartItems)): ?>
            <div class="cart-panel-footer">
                <div class="cart-panel-total">
                    <span>Tổng cộng:</span>
                    <span class="cart-panel-total-value"><?php echo number_format($cartTotal, 0, ',', '.'); ?>₫</span>
                </div>

                <div class="cart-panel-actions">
                    <a href="index.php?controller=cart&action=view" class="btn-cart-action btn-view-cart">
                        Xem giỏ hàng
                    </a>
                    <a href="index.php?controller=payment&action=checkout" class="btn-cart-action btn-checkout">
                        Thanh toán ngay
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Wishlist Panel -->
    <div class="cart-panel-overlay" id="wishlistOverlay" onclick="toggleWishlistPanel()"></div>

    <div class="wishlist-panel" id="wishlistPanel">
        <div class="wishlist-panel-header">
            <h3 class="cart-panel-title">❤️ Yêu thích (<?php echo $wishlistCount; ?>)</h3>
            <button class="cart-panel-close" onclick="toggleWishlistPanel()">×</button>
        </div>

        <div class="cart-panel-body">
            <?php if (empty($wishlistItems)): ?>
                <div class="cart-panel-empty">
                    <div class="cart-panel-empty-icon">💔</div>
                    <p>Danh sách yêu thích trống</p>
                </div>
            <?php else: ?>
                <?php foreach ($wishlistItems as $item): ?>
                    <div class="cart-panel-item">
                        <?php if (!empty($item['image']) && file_exists(__DIR__ . '/../uploads/' . $item['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" class="cart-panel-item-image">
                        <?php else: ?>
                            <div class="cart-panel-item-image" style="background: #e0e0e0;"></div>
                        <?php endif; ?>

                        <div class="cart-panel-item-info">
                            <div class="cart-panel-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="cart-panel-item-price"><?php echo number_format($item['price'], 0, ',', '.'); ?>₫</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($wishlistItems)): ?>
            <div class="cart-panel-footer">
                <div class="cart-panel-actions">
                    <a href="index.php?controller=wishlist" class="btn-cart-action btn-view-cart">
                        Xem danh sách
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <main class="site-content">

        <script>
            // Header scroll effect
            let lastScrollTop = 0;
            const header = document.querySelector('.site-header');
            const scrollThreshold = 50;

            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                if (scrollTop > scrollThreshold) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }

                lastScrollTop = scrollTop;
            }, {
                passive: true
            });

            // Toggle Cart Panel
            function toggleCartPanel() {
                const panel = document.getElementById('cartPanel');
                const overlay = document.getElementById('cartOverlay');

                panel.classList.toggle('active');
                overlay.classList.toggle('active');

                if (panel.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }

            // Toggle Wishlist Panel
            function toggleWishlistPanel() {
                const panel = document.getElementById('wishlistPanel');
                const overlay = document.getElementById('wishlistOverlay');

                panel.classList.toggle('active');
                overlay.classList.toggle('active');

                if (panel.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }

            // Toggle User Dropdown (Click version)
            function toggleUserDropdown(event) {
                event.stopPropagation();
                const menu = document.getElementById('userDropdownMenu');
                const overlay = document.getElementById('dropdownOverlay');

                menu.classList.toggle('show');
                overlay.classList.toggle('show');
            }

            function closeUserDropdown() {
                const menu = document.getElementById('userDropdownMenu');
                const overlay = document.getElementById('dropdownOverlay');

                menu.classList.remove('show');
                overlay.classList.remove('show');
            }

            // Close dropdown on ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeUserDropdown();

                    const cartPanel = document.getElementById('cartPanel');
                    const wishlistPanel = document.getElementById('wishlistPanel');

                    if (cartPanel.classList.contains('active')) {
                        toggleCartPanel();
                    }
                    if (wishlistPanel.classList.contains('active')) {
                        toggleWishlistPanel();
                    }
                }
            });
        </script>