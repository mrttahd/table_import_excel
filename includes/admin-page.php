<?php
if (!defined('ABSPATH')) {
    exit;
}


if (isset($_POST['import_excel'])) {
    require_once TTADATATABLE_PLUGIN_DIR . 'import-functions.php';
    $file = $_FILES['excel_file'];
    if ($file['type'] === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
        $file_path = $file['tmp_name'];
        ttadatatable_import_excel($file_path);
    } else {
        echo '<p>Vui lòng tải lên file Excel (.xlsx).</p>';
    }
}
?>

<h1>Import Dữ liệu</h1>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="excel_file" accept=".xlsx" required>
    <button type="submit" name="import_excel">Import Data</button>
</form>
<p>Tải file excel mẫu <a href="<?=  WP_PLUGIN_URL . '/ttadatatable/template-import.xlsx' ?>">tại đây</a></p>
<?php
 include WP_PLUGIN_DIR . '/ttadatatable/ttadatatable-table.php';