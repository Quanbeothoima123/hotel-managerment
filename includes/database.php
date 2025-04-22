<?php
function hrm_create_room_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'hotel_rooms';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        room_type VARCHAR(50) NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

function hrm_drop_room_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'hotel_rooms';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}
