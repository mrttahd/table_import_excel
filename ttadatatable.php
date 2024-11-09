<?php
/*
Plugin Name: Data table
Description: Plugin cho phép import file Excel vào database và hiển thị dưới dạng bảng.
Version: 1.0
Author: TTA
*/

if (!defined('ABSPATH')) {
    exit;
}

define('TTADATATABLE_PLUGIN_DIR', plugin_dir_path(__FILE__) . 'includes/');

register_activation_hook(__FILE__, 'ttadatatable_create_table');
function ttadatatable_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ttadatatable';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ngay DATE NOT NULL,
        so_tien INT NOT NULL,
        noi_dung_ngan_hang VARCHAR(255) NOT NULL,
        ten_ngan_hang VARCHAR(255) NOT NULL,
        ten_doi_ung VARCHAR(255) NOT NULL,
        date_created datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        date_updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('admin_menu', 'ttadatatable_add_admin_menu');
function ttadatatable_add_admin_menu() {
    add_menu_page(
        'Data table',
        'Data table',
        'manage_options',
        'ttadatatable',
        'ttadatatable_admin_page',
        'dashicons-table',
        20
    );
    add_submenu_page(
        null,
        'Chỉnh sửa bản ghi',
        'Chỉnh sửa bản ghi',
        'manage_options',
        'ttadatatable_edit',
        'ttadatatable_edit_page'
    );
}

function ttadatatable_admin_page() {
    require_once TTADATATABLE_PLUGIN_DIR . 'admin-page.php';
}

function ttadatatable_edit_page() {
    require_once TTADATATABLE_PLUGIN_DIR . 'edit-page.php';
}

function ttadatatable_shortcode() {
    ob_start();
    include WP_PLUGIN_DIR . '/ttadatatable/ttadatatable-table.php';
    return ob_get_clean();
}
add_shortcode('tta_datatable', 'ttadatatable_shortcode');


?>
