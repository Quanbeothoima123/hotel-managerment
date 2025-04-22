<?php
function hrm_register_menu() {
    add_menu_page(
        'Quản lý loại phòng',
        'Loại phòng',
        'manage_options',
        'hotel-room-management',
        'hrm_render_admin_page',
        'dashicons-admin-home',
        25
    );
}
add_action('admin_menu', 'hrm_register_menu');

function hrm_render_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'hotel_rooms';

    // Xử lý thêm hoặc xóa
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $room_type = sanitize_text_field($_POST['room_type']);
            $quantity = intval($_POST['quantity']);
            $price = floatval($_POST['price']);

            if ($room_type && $quantity > 0 && $price > 0) {
                $wpdb->insert($table_name, [
                    'room_type' => $room_type,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = intval($_POST['id']);
            $wpdb->delete($table_name, ['id' => $id]);
        }
    }

    // Lấy danh sách
    $rooms = $wpdb->get_results("SELECT * FROM $table_name");

    ?>
    <div class="wrap">
        <h1>Quản lý loại phòng</h1>
        <form method="POST" style="max-width: 600px;">
            <input type="hidden" name="action" value="add">
            <table class="form-table">
                <tr>
                    <th><label for="room_type">Loại phòng</label></th>
                    <td><input type="text" name="room_type" required class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="quantity">Số lượng</label></th>
                    <td><input type="number" name="quantity" required min="1" class="small-text"></td>
                </tr>
                <tr>
                    <th><label for="price">Đơn giá/ngày đêm</label></th>
                    <td><input type="number" name="price" step="0.01" required class="regular-text"></td>
                </tr>
            </table>
            <p>
                <button type="submit" class="button button-primary">Thêm mới</button>
            </p>
        </form>

        <h2>Danh sách loại phòng</h2>
        <div style="overflow-x: auto;">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Loại phòng</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $room): ?>
                        <tr>
                            <td><?php echo $room->id; ?></td>
                            <td><?php echo esc_html($room->room_type); ?></td>
                            <td><?php echo esc_html($room->quantity); ?></td>
                            <td><?php echo number_format($room->price, 0, ',', '.'); ?>đ</td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $room->id; ?>">
                                    <button type="submit" class="button button-link-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($rooms)): ?>
                        <tr><td colspan="5">Chưa có loại phòng nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
