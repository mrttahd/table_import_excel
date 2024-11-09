<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'ttadatatable';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$record = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));

if (!$record) {
    echo '<p>Bản ghi không tồn tại.</p>';
    return;
}

if (isset($_POST['update_record'])) {
    $ngay = sanitize_text_field($_POST['ngay']);
    $so_tien = sanitize_text_field($_POST['so_tien']);
    $noi_dung_ngan_hang = sanitize_textarea_field($_POST['noi_dung_ngan_hang']);
    $ten_ngan_hang = sanitize_text_field($_POST['ten_ngan_hang']);
    $ten_doi_ung = sanitize_text_field($_POST['ten_doi_ung']);

    $wpdb->update(
        $table_name,
        [
            'ngay' => $ngay,
            'so_tien' => $so_tien,
            'noi_dung_ngan_hang' => $noi_dung_ngan_hang,
            'ten_ngan_hang' => $ten_ngan_hang,
            'ten_doi_ung' => $ten_doi_ung,
            'date_updated' => current_time('mysql')
        ],
        ['id' => $id]
    );

    echo '<p>Bản ghi đã được cập nhật thành công.</p>';
    $record = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
}

if (isset($_POST['delete_record'])) {
    $wpdb->delete($table_name, ['id' => $id]);
    echo '<p>Bản ghi đã bị xóa.</p>';
    return;
}
?>

<h1>Chỉnh sửa dữ liệu</h1>

<form method="post" style="max-width: 500px;">
    <label for="ngay">Ngày:</label>
    <input type="date" name="ngay" id="ngay" value="<?php echo esc_attr($record->ngay); ?>" style="width: 100%; min-width: 250px;">

    <label for="so_tien">Số tiền:</label>
    <input type="number" name="so_tien" id="so_tien" value="<?php echo esc_attr($record->so_tien); ?>" style="width: 100%; min-width: 250px;">

    <label for="noi_dung_ngan_hang">Nội dung Ngân hàng:</label>
    <textarea name="noi_dung_ngan_hang" id="noi_dung_ngan_hang" rows="4" style="width: 100%; min-width: 250px;"><?php echo esc_textarea($record->noi_dung_ngan_hang); ?></textarea>

    <label for="ten_ngan_hang">Tên Ngân hàng:</label>
    <input type="text" name="ten_ngan_hang" id="ten_ngan_hang" value="<?php echo esc_attr($record->ten_ngan_hang); ?>" style="width: 100%; min-width: 250px;">

    <label for="ten_doi_ung">Tên Đối Ứng:</label>
    <input type="text" name="ten_doi_ung" id="ten_doi_ung" value="<?php echo esc_attr($record->ten_doi_ung); ?>" style="width: 100%; min-width: 250px;">

    <button type="submit" name="update_record" style="margin-top: 10px; padding: 8px 16px;">Cập nhật</button>
    <button type="submit" name="delete_record" style="margin-top: 10px; padding: 8px 16px; background-color: red; color: white;">Xóa</button>
</form>

<style>
    form label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
    }
    form input, form textarea {
        margin-top: 5px;
        padding: 8px;
    }
</style>
