<div class="page-container">
    <h1 class="page-title"><?php echo isset($product) ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới'; ?></h1>

    <div class="form-container">
        <form method="POST" enctype="multipart/form-data" class="product-form">
            <div class="form-group">
                <label for="name" class="form-label">Tên sản phẩm <span class="required">*</span></label>
                <input type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    value="<?php echo isset($product) ? htmlspecialchars($product['name']) : ''; ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="price" class="form-label">Giá (VNĐ) <span class="required">*</span></label>
                <input type="number"
                    id="price"
                    name="price"
                    class="form-input"
                    value="<?php echo isset($product) ? $product['price'] : ''; ?>"
                    min="0"
                    step="1"
                    required>
            </div>

            <div class="form-group">
                <label for="type" class="form-label">Loại sản phẩm <span class="required">*</span></label>
                <input type="text"
                    id="type"
                    name="type"
                    class="form-input"
                    value="<?php echo isset($product) ? htmlspecialchars($product['type']) : ''; ?>"
                    placeholder="Ví dụ: Sữa bột trẻ em, Sữa tươi, Sữa chua..."
                    required>
            </div>

            <div class="form-group">
                <label for="packaging" class="form-label">Quy cách đóng gói <span class="required">*</span></label>
                <select id="packaging" name="packaging" class="form-select" required>
                    <option value="Hộp" <?php echo (isset($product) && $product['packaging'] === 'Hộp') ? 'selected' : ''; ?>>Hộp</option>
                    <option value="Thùng" <?php echo (isset($product) && $product['packaging'] === 'Thùng') ? 'selected' : ''; ?>>Thùng</option>
                    <option value="Chai" <?php echo (isset($product) && $product['packaging'] === 'Chai') ? 'selected' : ''; ?>>Chai</option>
                    <option value="Lon" <?php echo (isset($product) && $product['packaging'] === 'Lon') ? 'selected' : ''; ?>>Lon</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image" class="form-label">
                    Hình ảnh sản phẩm <?php echo !isset($product) ? '<span class="required">*</span>' : ''; ?>
                </label>
                <?php if (isset($product) && !empty($product['image'])): ?>
                    <div class="current-image-wrapper">
                        <p class="current-image-label">Ảnh hiện tại:</p>
                        <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>"
                            alt="Current"
                            class="current-image">
                    </div>
                <?php endif; ?>
                <input type="file"
                    id="image"
                    name="image"
                    class="form-input-file"
                    accept="image/*"
                    <?php echo !isset($product) ? 'required' : ''; ?>>
                <p class="form-help-text">Định dạng: JPG, PNG, GIF (tối đa 5MB)</p>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Mô tả sản phẩm</label>
                <textarea id="description"
                    name="description"
                    class="form-textarea"
                    rows="5"><?php echo isset($product) ? htmlspecialchars($product['description']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="ingredients" class="form-label">Thành phần</label>
                <textarea id="ingredients"
                    name="ingredients"
                    class="form-textarea"
                    rows="4"><?php echo isset($product) ? htmlspecialchars($product['ingredients']) : ''; ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <?php echo isset($product) ? 'Cập nhật' : 'Thêm sản phẩm'; ?>
                </button>
                <a href="index.php?controller=product&action=admin" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>