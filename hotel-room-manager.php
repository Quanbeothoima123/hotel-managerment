<?php
/*
Plugin Name: Hotel Room Management
Description: Plugin quản lý loại phòng khách sạn.
Version: 1.0
Author: diepcd
*/

if (!defined('ABSPATH')) exit;

define('HRM_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Hook khi kích hoạt / hủy kích hoạt
register_activation_hook(__FILE__, 'hrm_activate_plugin');
register_deactivation_hook(__FILE__, 'hrm_deactivate_plugin');

function hrm_activate_plugin() {
    require_once HRM_PLUGIN_DIR . 'includes/database.php';
    hrm_create_room_table();
}

function hrm_deactivate_plugin() {
    // Optionally drop table
    // require_once HRM_PLUGIN_DIR . 'includes/database.php';
    // hrm_drop_room_table();
}

// Load trang admin
if (is_admin()) {
    require_once HRM_PLUGIN_DIR . 'includes/admin-page.php';
}

// Shortcode hiển thị danh sách phòng
add_shortcode('room_list', 'hrm_room_list_shortcode');

function hrm_room_list_shortcode() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'hotel_rooms';
    $rooms = $wpdb->get_results("SELECT * FROM $table_name");

    $output = '<div class="room-list">';
    $output .= '<h2>Danh sách loại phòng</h2>';
    $output .= '<table class="room-table">';
    $output .= '<thead>
                    <tr>
                        <th>Loại phòng</th>
                        <th>Số lượng</th>
                        <th>Đơn giá/ngày đêm</th>
                    </tr>
                </thead><tbody>';

    foreach ($rooms as $room) {
        $output .= "<tr>
                        <td>" . esc_html($room->room_type) . "</td>
                        <td>" . esc_html($room->quantity) . "</td>
                        <td>" . number_format($room->price, 0, ',', '.') . "đ</td>
                    </tr>";
    }

    $output .= '</tbody></table></div>';

    return $output;
}

// Load CSS cho frontend
add_action('wp_enqueue_scripts', 'hrm_enqueue_styles');
function hrm_enqueue_styles() {
    wp_enqueue_style('hrm-styles', plugin_dir_url(__FILE__) . 'assets/style.css');
}
