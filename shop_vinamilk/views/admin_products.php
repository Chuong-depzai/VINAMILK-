<div class="page-container">
    <div class="admin-header">
        <h1 class="page-title">Quản lý sản phẩm</h1>
        <a href="index.php?controller=product&action=create" class="btn-add-product">+ Thêm sản phẩm mới</a>
    </div>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <p class="empty-state-text">Chưa có sản phẩm nào.</p>
        </div>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead class="admin-table-head">
                    <tr class="admin-table-row">
                        <th class="admin-table-header">ID</th>
                        <th class="admin-table-header">Hình ảnh</th>
                        <th class="admin-table-header">Tên sản phẩm</th>
                        <th class="admin-table-header">Giá</th>
                        <th class="admin-table-header">Loại</th>
                        <th class="admin-table-header">Quy cách</th>
                        <th class="admin-table-header">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="admin-table-body">
                    <?php foreach ($products as $product): ?>
                        <tr class="admin-table-row">
                            <td class="admin-table-cell"><?php echo $product['id']; ?></td>
                            <td class="admin-table-cell">
                                <?php
                                $imagePath = "uploads/" . htmlspecialchars($product['image']);
                                if (file_exists(__DIR__ . '/../uploads/' . $product['image'])):
                                ?>
                                    <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="admin-product-image">
                                <?php else: ?>
                                    <span class="admin-no-image">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td class="admin-table-cell"><?php echo htmlspecialchars($product['name']); ?></td>
                            <td class="admin-table-cell"><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</td>
                            <td class="admin-table-cell"><?php echo htmlspecialchars($product['type']); ?></td>
                            <td class="admin-table-cell"><?php echo htmlspecialchars($product['packaging']); ?></td>
                            <td class="admin-table-cell">
                                <div class="admin-action-buttons">
                                    <a href="index.php?controller=product&action=edit&id=<?php echo $product['id']; ?>" class="btn-edit">Sửa</a>
                                    <a href="index.php?controller=product&action=delete&id=<?php echo $product['id']; ?>"
                                        class="btn-delete"
                                        onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                        Xóa
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>