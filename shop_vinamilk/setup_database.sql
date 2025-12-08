-- Tạo database
CREATE DATABASE IF NOT EXISTS vinamilk1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE vinamilk1;

-- Tạo bảng products
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price FLOAT NOT NULL,
  image VARCHAR(255) NOT NULL,
  type VARCHAR(100) NOT NULL,
  description TEXT,
  ingredients TEXT,
  packaging VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu
INSERT INTO products (name, price, image, type, description, ingredients, packaging) VALUES
('Optimum Gold (trên 1 tuổi)', 462914, 'optimum-gold-1.jpg', 'Sữa bột trẻ em', 'Lúc này hầu hết các dưỡng chất thuộc tim tâm sinh công thức Optimum Gold cũng ở HMO dầu tiên tại Việt Nam, giúp xây dựng hệ miễn dịch, tăng đề kháng và phát triển não bộ. Đạt tiêu chuẩn tính khiết an toàn Purity Award của Clean Label Project từ Hoa Kỳ với 200 lượt kiểm nghiệm khắt khc.', 'Sữa (92,5%) (sữa tươi, nước, sữa bột, chất béo sữa), đường, maltodextrin, dầu thực vật, chất nhũ hóa (E471, E322(i)), khoáng chất, vitamin', 'Hộp',
'YokoGold + (trên 1 tuổi)', 461394, 'yokogold-1.jpg', 'Sữa bột trẻ em', 'Sữa mạch shuẩn Nhật (***) YOKOGOLD vi siêu dạnh chất IQ và kứ dưỡng chất IQ & EQ giúp não đạc định hướng về tiêu hội & hệ dưỡng chất IQ & EQ lượn vé các dành chất hóa học ở thể giá để tốt nhất', 'Sữa bột, maltodextrin, đường, dầu thực vật, DHA, vitamin, khoáng chất', 'Hộp'),
('YokoGold + 3 (2-6 tuổi)', 434074, 'yokogold-3.jpg', 'Sữa bột trẻ em', 'Lúc vần hóng trẻ này mọi, cần mạt YokoGold được xung dần cao lượng đặm MFGM để ké lượp náo, tăng tỷ lệ cao mạnh của ké ở chất khóc khớp hóa học ở năm', 'Sữa bột, maltodextrin, đường, dầu thực vật, MFGM, DHA, vitamin, khoáng chất', 'Hộp'),
('Dielac Gold (trên 1 tuổi)', 399583, 'dielac-gold-1.jpg', 'Sữa bột trẻ em', 'Công thức chuyển biệt với 34 dưỡng chất hỗ trợ bé lớn kỳ, cao hơn, tăng đề kháng và phát triển não bộ. Đạt tiêu chuẩn tính khiết an toàn Purity Award của Clean Label Project từ Hoa Kỳ với 200 lượt kiểm nghiệm khắt khc.', 'Sữa (92,5%) (sữa tươi, nước, sữa bột, chất béo sữa), đường, maltodextrin, dầu thực vật, chất nhũ hóa (E471, E322(i))', 'Hộp'),
('Sữa Tươi Vinamilk 100% Không Đường', 8900, 'sua-tuoi-khong-duong.jpg', 'Sữa tươi', 'Sữa tươi tiệt trùng 100% từ sữa bò tươi nguyên chất, không đường, giàu dinh dưỡng tự nhiên, giúp bổ sung canxi và protein cho cơ thể.', 'Sữa tươi 100%', 'Hộp'),
('Sữa Chua Uống Vinamilk Có Đường', 5500, 'sua-chua-uong-duong.jpg', 'Sữa chua', 'Sữa chua uống có đường với hương vị thơm ngon, cung cấp lợi khuẩn probiotic tốt cho hệ tiêu hóa và sức khỏe đường ruột.', 'Sữa tươi, đường, men sữa chua', 'Chai'),
('Sữa Đặc Có Đường Ông Thọ', 28000, 'sua-dac-ong-tho.jpg', 'Sữa đặc', 'Sữa đặc có đường Ông Thọ với hương vị đậm đà, ngọt ngào, thích hợp dùng để pha chế đồ uống hoặc làm nguyên liệu cho các món tráng miệng.', 'Sữa tươi, đường', 'Lon'),
('Vinamilk Green Farm', 9500, 'green-farm.jpg', 'Sữa tươi', 'Sữa tươi tiệt trùng Green Farm từ trang trại bò sữa organic, không chất bảo quản, giàu dưỡng chất tự nhiên.', 'Sữa tươi organic 100%', 'Hộp'),
('Sữa Bột Dielac Alpha Gold', 485000, 'dielac-alpha-gold.jpg', 'Sữa bột người lớn', 'Sữa bột dinh dưỡng dành cho người lớn tuổi và người cần bổ sung dinh dưỡng, giàu protein, canxi và các vitamin thiết yếu.', 'Sữa bột, maltodextrin, đường, vitamin, khoáng chất', 'Hộp'),
('Sữa Chua Vinamilk Không Đường', 5000, 'sua-chua-khong-duong.jpg', 'Sữa chua', 'Sữa chua không đường với lợi khuẩn probiotic, giúp cải thiện hệ tiêu hóa, phù hợp cho người ăn kiêng và người tiểu đường.', 'Sữa tươi, men sữa chua', 'Hộp');
-- Tạo bảng users để lưu thông tin đăng nhập
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  phone VARCHAR(15) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  full_name VARCHAR(100),
  email VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm 1 tài khoản test
INSERT INTO users (phone, password, full_name, email) VALUES
('0123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyễn Văn A', 'test@example.com');
-- Password mặc định là: password123

-- Thêm vào file setup_database.sql

-- Tạo bảng stores để lưu thông tin cửa hàng
CREATE TABLE IF NOT EXISTS stores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  address TEXT NOT NULL,
  city VARCHAR(100) NOT NULL,
  province VARCHAR(100) NOT NULL,
  latitude DECIMAL(10, 8),
  longitude DECIMAL(11, 8),
  phone VARCHAR(20),
  opening_hours VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu cửa hàng mẫu (với tọa độ thực)
INSERT INTO stores (name, address, city, province, latitude, longitude, phone, opening_hours) VALUES
-- Bắc Giang
('Vinamilk Bắc Giang 1', 'Số 665 TDP Minh Khai, P. Chũ, Tx. Chũ', 'Chũ', 'Bắc Giang', 21.2919, 106.1033, '0204.3823.456', '8:00 - 20:00'),
('Vinamilk Bắc Giang 2', 'Số 329 Nguyễn Thị Lưu 2, P. Bắc Giang', 'Bắc Giang', 'Bắc Giang', 21.2819, 106.1933, '0204.3823.457', '8:00 - 20:00'),

-- Bạc Liêu
('Vinamilk Bạc Liêu', 'Số 99 Trần Huỳnh, P.7', 'Bạc Liêu', 'Bạc Liêu', 9.2947, 105.7214, '0291.3822.123', '8:00 - 20:00'),

-- Bắc Ninh
('Vinamilk Bắc Ninh 1', 'Số 519 Ngô Gia Tự, P. Kinh Bắc', 'Bắc Ninh', 'Bắc Ninh', 21.1861, 106.0763, '0222.3822.456', '8:00 - 20:00'),
('Vinamilk Bắc Ninh 2', 'Số 231 Vạn Tiến Dũng, TT. Chờ, H. Yên Phong', 'Yên Phong', 'Bắc Ninh', 21.0847, 105.9847, '0222.3822.457', '8:00 - 20:00'),
('Vinamilk Bắc Ninh 3', 'Số 124 Nguyễn Gia Thiều, P. Suối Hoa', 'Bắc Ninh', 'Bắc Ninh', 21.1713, 106.0611, '0222.3822.458', '8:00 - 20:00'),

-- Bình Dương
('Vinamilk Bình Dương', 'Số 123 Trần Hưng Đạo, KP. Tây A, P. Đông Hòa', 'Dĩ An', 'Bình Dương', 10.9047, 106.7639, '0274.3822.123', '8:00 - 20:00'),

-- Cà Mau
('Vinamilk Cà Mau 1', 'Số 158 - 160 Trần Hưng Đạo, P.5', 'Cà Mau', 'Cà Mau', 9.1767, 105.1524, '0290.3822.123', '8:00 - 20:00'),
('Vinamilk Cà Mau 2', 'Số 94 Lý Thường Kiệt, P.7', 'Cà Mau', 'Cà Mau', 9.1847, 105.1624, '0290.3822.124', '8:00 - 20:00'),

-- Điện Biên
('Vinamilk Điện Biên', 'Số 244, Tổ 10, P. Tân Thanh', 'Điện Biên Phủ', 'Điện Biên', 21.3833, 103.0167, '0215.3822.123', '8:00 - 20:00'),

-- Đồng Tháp
('Vinamilk Đồng Tháp', 'Số 16 Thiên Hộ Dương, P.4', 'Cao Lãnh', 'Đồng Tháp', 10.4667, 105.6333, '0277.3822.123', '8:00 - 20:00'),

-- Hà Nam
('Vinamilk Hà Nam', 'Số 51 Lê Công Thanh, P. Hai Bà Trưng', 'Phủ Lý', 'Hà Nam', 20.5333, 105.9167, '0226.3822.123', '8:00 - 20:00'),

-- Khánh Hòa
('Vinamilk Nha Trang 1', '110 Nguyễn Thị Ngọc Oanh, phường Phước Hải', 'Nha Trang', 'Khánh Hòa', 12.2447, 109.1894, '0258.3822.123', '8:00 - 20:00'),
('Vinamilk Nha Trang 2', 'Đường 23/10, xã Diên Khánh', 'Diên Khánh', 'Khánh Hòa', 12.2547, 109.1594, '0258.3822.124', '8:00 - 20:00'),
('Vinamilk Nha Trang 3', 'Số B3 Chung Cư Vĩnh Phước, Đường 2/4, P. Vĩnh Phước', 'Nha Trang', 'Khánh Hòa', 12.2347, 109.1694, '0258.3822.125', '8:00 - 20:00'),

-- Hà Nội
('Vinamilk Hà Nội 1', '47 Trần Kinh, Tổ 32, P. Trung Hòa, Q. Cầu Giấy', 'Hà Nội', 'Hà Nội', 21.0167, 105.7944, '024.3822.123', '8:00 - 20:00'),
('Vinamilk Hà Nội 2', 'Phố Yên, X. Tiền Phong, H. Mê Linh', 'Hà Nội', 'Hà Nội', 21.1833, 105.7333, '024.3822.124', '8:00 - 20:00'),
('Vinamilk Hà Nội 3', 'Số 19 Trần Nguyên Đán, P. Định Công, Q. Hoàng Mai', 'Hà Nội', 'Hà Nội', 20.9833, 105.8333, '024.3822.125', '8:00 - 20:00'),
('Vinamilk Hà Nội 4', 'Số 15 Đường 2.2, Gamuda Garden, P. Trần Phú, Q. Hoàng Mai', 'Hà Nội', 'Hà Nội', 20.9733, 105.8433, '024.3822.126', '8:00 - 20:00'),
('Vinamilk Hà Nội 5', 'Số 116 Phố Huyền, TT. Quốc Oai', 'Hà Nội', 'Hà Nội', 21.0233, 105.6733, '024.3822.127', '8:00 - 20:00'),

-- TP. Hồ Chí Minh
('Vinamilk Gò Vấp 1', 'Số 689/57 Nguyễn Kiệm, P.3, Q. Gò Vấp', 'TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.8167, 106.6833, '028.3822.123', '8:00 - 20:00'),
('Vinamilk Tân Phú', 'Số 1A Tân Quý, P. Tân Quý, Q. Tân Phú', 'TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.7906, 106.6278, '028.3822.124', '8:00 - 20:00'),
('Vinamilk Bình Chánh', 'Số 247 Nguyễn Hữu Trí, TT. Tân Túc, H. Bình Chánh', 'TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.7383, 106.6111, '028.3822.125', '8:00 - 20:00'),
('Vinamilk Bình Tân', '247 An Dương Vương, P. An Lạc, Q. Bình Tân', 'TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.7556, 106.6078, '028.3822.126', '8:00 - 20:00'),
('Vinamilk Gò Vấp 2', 'Số 64 Đường Quang Trung, P.10, Q. Gò Vấp', 'TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.8356, 106.6628, '028.3822.127', '8:00 - 20:00'),
('Vinamilk Quận 1', 'Số 251-253 Trần Hưng Đạo, P. Cô Giang, Q.1', 'TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.7614, 106.6958, '028.3822.128', '8:00 - 20:00'),

-- Hải Phòng
('Vinamilk Hải Phòng 1', 'Số 109 Miếu Lôi, P. Vĩnh Niệm, Q. Lê Chân', 'Hải Phòng', 'Hải Phòng', 20.8556, 106.6833, '0225.3822.123', '8:00 - 20:00'),
('Vinamilk Hải Phòng 2', 'Số 20 + 2/20 Điện Biên Phủ, P. Máy Tơ, Q. Ngô Quyền', 'Hải Phòng', 'Hải Phòng', 20.8656, 106.6933, '0225.3822.124', '8:00 - 20:00'),

-- Đà Nẵng
('Vinamilk Đà Nẵng', 'Số 454-456 Phan Châu Trinh, Phường Tam Kỳ', 'Đà Nẵng', 'Đà Nẵng', 16.0544, 108.2022, '0236.3822.123', '8:00 - 20:00'),

-- Nghệ An
('Vinamilk Nghệ An', 'Số 118 Đường Tuệ Tĩnh, P. Hà Huy Tập', 'Vinh', 'Nghệ An', 18.6792, 105.6811, '0238.3822.123', '8:00 - 20:00');
USE vinamilk1;

-- Tạo bảng wishlist
CREATE TABLE IF NOT EXISTS wishlist (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_wishlist (user_id, product_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo index để tăng tốc truy vấn
CREATE INDEX idx_user_id ON wishlist(user_id);
CREATE INDEX idx_product_id ON wishlist(product_id);

-- Tạo bản đánh giá
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL DEFAULT 5,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;