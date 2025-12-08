<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$cartModel = new Cart();
$cartCount = $cartModel->getCount();

$isLoggedIn = AuthController::isLoggedIn();
$currentUser = AuthController::getCurrentUser();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sữa Tươi | Vinamilk</title>
    <link rel="icon" type="image/png" href="uploads/logo.png">
    <link rel="shortcut icon" href="uploads/logo.png" type="image/png">
    <link rel="apple-touch-icon" href="uploads/logo.png">

    <link rel="stylesheet" href="css/new-style.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* ===== HEADER - TRONG SUỐT BAN ĐẦU ===== */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
        }

        /* Khi scroll: White background */
        .site-header.scrolled {
            background: rgba(255, 255, 255, 0.98) !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(20px);
        }

        /* ===== TOP BAR (Thanh xanh) ===== */
        .header-top {
            background: rgba(0, 51, 160, 0.95);
            backdrop-filter: blur(10px);
            padding: 8px 0;
            transition: all 0.4s ease;
            opacity: 1;
            max-height: 40px;
            overflow: hidden;
        }

        /* Ẩn top bar khi scroll */
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

        /* ===== MAIN HEADER ===== */
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

        /* Thu nhỏ header khi scroll */
        .site-header.scrolled .header-main-content {
            height: 60px;
        }

        /* ===== LOGO ===== */
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

        /* Logo nhỏ hơn khi scroll */
        .site-header.scrolled .logo-img {
            height: 38px;
        }

        /* ===== NAVIGATION ===== */
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

        /* Text màu xanh khi scroll */
        .site-header.scrolled .nav-link {
            color: #0033a0 !important;
        }

        .nav-link:hover {
            opacity: 0.8;
        }

        /* ===== HEADER ACTIONS ===== */
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

        /* Icon màu đen khi scroll */
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

        /* ===== USER DROPDOWN ===== */
        .user-dropdown-wrapper {
            position: relative;
        }

        .user-greeting {
            color: white;
            cursor: pointer;
            font-size: 12px;
            transition: color 0.3s;
        }

        .site-header.scrolled .user-greeting {
            color: #0033a0 !important;
        }

        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            min-width: 200px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .user-dropdown-wrapper:hover .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-menu a {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: background 0.2s;
        }

        .user-dropdown-menu a:hover {
            background: #f5f5f5;
        }

        /* ===== MEGA MENU ===== */
        .has-dropdown {
            position: relative;
        }

        .dropdown-icon {
            width: 10px;
            height: 10px;
            transition: transform 0.3s;
        }

        .has-dropdown:hover .dropdown-icon {
            transform: rotate(180deg);
        }

        .mega-menu {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            min-width: 800px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 30px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .has-dropdown:hover .mega-menu {
            opacity: 1;
            visibility: visible;
        }

        .mega-menu-content {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }

        .mega-menu-title {
            font-size: 14px;
            font-weight: 700;
            color: #0033a0;
            margin-bottom: 15px;
        }

        .mega-menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mega-menu-list li {
            margin-bottom: 10px;
        }

        .mega-menu-list a {
            color: #666;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }

        .mega-menu-list a:hover {
            color: #0033a0;
        }

        /* ===== SEARCH OVERLAY ===== */
        .search-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .search-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .search-overlay-content {
            max-width: 800px;
            width: 90%;
            position: relative;
        }

        .search-close {
            position: absolute;
            top: -50px;
            right: 0;
            background: none;
            border: none;
            color: white;
            font-size: 40px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 20px 30px;
            font-size: 18px;
            border: none;
            border-radius: 50px;
            outline: none;
        }

        .search-submit {
            padding: 20px 40px;
            background: #0033a0;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }

        .search-submit:hover {
            background: #002780;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .main-nav {
                display: none;
            }

            .header-main-content {
                justify-content: space-between;
            }

            .mega-menu {
                min-width: 600px;
            }

            .mega-menu-content {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .header-top-content {
                padding: 0 20px;
                font-size: 11px;
                flex-direction: column;
                gap: 3px;
            }

            .header-main .container-header {
                padding: 0 20px;
            }

            .header-main-content {
                height: 60px;
            }

            .logo-img {
                height: 40px;
            }

            .site-header.scrolled .header-main-content {
                height: 55px;
            }

            .site-header.scrolled .logo-img {
                height: 35px;
            }

            .mega-menu {
                min-width: 90vw;
                left: 5vw;
                transform: none;
            }

            .mega-menu-content {
                grid-template-columns: 1fr;
                gap: 20px;
            }
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
                                <span class="user-greeting">
                                    👤 Xin chào, <?php echo htmlspecialchars($currentUser['name'] ?: $currentUser['phone']); ?>
                                </span>

                                <div class="user-dropdown-menu">
                                    <a href="index.php?controller=user&action=profile">Thông tin cá nhân</a>
                                    <a href="index.php?controller=order&action=history">Đơn hàng của tôi</a>
                                    <a href="index.php?controller=wishlist">Danh sách yêu thích</a>
                                    <a href="index.php?controller=auth&action=logout">Đăng xuất</a>
                                </div>
                            </div>
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
                    <!-- Logo -->
                    <a href="index.php" class="site-logo">
                        <img src="uploads/vinamilk-logo_brandlogos.net_quayf.png" alt="Vinamilk" class="logo-img">
                    </a>

                    <!-- Navigation -->
                    <nav class="main-nav">
                        <ul class="nav-menu">
                            <li class="nav-item">
                                <a href="index.php" class="nav-link">Trang chủ</a>
                            </li>

                            <li class="nav-item has-dropdown">
                                <a href="index.php?controller=product&action=productList" class="nav-link">
                                    Sản phẩm
                                    <svg class="dropdown-icon" width="10" height="6" viewBox="0 0 10 6" fill="none">
                                        <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="2" />
                                    </svg>
                                </a>

                                <!-- Mega menu -->
                                <div class="mega-menu">
                                    <div class="mega-menu-content">
                                        <div class="mega-menu-column">
                                            <h4 class="mega-menu-title">Sữa bột trẻ em</h4>
                                            <ul class="mega-menu-list">
                                                <li><a href="?type=optimum">Optimum Gold</a></li>
                                                <li><a href="?type=yoko">YokoGold +</a></li>
                                                <li><a href="?type=dielac">Dielac Alpha Gold</a></li>
                                            </ul>
                                        </div>

                                        <div class="mega-menu-column">
                                            <h4 class="mega-menu-title">Sữa tươi</h4>
                                            <ul class="mega-menu-list">
                                                <li><a href="?type=100">Sữa tươi 100%</a></li>
                                                <li><a href="?type=green-farm">Green Farm</a></li>
                                                <li><a href="?type=organic">Sữa tươi Organic</a></li>
                                            </ul>
                                        </div>

                                        <div class="mega-menu-column">
                                            <h4 class="mega-menu-title">Sữa chua</h4>
                                            <ul class="mega-menu-list">
                                                <li><a href="?type=vinamilk-yogurt">Sữa chua uống</a></li>
                                                <li><a href="?type=probi">Probi</a></li>
                                                <li><a href="?type=yo">Sữa chua Yo</a></li>
                                            </ul>
                                        </div>

                                        <div class="mega-menu-column">
                                            <h4 class="mega-menu-title">Sản phẩm khác</h4>
                                            <ul class="mega-menu-list">
                                                <li><a href="?type=condensed-milk">Sữa đặc</a></li>
                                                <li><a href="?type=ice-cream">Kem</a></li>
                                                <li><a href="?type=cheese">Phô mai</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a href="index.php?controller=store" class="nav-link">Cửa hàng</a>
                            </li>

                            <li class="nav-item">
                                <a href="#about" class="nav-link">Về Vinamilk</a>
                            </li>

                            <?php if (AuthController::isAdmin()): ?>
                                <li class="nav-item">
                                    <a href="index.php?controller=admin&action=dashboard" class="nav-link">Admin</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>

                    <!-- Header actions -->
                    <div class="header-actions">
                        <!-- Search -->
                        <button class="action-btn search-btn" onclick="toggleSearch()" aria-label="Tìm kiếm">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                                <path d="M19 19L13 13M15 8C15 11.866 11.866 15 8 15C4.13401 15 1 11.866 1 8C1 4.13401 4.13401 1 8 1C11.866 1 15 4.13401 15 8Z" stroke="currentColor" stroke-width="2" />
                            </svg>
                        </button>

                        <!-- Wishlist -->
                        <?php if ($isLoggedIn): ?>
                            <a href="index.php?controller=wishlist" class="action-btn wishlist-btn" aria-label="Yêu thích">
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                                    <path d="M10 18L2 10C0.5 8.5 0 6.5 1 4.5C2 2.5 4 2 6 3C7 3.5 8 4.5 10 7C12 4.5 13 3.5 14 3C16 2 18 2.5 19 4.5C20 6.5 19.5 8.5 18 10L10 18Z" stroke="currentColor" stroke-width="2" />
                                </svg>
                                <span id="wishlist-count-badge" class="badge" style="display: none;">0</span>
                            </a>
                        <?php endif; ?>

                        <!-- Cart -->
                        <a href="index.php?controller=cart&action=view" class="action-btn cart-btn" aria-label="Giỏ hàng">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                                <path d="M1 1H4L6 13H16L18 5H5" stroke="currentColor" stroke-width="2" />
                                <circle cx="7" cy="17" r="1" fill="currentColor" />
                                <circle cx="15" cy="17" r="1" fill="currentColor" />
                            </svg>
                            <?php if ($cartCount > 0): ?>
                                <span class="badge"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search overlay -->
        <div class="search-overlay" id="searchOverlay">
            <div class="search-overlay-content">
                <button class="search-close" onclick="toggleSearch()">×</button>
                <form class="search-form" action="index.php" method="GET">
                    <input type="hidden" name="controller" value="product">
                    <input type="hidden" name="action" value="search">
                    <input type="text" name="keyword" class="search-input" placeholder="Tìm kiếm sản phẩm..." autofocus>
                    <button type="submit" class="search-submit">Tìm kiếm</button>
                </form>
            </div>
        </div>
    </header>

    <main class="site-content">

        <script>
            // ========== SMOOTH HEADER SCROLL EFFECT ==========
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

            // ========== TOGGLE SEARCH ==========
            function toggleSearch() {
                const overlay = document.getElementById('searchOverlay');
                overlay.classList.toggle('active');

                if (overlay.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                    setTimeout(() => {
                        overlay.querySelector('.search-input')?.focus();
                    }, 100);
                } else {
                    document.body.style.overflow = '';
                }
            }

            // ESC key to close search
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    const overlay = document.getElementById('searchOverlay');
                    if (overlay?.classList.contains('active')) {
                        toggleSearch();
                    }
                }
            });

            // Click outside to close search
            document.getElementById('searchOverlay')?.addEventListener('click', (e) => {
                if (e.target.id === 'searchOverlay') {
                    toggleSearch();
                }
            });

            // ========== SMOOTH SCROLL ==========
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href !== '#' && document.querySelector(href)) {
                        e.preventDefault();
                        document.querySelector(href).scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        </script>