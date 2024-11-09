<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'ttadatatable';
$is_admin_page = is_admin() && current_user_can('administrator');
?>
<div class="table_wrapper" style="width:100%; max-width:100%">
    <h2>Dữ liệu hiện tại trong bảng</h2>

    <div style="margin-bottom: 20px;">
        <div>
            <label for="filter_so_tien">Lọc theo Số tiền:</label>
            <select id="filter_so_tien">
                <option value="">Tất cả</option>
                <option value="0-500000">0 - 500.000</option>
                <option value="500001-5000000">500.001 - 5.000.000</option>
                <option value="5000001-10000000">5.000.001 - 10.000.000</option>
                <option value="10000001">Lớn hơn 10.000.000</option>
            </select>
        </div>
        <div>
            <label for="filter_ten_ngan_hang">Lọc theo Tên Ngân hàng:</label>
            <select id="filter_ten_ngan_hang">
                <option value="">Tất cả</option>
                <?php
                $ten_ngan_hang_results = $wpdb->get_col("SELECT DISTINCT ten_ngan_hang FROM $table_name ORDER BY ten_ngan_hang");
                foreach ($ten_ngan_hang_results as $ten_ngan_hang) {
                    echo '<option value="' . esc_attr($ten_ngan_hang) . '">' . esc_html($ten_ngan_hang) . '</option>';
                }
                ?>
            </select>
        </div>
    </div>

    <table id="data-table" class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ngày</th>
                <th>Số tiền</th>
                <th>Nội dung Ngân hàng</th>
                <th>Tên Ngân Hàng</th>
                <th>Tên Đối Ứng</th>
                <?php if ($is_admin_page): ?>
                    <th>Ngày tạo</th>
                    <th>Ngày cập nhật</th>
                    <th>Hành động</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date_created DESC");
            if ($results) {
                foreach ($results as $row) {
                    echo '<tr>';
                    echo '<td>' . esc_html($row->id) . '</td>';
                    echo '<td>' . esc_html($row->ngay) . '</td>';
                    echo '<td>' . esc_html($row->so_tien) . '</td>';
                    echo '<td>' . esc_html($row->noi_dung_ngan_hang) . '</td>';
                    echo '<td>' . esc_html($row->ten_ngan_hang) . '</td>';
                    echo '<td>' . esc_html($row->ten_doi_ung) . '</td>';
                    if ($is_admin_page):
                        echo '<td>' . esc_html($row->date_created) . '</td>';
                        echo '<td>' . esc_html($row->date_updated) . '</td>';
                        echo '<td><a href="?page=ttadatatable_edit&id=' . esc_attr($row->id) . '">Sửa</a></td>';
                    endif;
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="9">Không có dữ liệu</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script>
        jQuery(document).ready(function($) {
            var table = $('#data-table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "pageLength": 10,
                "language": {
                    "lengthMenu": "Hiển thị _MENU_ dòng mỗi trang",
                    "zeroRecords": "Không tìm thấy dữ liệu",
                    "info": "Đang hiển thị trang _PAGE_ của _PAGES_",
                    "infoEmpty": "Không có dữ liệu",
                    "infoFiltered": "(lọc từ _MAX_ dòng)",
                    "search": "Tìm kiếm:",
                    "paginate": {
                        "first": "Đầu",
                        "last": "Cuối",
                        "next": "Tiếp",
                        "previous": "Trước"
                    }
                },
                "columnDefs": [{
                    "targets": 2,
                    "render": function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            return parseInt(data).toLocaleString('vi-VN');
                        }
                        return data;
                    }
                }],
                stateSave: true
            });

            $('#filter_so_tien').on('change', function() {
                var filterValue = $(this).val();
                table.draw();
            });

            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var soTien = parseInt(data[2].replace(/\./g, '').trim()) || 0;
                    var filterValue = $('#filter_so_tien').val();

                    if (filterValue === "") {
                        return true;
                    }

                    switch (filterValue) {
                        case "0-500000":
                            return soTien >= 0 && soTien <= 500000;
                        case "500001-5000000":
                            return soTien >= 500001 && soTien <= 5000000;
                        case "5000001-10000000":
                            return soTien >= 5000001 && soTien <= 10000000;
                        case "10000001":
                            return soTien > 10000000;
                    }
                    return true;
                }
            );

            $('#filter_ten_ngan_hang').on('change', function() {
                var filterValue = $(this).val();
                table.column(4).search(filterValue).draw();
            });
        });
    </script>
</div>