<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

function ttadatatable_import_excel($file_path) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ttadatatable';

    require_once ABSPATH . 'wp-content/plugins/ttadatatable/vendor/autoload.php';
    $spreadsheet = IOFactory::load($file_path);
    $sheet = $spreadsheet->getActiveSheet();
    $header = $sheet->rangeToArray('A1:' . $sheet->getHighestColumn() . '1')[0];

    foreach ($header as $column) {
        $wpdb->query("ALTER TABLE $table_name ADD COLUMN IF NOT EXISTS `$column` TEXT");
    }

    $rows = $sheet->toArray(null, true, true, true);
    array_shift($rows);


    foreach ($rows as $row) {
        $data = [
            'ngay' => date('Y-m-d', strtotime($row['A'])),
            'so_tien' => intval($row['B']),
            'noi_dung_ngan_hang' => sanitize_text_field($row['C']),
            'ten_ngan_hang' => sanitize_text_field($row['D']),
            'ten_doi_ung' => sanitize_text_field($row['E']),
            'date_created' => current_time('mysql'),
            'date_updated' => current_time('mysql')
        ];
        $wpdb->insert($table_name, $data);
    }

    echo '<p>Dữ liệu đã được import thành công.</p>';
}
?>
