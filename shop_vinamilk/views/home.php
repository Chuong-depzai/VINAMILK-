<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vinamilk - Luôn Vắt Tươi Ngon</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-blue: #0033a0;
            --text-dark: #333;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Be Vietnam Pro', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ===== TOP BAR ===== */
        .top-bar {
            background: var(--primary-blue);
            color: white;
            padding: 10px 0;
        }

        .top-bar-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        .top-links {
            display: flex;
            gap: 25px;
        }

        .top-links a:hover {
            opacity: 0.85;
        }

        /* ===== MAIN HEADER ===== */
        .main-header {
            background: rgba(0, 0, 0, 0.3);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
        }

        .logo {
            height: 50px;
            width: auto;
            filter: brightness(0) invert(1);
        }

        .main-nav {
            display: flex;
            gap: 35px;
            align-items: center;
        }

        .nav-link {
            font-size: 15px;
            font-weight: 600;
            color: white;
            position: relative;
            padding: 10px 0;
            transition: opacity 0.2s;
        }

        .nav-link:hover {
            opacity: 0.7;
        }

        .header-actions {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .icon-btn {
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            padding: 8px;
            color: white;
            transition: opacity 0.2s;
        }

        .icon-btn:hover {
            opacity: 0.7;
        }

        /* ===== HERO CAROUSEL ===== */
        .hero-carousel {
            position: relative;
            width: 100%;
            height: 100vh;
            min-height: 600px;
            overflow: hidden;
            background: #000;
        }

        .carousel-track {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
        }

        .slide.active {
            opacity: 1;
            z-index: 1;
        }

        .slide-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1.08);
        }

        .slide.active .slide-bg {
            animation: zoomIn 10s ease-out forwards;
        }

        @keyframes zoomIn {
            from {
                transform: scale(1.08);
            }

            to {
                transform: scale(1);
            }
        }

        .slide-content {
            position: absolute;
            bottom: 90px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            text-align: center;
            color: #fffff1;
            padding: 0 20px;
            max-width: 1000px;
            width: 100%;
        }

        .slide-title {
            font-size: clamp(38px, 6vw, 68px);
            font-weight: 700;
            margin-bottom: 12px;
            text-transform: capitalize;
            letter-spacing: 0.5px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.4);
        }

        .slide-subtitle {
            font-size: clamp(16px, 2.3vw, 21px);
            font-weight: 400;
            font-style: italic;
            margin-bottom: 0;
            opacity: 0.95;
            text-shadow: 1px 1px 8px rgba(0, 0, 0, 0.4);
        }

        /* Navigation Arrows */
        .carousel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .carousel-arrow:hover {
            background: white;
            transform: translateY(-50%) scale(1.08);
        }

        .carousel-arrow.prev {
            left: 28px;
        }

        .carousel-arrow.next {
            right: 28px;
        }

        .carousel-arrow svg {
            width: 22px;
            height: 22px;
            stroke: var(--primary-blue);
            stroke-width: 3;
            fill: none;
        }

        /* Dots Indicator */
        .carousel-dots {
            position: absolute;
            bottom: 38px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 11px;
            z-index: 10;
        }

        .dot {
            width: 11px;
            height: 11px;
            background: rgba(255, 255, 255, 0.45);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.35s ease;
        }

        .dot.active {
            background: #fffff1;
            width: 42px;
            border-radius: 6px;
        }

        /* ===== AWARDS SECTION ===== */
        .awards {
            background: #f8f9fa;
            padding: 35px 20px;
        }

        .awards-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            text-align: center;
        }

        .award-item {
            padding: 15px;
        }

        .award-icon {
            font-size: 42px;
            margin-bottom: 12px;
        }

        .award-text {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary-blue);
        }

        /* ===== ICE CREAM SECTION ===== */
        .ice-cream {
            position: relative;
            height: 450px;
            display: flex;
            background: linear-gradient(180deg, #e8f4ff 0%, #fff8e1 100%);
        }

        .ice-left,
        .ice-right {
            width: 50%;
            background-size: cover;
            background-position: center;
        }

        .ice-left {
            background-image: url('https://c.animaapp.com/y3F5MTRV/img/frame-3.png');
        }

        .ice-right {
            background-image: url('https://c.animaapp.com/y3F5MTRV/img/frame-2.png');
        }

        .ice-waves {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            z-index: 2;
        }

        /* ===== PRODUCT SECTION ===== */
        .products {
            padding: 70px 20px;
            background: #fffff1;
        }

        .section-title {
            text-align: center;
            font-size: clamp(36px, 6vw, 60px);
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 50px;
        }

        .products-grid {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            gap: 15px;
            justify-content: center;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .product-item {
            cursor: pointer;
            transition: transform 0.3s;
        }

        .product-item:hover {
            transform: translateY(-8px);
        }

        .product-item img {
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.1));
        }

        .color-bar {
            max-width: 1400px;
            height: 10px;
            margin: 35px auto 0;
            background: linear-gradient(90deg, #ff6b6b 0%, #ffd93d 25%, #6bcf7f 50%, #4d96ff 75%, #9b59b6 100%);
            border-radius: 5px;
        }

        /* ===== TECHNOLOGY SECTION ===== */
        .technology {
            padding: 70px 20px;
            background: #f4f7f5;
            text-align: center;
        }

        .tech-title {
            font-size: clamp(32px, 5vw, 58px);
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 18px;
        }

        .tech-desc {
            font-size: clamp(15px, 2.5vw, 18px);
            color: var(--primary-blue);
            max-width: 850px;
            margin: 0 auto 35px;
            line-height: 1.6;
        }

        .tech-banner {
            max-width: 1150px;
            margin: 0 auto;
            border-radius: 14px;
            overflow: hidden;
        }

        .tech-banner img {
            width: 100%;
            height: auto;
            display: block;
        }

        .btn-primary {
            display: inline-block;
            margin-top: 35px;
            padding: 14px 40px;
            background: var(--primary-blue);
            color: white;
            font-weight: 700;
            font-size: 14px;
            border-radius: 25px;
            text-transform: uppercase;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: #002780;
            transform: translateY(-2px);
        }

        /* ===== NET ZERO SECTION ===== */
        .netzero {
            padding: 70px 20px;
            background: #fffff1;
        }

        .netzero-header {
            text-align: center;
            margin-bottom: 55px;
        }

        .netzero-cards {
            max-width: 1350px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 35px;
            margin-bottom: 45px;
        }

        .netzero-card {
            background: white;
            border-radius: 14px;
            padding: 28px;
            transition: transform 0.3s;
        }

        .netzero-card:hover {
            transform: translateY(-6px);
        }

        .card-num {
            font-size: 13px;
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 14px;
        }

        .card-img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            border-radius: 11px;
            margin-bottom: 18px;
        }

        .card-title {
            font-size: clamp(15px, 2.5vw, 17px);
            font-weight: 700;
            font-style: italic;
            color: var(--primary-blue);
            margin-bottom: 14px;
            line-height: 1.4;
        }

        .card-desc {
            font-size: 14px;
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* ===== JOIN US SECTION ===== */
        .joinus {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 550px;
        }

        .joinus-img {
            background: url('https://c.animaapp.com/y3F5MTRV/img/gia-nhap-vnm-bb4e565b8d-be63dac320-1.png') center/cover;
        }

        .joinus-content {
            background: #f4f7f5;
            padding: 70px 55px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .joinus-title {
            font-size: clamp(32px, 5vw, 58px);
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 45px;
        }

        .joinus-items {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .joinus-item {
            position: relative;
        }

        .item-num {
            font-size: 13px;
            color: var(--primary-blue);
            font-weight: 600;
            float: right;
            margin-bottom: 9px;
        }

        .item-title {
            font-size: clamp(17px, 2.8vw, 21px);
            font-weight: 700;
            font-style: italic;
            color: var(--primary-blue);
            margin-bottom: 13px;
            clear: both;
        }

        .item-line {
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-blue) 0%, transparent 100%);
            margin-bottom: 13px;
        }

        .item-desc {
            font-size: 15px;
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* ===== FOOTER ===== */
        .footer-banner {
            padding: 35px 20px;
            background: #fffff1;
        }

        .footer-banner img {
            max-width: 1350px;
            width: 100%;
            margin: 0 auto;
            display: block;
        }

        .footer-social {
            background: #fffff1;
            padding: 35px 55px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 25px;
        }

        .social-icons {
            display: flex;
            gap: 22px;
            align-items: center;
        }

        .social-icon {
            font-size: 26px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .social-icon:hover {
            transform: scale(1.12);
        }

        .footer-cta-group {
            display: flex;
            gap: 18px;
            align-items: center;
            flex-wrap: wrap;
        }

        .cta-btn {
            padding: 11px 28px;
            background: var(--primary-blue);
            color: white;
            border-radius: 22px;
            font-weight: 700;
            font-size: 13px;
            transition: all 0.3s;
        }

        .cta-btn:hover {
            background: #002780;
            transform: translateY(-2px);
        }

        .footer-logo {
            height: 42px;
        }

        .footer-info {
            background: #fffff1;
            padding: 35px 55px;
            text-align: center;
        }

        .footer-text {
            font-size: 12px;
            color: #414ec0;
            line-height: 1.8;
            margin-bottom: 11px;
        }

        .footer-text a {
            color: #414ec0;
            text-decoration: underline;
        }

        .footer-text a:hover {
            color: var(--primary-blue);
        }

        .footer-bottom-img {
            width: 100%;
        }

        .footer-bottom-img img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .joinus {
                grid-template-columns: 1fr;
            }

            .joinus-img {
                min-height: 380px;
            }

            .joinus-content {
                padding: 55px 35px;
            }

            .main-nav {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .hero-carousel {
                min-height: 500px;
            }

            .carousel-arrow {
                width: 38px;
                height: 38px;
            }

            .carousel-arrow.prev {
                left: 12px;
            }

            .carousel-arrow.next {
                right: 12px;
            }

            .ice-cream {
                height: 350px;
            }

            .products-grid {
                gap: 10px;
            }

            .netzero-cards {
                grid-template-columns: 1fr;
                gap: 22px;
            }

            .footer-social {
                flex-direction: column;
                padding: 28px 18px;
            }

            .social-icons {
                justify-content: center;
            }

            .footer-cta-group {
                justify-content: center;
            }

            .footer-info {
                padding: 28px 18px;
            }

            .joinus-content {
                padding: 35px 18px;
            }

            .joinus-items {
                gap: 22px;
            }
        }

        @media (max-width: 480px) {
            .card-img {
                height: 200px;
            }

            .item-num {
                float: none;
                display: block;
                margin-bottom: 7px;
            }
        }
    </style>
</head>

<body>




    <!-- HERO CAROUSEL -->
    <section class="hero-carousel">
        <div class="carousel-track">
            <!-- Slide 1 -->
            <div class="slide active">
                <img src="https://c.animaapp.com/y3F5MTRV/img/vnm-netzero-2-1.png" alt="Vinamilk" class="slide-bg">
                <div class="slide-content">
                    <h1 class="slide-title">LUÔN VẮT TƯƠI NGON</h1>
                    <p class="slide-subtitle">từ 14 trang trại xanh trải dài toàn quốc</p>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="slide">
                <img src="https://cdn.baohatinh.vn/images/5138a140b27369b8efcbb23e3764f0fdc77fc81ee3e051bd81425403c6c2b3237411926c74fab095fed68a5782dc0313/anh-02a-2.jpg" alt="Slide 2" class="slide-bg">
                <div class="slide-content">
                    <h1 class="slide-title">VƯỢT THIÊN TAI<br>Tiếp bước tương lai - 2025</h1>
                    <p class="slide-subtitle">bạn góp một - vinamilk góp</p>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="slide">
                <img src="https://i.ex-cdn.com/giadinhonline.vn/files/content/2025/01/07/hinh-vnm-v1-8-0918.jpg" alt="Slide 3" class="slide-bg">
                <div class="slide-content">
                    <h1 class="slide-title">Luôn Sạch Tinh Khiết </h1>
                    <p class="slide-subtitle">từ 14 nhà máy công nghệ hàng đầu trên thế giới</p>
                </div>
            </div>

            <!-- Slide 4 -->
            <div class="slide">
                <img src="https://media.vneconomy.vn/images/upload/2023/07/14/4c3728cd-1171-4569-9c87-2c16a152d816.jpg" alt="Slide 4" class="slide-bg">
                <div class="slide-content">
                    <h1 class="slide-title">LUÔN ĐỦ LỰA CHỌN</h1>
                    <p class="slide-subtitle">cho cả gia đình</p>
                </div>
            </div>

            <!-- Slide 5 -->
            <div class="slide">
                <img src="https://d8um25gjecm9v.cloudfront.net/cms/LDP_Hero_Banner_Desktop_42eb14dccf.webp" alt="Slide 5" class="slide-bg">
                <div class="slide-content">

                </div>
            </div>
        </div>

        <!-- Navigation -->
        <button class="carousel-arrow prev" onclick="changeSlide(-1)">
            <svg viewBox="0 0 24 24">
                <path d="M15 18l-6-6 6-6" />
            </svg>
        </button>
        <button class="carousel-arrow next" onclick="changeSlide(1)">
            <svg viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6" />
            </svg>
        </button>

        <!-- Dots -->
        <div class="carousel-dots">
            <span class="dot active" onclick="goToSlide(0)"></span>
            <span class="dot" onclick="goToSlide(1)"></span>
            <span class="dot" onclick="goToSlide(2)"></span>
            <span class="dot" onclick="goToSlide(3)"></span>
            <span class="dot" onclick="goToSlide(4)"></span>
        </div>
    </section>

    <!-- AWARDS -->
    <section class="awards">
        <div class="awards-grid">
            <div class="award-item">
                <div class="award-icon">🏆</div>
                <div class="award-text">Thương hiệu Quốc gia</div>
            </div>
            <div class="award-item">
                <div class="award-icon">⭐</div>
                <div class="award-text">Top 100 Nơi làm việc tốt nhất</div>
            </div>
            <div class="award-item">
                <div class="award-icon">🌱</div>
                <div class="award-text">Net Zero 2050</div>
            </div>
            <div class="award-item">
                <div class="award-icon">💚</div>
                <div class="award-text">Sản phẩm Xanh</div>
            </div>
        </div>
    </section>

    <!-- ICE CREAM -->
    <section class="ice-cream">
        <div class="ice-left"></div>
        <div class="ice-right"></div>
        <img src="https://c.animaapp.com/y3F5MTRV/img/waves@2x.png" alt="Waves" class="ice-waves">
    </section>

    <!-- PRODUCTS -->
    <section class="products">
        <h2 class="section-title">Mời Bạn Sắm Sữa</h2>
        <div class="products-grid">
            <div class="product-item">
                <img src="https://c.animaapp.com/y3F5MTRV/img/sp-21-side-9a37f0593e-9423a3cdfe-1@2x.png" alt="Product" style="height:260px">
            </div>
            <div class="product-item">
                <img src="https://c.animaapp.com/y3F5MTRV/img/sp-19-side-665c54b37b-ff6e9dfb31-1@2x.png" alt="Product" style="height:220px">
            </div>
            <div class="product-item">
                <img src="https://c.animaapp.com/y3F5MTRV/img/sp-1-side-4846bd0415-65223fdbc1-1@2x.png" alt="Product" style="height:280px">
            </div>
            <div class="product-item">
                <img src="https://c.animaapp.com/y3F5MTRV/img/sp-2-side-fd31695433-acdfead97a-1@2x.png" alt="Product" style="height:220px">
            </div>
            <div class="product-item">
                <img src="https://c.animaapp.com/y3F5MTRV/img/sp-13-side-35aab76c3e-4a3e6ea55a-1@2x.png" alt="Product" style="height:280px">
            </div>
            <div class="product-item">
                <img src="https://c.animaapp.com/y3F5MTRV/img/sp-26-side-8a9509923e-85df7697ec-1@2x.png" alt="Product" style="height:240px">
            </div>
            <div class="product-item">
                <img src="https://c.animaapp.com/y3F5MTRV/img/sp-24-front-104a1821ac-cf82b2a1eb-1@2x.png" alt="Product" style="height:290px">
            </div>
            <div class="product-item">
                <img src="https://c.animaapp.com/y3F5MTRV/img/sp-22-side-6c958a5103-aafbafc314-1@2x.png" alt="Product" style="height:290px">
            </div>
        </div>
        <div class="color-bar"></div>
    </section>

    <!-- TECHNOLOGY SECTION -->
    <section class="technology">
        <h2 class="tech-title">Cầu tiến là bí quyết</h2>
        <p class="tech-desc">
            Không ngừng tìm kiếm, ứng dụng công nghệ sản xuất tiên tiến nhất để đáp ứng những tiêu chuẩn khắt khe nhất của chính Vinamilk.
        </p>
        <div class="tech-banner">
            <img src="https://c.animaapp.com/y3F5MTRV/img/tech-banner-desktop-cce7be112e-1.png" alt="Technology">
        </div>
        <a href="#" class="btn-primary">Tìm hiểu thêm</a>
    </section>

    <!-- NET ZERO SECTION -->
    <section class="netzero">
        <div class="netzero-header">
            <h2 class="section-title">Để Tâm Để Hành Động</h2>
            <p class="tech-desc">
                Chỉ 1 năm sau kế hoạch Net Zero 2050, Vinamilk có 3 đơn vị đạt<!-- PHẦN 2: Copy phần này và dán tiếp sau "đạt" trong Phần 1 -->

                Chứng nhận Quốc tế về Trung hòa Carbon
            </p>
        </div>

        <div class="netzero-cards">
            <div class="netzero-card">
                <div class="card-num">01</div>
                <img src="https://d8um25gjecm9v.cloudfront.net/cms/home_ptbv_01_647efd4c2a.webp" alt="Factory" class="card-img">
                <h3 class="card-title">Mảnh ghép mới trong hệ thống nhà máy "xanh"</h3>
                <p class="card-desc">
                    Trước thềm Đại hội đồng cổ đông thường niên 2024, Vinamilk công bố Nhà máy Nước giải khát Việt Nam đạt trung hòa Carbon theo tiêu chuẩn quốc tế PAS
                </p>
            </div>

            <div class="netzero-card">
                <div class="card-num">02</div>
                <img src="https://c.animaapp.com/y3F5MTRV/img/home-ptbv-01-647efd4c2a-2@2x.png" alt="Carbon Neutral" class="card-img">
                <h3 class="card-title">3 đơn vị đạt chứng nhận về trung hòa Carbon</h3>
                <p class="card-desc">
                    Vinamilk đang sở hữu 2 nhà máy và 1 trang trại đạt chứng nhận về trung hòa Carbon, cho thấy những bước tiến quyết liệt trên con đường tiến đến mục tiêu Net Zero vào năm 2050.
                </p>
            </div>

            <div class="netzero-card">
                <div class="card-num">03</div>
                <img src="https://d8um25gjecm9v.cloudfront.net/cms/home_ptbv_03_cd8161be5c.webp" alt="Commitment" class="card-img">
                <h3 class="card-title">Cam kết và Lộ trình tiến đến Net Zero vào năm 2050</h3>
                <p class="card-desc">
                    Tiên phong về phát triển bền vững, Vinamilk đặt mục tiêu cắt giảm 15% phát thải khí nhà kính vào 2027, 55% vào năm 2035 và tiến đến phát thải ròng bằng 0 vào năm 2050.
                </p>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="#" class="btn-primary">Tìm hiểu thêm</a>
        </div>
    </section>

    <!-- JOIN US SECTION -->
    <section class="joinus">
        <div class="joinus-img"></div>
        <div class="joinus-content">
            <h2 class="joinus-title">Mời Vào Đội</h2>

            <div class="joinus-items">
                <div class="joinus-item">
                    <span class="item-num">01</span>
                    <h3 class="item-title">Job pháp phới, chủ động đội mới!</h3>
                    <div class="item-line"></div>
                    <p class="item-desc">Vinamilk mong sánh vai cùng bạn.</p>
                </div>

                <div class="joinus-item">
                    <span class="item-num">02</span>
                    <h3 class="item-title">Chất Vinamilk</h3>
                    <div class="item-line"></div>
                    <p class="item-desc">Chung tinh thần. Chung hành động. Chung giá trị.</p>
                </div>

                <div class="joinus-item">
                    <span class="item-num">03</span>
                    <h3 class="item-title">Nhịp sống Vinamilk</h3>
                    <div class="item-line"></div>
                    <p class="item-desc">Những câu chuyện đồng đội kể.</p>
                </div>
            </div>

            <div style="margin-top: 40px;">
                <a href="#" class="btn-primary">Ứng tuyển ngay</a>
            </div>
        </div>
    </section>

    <!-- FOOTER BANNER -->
    <section class="footer-banner">
        <img src="https://c.animaapp.com/y3F5MTRV/img/frame-52.png" alt="Vinamilk Banner">
    </section>

    <!-- FOOTER SOCIAL -->
    <section class="footer-social">
        <div class="social-icons">
            <a href="#" class="social-icon" title="Facebook">📘</a>
            <a href="#" class="social-icon" title="Instagram">📷</a>
            <a href="#" class="social-icon" title="YouTube">▶️</a>
            <a href="#" class="social-icon" title="TikTok">🎵</a>
            <a href="#" class="social-icon" title="Zalo">💬</a>
        </div>

        <div class="footer-cta-group">
            <a href="#" class="cta-btn">Đăng ký nhận tin</a>
            <img src="https://c.animaapp.com/y3F5MTRV/img/logosalenoti-1@2x.png" alt="Sale Noti" class="footer-logo">
            <img src="https://c.animaapp.com/y3F5MTRV/img/logoccdv-2@2x.png" alt="CCDV" class="footer-logo">
        </div>
    </section>

    <!-- FOOTER INFO -->
    <section class="footer-info">
        <p class="footer-text">
            Bản quyền thuộc về Vinamilk © 2025
            <a href="#" target="_blank">Điều khoản sử dụng</a> |
            <a href="#" target="_blank">Chính sách bảo mật</a> |
            <a href="#" target="_blank">Quy chế hoạt động</a>
        </p>
        <p class="footer-text">
            Số giấy chứng nhận đăng ký doanh nghiệp: 0300588569. Cấp lần đầu ngày: 20/11/2003. Nơi cấp: Sở Tài chính Thành phố Hồ Chí Minh
        </p>
        <p class="footer-text">
            Giấy phép kinh doanh dịch vụ thương mại điện tử số: 0300588569/KD-0956. Cấp lần đầu ngày 25/06/2024. Nơi cấp: Sở Công thương TP. Hồ Chí Minh.
        </p>
        <p class="footer-text">
            Vinamilk đang trong quá trình chuyển đổi và nâng cấp hệ thống, các thông tin trên website đang được cập nhật và sẽ hoàn thiện chậm nhất vào ngày 30/08/2025.
        </p>
    </section>

    <!-- FOOTER BOTTOM IMAGE -->
    <div class="footer-bottom-img">
        <img src="https://c.animaapp.com/y3F5MTRV/img/57aed46263ff3c7f1704feb453ff6d3e-2.png" alt="Vinamilk Footer">
    </div>

    <!-- JAVASCRIPT -->
    <script>
        // Carousel Auto Slide
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = slides.length;

        function showSlide(n) {
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));

            currentSlide = (n + totalSlides) % totalSlides;

            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }

        function changeSlide(direction) {
            showSlide(currentSlide + direction);
        }

        function goToSlide(n) {
            showSlide(n);
        }

        // Auto slide every 6 seconds
        setInterval(() => {
            changeSlide(1);
        }, 6000);
    </script>

</body>

</html>