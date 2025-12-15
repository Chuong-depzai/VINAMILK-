</main>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3 class="footer-title">CÔNG TY CỔ PHẦN SỮA VIỆT NAM</h3>
            <p class="footer-text">Địa chỉ trụ sở: 10 Tân Trào, Phường Tân Phú, Thành phố Hồ Chí Minh</p>
            <p class="footer-text">Điện thoại: 1900 636 979</p>
            <p class="footer-text">Email: vinamilk@vinamilk.com.vn</p>
        </div>
        <div class="footer-section">
            <h3 class="footer-title">SẢN PHẨM</h3>
            <ul class="footer-list">
                <li class="footer-list-item"><a href="#" class="footer-link">Sữa bột</a></li>
                <li class="footer-list-item"><a href="#" class="footer-link">Sữa tươi</a></li>
                <li class="footer-list-item"><a href="#" class="footer-link">Sữa chua</a></li>
                <li class="footer-list-item"><a href="#" class="footer-link">Sữa đặc</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3 class="footer-title">HỖ TRỢ KHÁCH HÀNG</h3>
            <ul class="footer-list">
                <li class="footer-list-item"><a href="#" class="footer-link">Chính sách đổi trả</a></li>
                <li class="footer-list-item"><a href="#" class="footer-link">Hướng dẫn mua hàng</a></li>
                <li class="footer-list-item"><a href="#" class="footer-link">Phương thức thanh toán</a></li>
                <li class="footer-list-item"><a href="#" class="footer-link">Liên hệ</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p class="footer-copyright">&copy; 2025 Vinamilk. Tất cả quyền được bảo lưu.</p>

        <!-- ✅ NÚT ĐĂNG XUẤT -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="index.php?controller=auth&action=logout" class="btn-logout-footer" title="Đăng xuất">
                🚪
            </a>
        <?php endif; ?>
    </div>
</footer>

<?php
require_once __DIR__ . '/chatbox_widget.php';
?>

<style>
    /* Nút đăng xuất */
    .btn-logout-footer {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.15);
        color: #bdc3c7;
        text-decoration: none;
        border-radius: 50%;
        font-size: 20px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        margin-top: 15px;
    }

    .btn-logout-footer:hover {
        background-color: #dc3545;
        color: white;
        border-color: #dc3545;
        transform: scale(1.1);
    }
</style>

</body>

</html>