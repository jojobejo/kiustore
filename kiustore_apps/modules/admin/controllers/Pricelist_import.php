<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pricelist_import extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        verify_session('admin');

        if (!in_array(admin_role(), array('admin', 'adminonline', 'keuangan'), TRUE)) {
            show_error('Akses import pricelist hanya untuk admin dan keuangan.', 403, 'Akses Ditolak');
        }

        $this->load->model('pricelist_import_model', 'pricelist_import');
    }

    public function index()
    {
        $params['title'] = 'Import Pricelist Harga';
        $batch_id = (int) $this->input->get('batch_id');
        $batch = $batch_id > 0 ? $this->pricelist_import->get_batch($batch_id) : $this->pricelist_import->get_latest_batch();

        $data = $this->empty_payload();
        if ($batch) {
            $data = $this->build_payload_from_batch($batch);
        }

        $data['flash'] = $this->session->flashdata('pricelist_import_flash');
        $data['latest_batch'] = $this->pricelist_import->get_latest_batch();
        $data['recent_batches'] = $this->pricelist_import->get_recent_batches(10);

        $this->load->view('header', $params);
        $this->load->view('pricelist_import/index', $data);
        $this->load->view('footer');
    }

    public function import()
    {
        if (empty($_FILES['pricelist_file']['name'])) {
            $this->set_flash('warning', 'Pilih file Excel pricelist terlebih dahulu.');
            redirect('admin/pricelist-import');
            return;
        }

        $original_name = $_FILES['pricelist_file']['name'];
        $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $allowed = array('xlsx', 'xls', 'csv', 'txt', 'tsv');
        if (!in_array($extension, $allowed, TRUE)) {
            $this->set_flash('danger', 'Format file tidak didukung. Gunakan .xlsx, .xls, .csv, .txt, atau .tsv.');
            redirect('admin/pricelist-import');
            return;
        }

        $upload_dir = FCPATH . 'assets/uploads/pricelist_import/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, TRUE);
        }

        $stored_name = 'pricelist_' . date('Ymd_His') . '_' . mt_rand(1000, 9999) . '.' . $extension;
        $stored_path = $upload_dir . $stored_name;

        if (!move_uploaded_file($_FILES['pricelist_file']['tmp_name'], $stored_path)) {
            $this->set_flash('danger', 'Upload file pricelist gagal.');
            redirect('admin/pricelist-import');
            return;
        }

        $raw_rows = $this->parse_uploaded_file($stored_path, $extension);
        if (empty($raw_rows)) {
            $this->set_flash('danger', 'File berhasil diupload, tetapi kolom Deskripsi dan harga belum terbaca.');
            redirect('admin/pricelist-import');
            return;
        }

        $processed = $this->process_pricelist_rows($raw_rows);
        $compare = $this->compare_processed_rows($processed, $this->pricelist_import->get_products_for_compare());
        $now = date('Y-m-d H:i:s');
        $admin_id = get_current_user_id();

        $this->db->trans_start();
        $batch_id = $this->pricelist_import->create_batch(array(
            'source_file_name' => $original_name,
            'stored_file_name' => $stored_name,
            'raw_rows' => count($raw_rows),
            'processed_rows' => count($processed['valid']),
            'matched_rows' => count($compare['matched']),
            'pricelist_only_rows' => count($compare['pricelist_only']),
            'product_only_rows' => count($compare['product_only']),
            'changed_rows' => count($compare['changed']),
            'invalid_rows' => count($processed['invalid']) + count($compare['invalid']),
            'duplicate_rows' => $processed['duplicate_rows'],
            'conflict_rows' => $processed['conflict_rows'] + count($compare['invalid']),
            'status' => 'IMPORTED',
            'imported_by' => $admin_id ? (int) $admin_id : NULL,
            'imported_at' => $now
        ));

        $items = array();
        foreach ($compare['matched'] as $row) {
            $items[] = $this->item_payload($batch_id, $row, 'MATCHED', $row['change_status'], 'PENDING');
        }

        foreach ($compare['pricelist_only'] as $row) {
            $items[] = $this->item_payload($batch_id, $row, 'PRICELIST_ONLY', 'PRICELIST_ONLY', 'PENDING');
        }

        foreach ($compare['product_only'] as $row) {
            $items[] = $this->product_only_payload($batch_id, $row);
        }

        foreach (array_merge($processed['invalid'], $compare['invalid']) as $row) {
            $items[] = $this->item_payload($batch_id, $row, 'INVALID', $row['change_status'], 'SKIPPED');
        }

        $this->pricelist_import->insert_items($items);
        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            $this->set_flash('danger', 'Import gagal saat menyimpan staging pricelist.');
            redirect('admin/pricelist-import');
            return;
        }

        $this->set_flash('success', 'Import pricelist berhasil. Preview batch #' . $batch_id . ' siap direview sebelum approve harga.');
        redirect('admin/pricelist-import?batch_id=' . $batch_id);
    }

    public function approve()
    {
        $batch_id = (int) $this->input->post('batch_id');
        $approve_all = $this->input->post('approve_all') === '1';
        $item_ids = $this->input->post('item_ids');
        $item_ids = is_array($item_ids) ? array_unique(array_filter(array_map('intval', $item_ids))) : array();

        $batch = $this->pricelist_import->get_batch($batch_id);
        if (!$batch) {
            $this->set_flash('danger', 'Batch import pricelist tidak ditemukan.');
            redirect('admin/pricelist-import');
            return;
        }

        if ($approve_all) {
            $items = $this->pricelist_import->get_changed_items($batch_id);
        } else {
            if (empty($item_ids)) {
                $this->set_flash('warning', 'Pilih minimal satu item harga berubah untuk approve.');
                redirect('admin/pricelist-import?batch_id=' . $batch_id);
                return;
            }
            $items = $this->pricelist_import->get_items_by_ids($batch_id, $item_ids);
        }

        $admin_id = get_current_user_id();
        $updated = 0;
        $skipped = 0;

        $this->db->trans_start();
        foreach ($items as $item) {
            if ($item->match_status !== 'MATCHED' || $item->change_status !== 'PRICE_CHANGED' || $item->update_status !== 'PENDING') {
                $skipped++;
                continue;
            }

            $product = $this->pricelist_import->get_product($item->product_id);
            if (!$product) {
                $this->pricelist_import->mark_item_skipped($item->id, 'Produk tidak ditemukan saat approve.', $admin_id);
                $skipped++;
                continue;
            }

            $this->pricelist_import->update_product_prices($product->id, $item->new_price, $item->new_price_2, $item->new_price_3);
            $this->pricelist_import->mark_item_updated($item->id, array(
                'price' => $product->price,
                'price_2' => $product->price_2,
                'price_3' => $product->price_3
            ), $admin_id);
            $updated++;
        }

        if ($updated > 0) {
            $this->pricelist_import->update_batch($batch_id, array(
                'status' => 'APPROVED',
                'approved_by' => $admin_id ? (int) $admin_id : NULL,
                'approved_at' => date('Y-m-d H:i:s')
            ));
        }
        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            $this->set_flash('danger', 'Approve gagal. Transaction database tidak berhasil.');
        } else {
            $this->set_flash('success', 'Approve selesai. ' . $updated . ' produk diupdate, ' . $skipped . ' item dilewati.');
        }

        redirect('admin/pricelist-import?batch_id=' . $batch_id);
    }

    public function export_pricelist_only_excel()
    {
        $batch = $this->get_export_batch();
        if (!$batch) {
            show_error('Batch import pricelist tidak ditemukan.', 404, 'Export Gagal');
            return;
        }

        $items = $this->pricelist_import->get_items($batch->id, 'PRICELIST_ONLY');
        $headers = array('Kode Barang', 'Deskripsi Bersih', 'Harga', 'Harga R1', 'Harga R2', 'Supplier', 'Tgl Info', 'Keterangan Asal Info Perubahan Harga');
        $rows = array();

        foreach ($items as $item) {
            $rows[] = array(
                $item->kode_barang,
                $item->deskripsi_bersih,
                $item->new_price,
                $item->new_price_2,
                $item->new_price_3,
                $item->supplier,
                $item->tgl_info,
                $item->keterangan_asal_info
            );
        }

        $filename = 'barang_pricelist_tidak_ada_karisma_online_batch_' . (int) $batch->id . '_' . date('Ymd_His') . '.xls';
        $this->export_excel_table($filename, $headers, $rows);
    }

    public function export_product_only_excel()
    {
        $batch = $this->get_export_batch();
        if (!$batch) {
            show_error('Batch import pricelist tidak ditemukan.', 404, 'Export Gagal');
            return;
        }

        $items = $this->pricelist_import->get_items($batch->id, 'PRODUCT_ONLY');
        $headers = array('Product ID', 'Produk Karisma Online', 'Harga', 'Harga R1', 'Harga R2');
        $rows = array();

        foreach ($items as $item) {
            $rows[] = array(
                $item->product_id,
                $item->product_name,
                $item->current_price,
                $item->current_price_2,
                $item->current_price_3
            );
        }

        $filename = 'barang_karisma_online_tidak_ada_pricelist_batch_' . (int) $batch->id . '_' . date('Ymd_His') . '.xls';
        $this->export_excel_table($filename, $headers, $rows);
    }

    private function build_payload_from_batch($batch)
    {
        $items = $this->pricelist_import->get_items($batch->id);
        $data = $this->empty_payload();
        $data['batch'] = $batch;
        $data['update_summary'] = $this->pricelist_import->get_update_summary($batch->id);

        foreach ($items as $item) {
            if ($item->match_status === 'MATCHED' && $item->change_status === 'PRICE_CHANGED') {
                $data['changed'][] = $item;
            } elseif ($item->match_status === 'PRICELIST_ONLY') {
                $data['pricelist_only'][] = $item;
            } elseif ($item->match_status === 'PRODUCT_ONLY') {
                $data['product_only'][] = $item;
            } elseif ($item->match_status === 'INVALID') {
                $data['invalid'][] = $item;
            } elseif ($item->match_status === 'MATCHED') {
                $data['unchanged'][] = $item;
            }
        }

        $data['summary'] = array(
            'processed_rows' => (int) $batch->processed_rows,
            'matched_rows' => (int) $batch->matched_rows,
            'pricelist_only_rows' => (int) $batch->pricelist_only_rows,
            'product_only_rows' => (int) $batch->product_only_rows,
            'changed_rows' => (int) $batch->changed_rows,
            'invalid_rows' => (int) $batch->invalid_rows,
            'duplicate_rows' => (int) $batch->duplicate_rows,
            'conflict_rows' => (int) $batch->conflict_rows
        );

        return $data;
    }

    private function get_export_batch()
    {
        $batch_id = (int) $this->input->get('batch_id');
        return $batch_id > 0 ? $this->pricelist_import->get_batch($batch_id) : $this->pricelist_import->get_latest_batch();
    }

    private function export_excel_table($filename, $headers, $rows)
    {
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        echo "\xEF\xBB\xBF";
        echo '<html><head><meta charset="UTF-8"></head><body>';
        echo '<table border="1">';
        echo '<thead><tr>';
        foreach ($headers as $header) {
            echo '<th>' . html_escape($header) . '</th>';
        }
        echo '</tr></thead><tbody>';
        foreach ($rows as $row) {
            echo '<tr>';
            foreach ($row as $value) {
                echo '<td>' . html_escape($value) . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
        echo '</body></html>';
    }

    private function empty_payload()
    {
        return array(
            'batch' => NULL,
            'changed' => array(),
            'pricelist_only' => array(),
            'product_only' => array(),
            'invalid' => array(),
            'unchanged' => array(),
            'summary' => array(
                'processed_rows' => 0,
                'matched_rows' => 0,
                'pricelist_only_rows' => 0,
                'product_only_rows' => 0,
                'changed_rows' => 0,
                'invalid_rows' => 0,
                'duplicate_rows' => 0,
                'conflict_rows' => 0
            ),
            'update_summary' => array('PENDING' => 0, 'UPDATED' => 0, 'SKIPPED' => 0)
        );
    }

    private function compare_processed_rows($processed, $products)
    {
        $product_map = array();
        $duplicate_product_keys = array();
        foreach ($products as $product) {
            $key = $this->normalize_compare_name($product->name);
            if ($key === '') {
                continue;
            }
            if (isset($product_map[$key])) {
                $duplicate_product_keys[$key] = TRUE;
                continue;
            }
            $product_map[$key] = $product;
        }

        $matched = array();
        $changed = array();
        $pricelist_only = array();
        $invalid = array();
        $pricelist_map = array();

        foreach ($processed['valid'] as $row) {
            $key = $this->normalize_compare_name($row['deskripsi_bersih']);
            $pricelist_map[$key] = $row;

            if (isset($duplicate_product_keys[$key])) {
                $row['product_id'] = NULL;
                $row['product_name'] = NULL;
                $row['current_price'] = NULL;
                $row['current_price_2'] = NULL;
                $row['current_price_3'] = NULL;
                $row['change_status'] = 'CONFLICT';
                $row['validation_message'] = 'Konflik: ada lebih dari satu products.name dengan deskripsi bersih yang sama.';
                $invalid[] = $row;
                continue;
            }

            if (!isset($product_map[$key])) {
                $row['product_id'] = NULL;
                $row['product_name'] = NULL;
                $row['current_price'] = NULL;
                $row['current_price_2'] = NULL;
                $row['current_price_3'] = NULL;
                $row['change_status'] = 'PRICELIST_ONLY';
                $pricelist_only[] = $row;
                continue;
            }

            $product = $product_map[$key];
            $row['product_id'] = (int) $product->id;
            $row['product_name'] = $product->name;
            $row['current_price'] = (int) $product->price;
            $row['current_price_2'] = (int) $product->price_2;
            $row['current_price_3'] = (int) $product->price_3;
            $row['change_status'] = ((int) $row['new_price'] !== (int) $product->price
                || (int) $row['new_price_2'] !== (int) $product->price_2
                || (int) $row['new_price_3'] !== (int) $product->price_3)
                ? 'PRICE_CHANGED'
                : 'UNCHANGED';

            $matched[] = $row;
            if ($row['change_status'] === 'PRICE_CHANGED') {
                $changed[] = $row;
            }
        }

        $product_only = array();
        foreach ($product_map as $key => $product) {
            if (!isset($pricelist_map[$key])) {
                $product_only[] = array(
                    'product_id' => (int) $product->id,
                    'product_name' => $product->name,
                    'deskripsi_bersih' => $product->name,
                    'current_price' => (int) $product->price,
                    'current_price_2' => (int) $product->price_2,
                    'current_price_3' => (int) $product->price_3
                );
            }
        }

        return array(
            'matched' => $matched,
            'changed' => $changed,
            'pricelist_only' => $pricelist_only,
            'product_only' => $product_only,
            'invalid' => $invalid
        );
    }

    private function process_pricelist_rows($rows)
    {
        $groups = array();
        $invalid = array();
        $duplicate_rows = 0;
        $conflict_rows = 0;

        foreach ($rows as $row) {
            $clean = $this->clean_item_name(isset($row['deskripsi']) ? $row['deskripsi'] : '');
            $new_price_2 = $this->normalize_price(isset($row['price_2']) ? $row['price_2'] : NULL);
            $new_price = $this->normalize_price(isset($row['price']) ? $row['price'] : NULL);
            $new_price_3 = $this->normalize_price(isset($row['price_3']) ? $row['price_3'] : NULL);

            if ($new_price_2 === NULL) {
                $new_price_2 = $this->pick_lowest_qty_price(isset($row['qty_prices']) ? $row['qty_prices'] : array());
            }

            if ($clean === '') {
                $invalid[] = $this->invalid_row($row, 'INVALID', 'Deskripsi kosong setelah dibersihkan.');
                continue;
            }

            if ($new_price_2 === NULL || $new_price_2 < 1) {
                $invalid[] = $this->invalid_row($row, 'INVALID', 'Harga dasar/R1 tidak valid atau kosong.');
                continue;
            }

            if ($new_price === NULL) {
                $new_price = (int) round($new_price_2 * 1.05);
            }

            if ($new_price_3 === NULL) {
                $new_price_3 = (int) round($new_price_2 * 1.02);
            }

            $key = $this->normalize_compare_name($clean);
            $payload = array(
                'row_number' => isset($row['row_number']) ? (int) $row['row_number'] : NULL,
                'kode_barang' => isset($row['kode_barang']) ? trim((string) $row['kode_barang']) : '',
                'deskripsi_raw' => isset($row['deskripsi']) ? (string) $row['deskripsi'] : '',
                'deskripsi_bersih' => $clean,
                'supplier' => isset($row['supplier']) ? trim((string) $row['supplier']) : '',
                'new_price' => (int) $new_price,
                'new_price_2' => (int) $new_price_2,
                'new_price_3' => (int) $new_price_3,
                'tgl_info' => isset($row['tgl_info']) ? $this->normalize_date_text($row['tgl_info']) : '',
                'keterangan_asal_info' => isset($row['keterangan_asal_info']) ? trim((string) $row['keterangan_asal_info']) : '',
                'raw_payload' => json_encode($row),
                'source_rows' => 1,
                'validation_message' => ''
            );

            if (!isset($groups[$key])) {
                $groups[$key] = array(
                    'rows' => array(),
                    'price_keys' => array()
                );
            }

            $price_key = $payload['new_price'] . '|' . $payload['new_price_2'] . '|' . $payload['new_price_3'];
            $groups[$key]['rows'][] = $payload;
            $groups[$key]['price_keys'][$price_key] = TRUE;
        }

        $valid = array();
        foreach ($groups as $group) {
            if (count($group['rows']) > 1) {
                $duplicate_rows += count($group['rows']);
            }

            if (count($group['price_keys']) > 1) {
                $row = $group['rows'][0];
                $row['source_rows'] = count($group['rows']);
                $row['change_status'] = 'CONFLICT';
                $row['validation_message'] = 'Konflik harga: deskripsi bersih sama tetapi nilai harga berbeda dalam file.';
                $invalid[] = $row;
                $conflict_rows += count($group['rows']);
                continue;
            }

            $row = $group['rows'][0];
            $row['source_rows'] = count($group['rows']);
            if (count($group['rows']) > 1) {
                $row['validation_message'] = 'Duplikat terdeteksi dan digroup karena harga sama.';
            }
            $valid[] = $row;
        }

        usort($valid, array($this, 'sort_by_deskripsi'));

        return array(
            'valid' => $valid,
            'invalid' => $invalid,
            'duplicate_rows' => $duplicate_rows,
            'conflict_rows' => $conflict_rows
        );
    }

    private function item_payload($batch_id, $row, $match_status, $change_status, $update_status)
    {
        return array(
            'batch_id' => (int) $batch_id,
            'row_number' => isset($row['row_number']) ? $row['row_number'] : NULL,
            'kode_barang' => isset($row['kode_barang']) ? $row['kode_barang'] : NULL,
            'deskripsi_raw' => isset($row['deskripsi_raw']) ? $row['deskripsi_raw'] : NULL,
            'deskripsi_bersih' => isset($row['deskripsi_bersih']) ? $row['deskripsi_bersih'] : '',
            'supplier' => isset($row['supplier']) ? $row['supplier'] : NULL,
            'new_price' => isset($row['new_price']) ? (int) $row['new_price'] : NULL,
            'new_price_2' => isset($row['new_price_2']) ? (int) $row['new_price_2'] : NULL,
            'new_price_3' => isset($row['new_price_3']) ? (int) $row['new_price_3'] : NULL,
            'tgl_info' => isset($row['tgl_info']) ? $row['tgl_info'] : NULL,
            'keterangan_asal_info' => isset($row['keterangan_asal_info']) ? $row['keterangan_asal_info'] : NULL,
            'raw_payload' => isset($row['raw_payload']) ? $row['raw_payload'] : NULL,
            'source_rows' => isset($row['source_rows']) ? (int) $row['source_rows'] : 1,
            'product_id' => isset($row['product_id']) ? $row['product_id'] : NULL,
            'product_name' => isset($row['product_name']) ? $row['product_name'] : NULL,
            'current_price' => isset($row['current_price']) ? $row['current_price'] : NULL,
            'current_price_2' => isset($row['current_price_2']) ? $row['current_price_2'] : NULL,
            'current_price_3' => isset($row['current_price_3']) ? $row['current_price_3'] : NULL,
            'match_status' => $match_status,
            'change_status' => $change_status,
            'validation_message' => isset($row['validation_message']) ? $row['validation_message'] : NULL,
            'update_status' => $update_status
        );
    }

    private function product_only_payload($batch_id, $row)
    {
        $payload = array(
            'deskripsi_bersih' => $row['deskripsi_bersih'],
            'product_id' => (int) $row['product_id'],
            'product_name' => $row['product_name'],
            'current_price' => (int) $row['current_price'],
            'current_price_2' => (int) $row['current_price_2'],
            'current_price_3' => (int) $row['current_price_3'],
            'validation_message' => 'Produk ada di Karisma Online tetapi tidak ada pada pricelist import.'
        );

        return $this->item_payload($batch_id, $payload, 'PRODUCT_ONLY', 'PRODUCT_ONLY', 'PENDING');
    }

    private function invalid_row($row, $status, $message)
    {
        return array(
            'row_number' => isset($row['row_number']) ? (int) $row['row_number'] : NULL,
            'kode_barang' => isset($row['kode_barang']) ? trim((string) $row['kode_barang']) : '',
            'deskripsi_raw' => isset($row['deskripsi']) ? (string) $row['deskripsi'] : '',
            'deskripsi_bersih' => $this->clean_item_name(isset($row['deskripsi']) ? $row['deskripsi'] : ''),
            'supplier' => isset($row['supplier']) ? trim((string) $row['supplier']) : '',
            'new_price' => NULL,
            'new_price_2' => NULL,
            'new_price_3' => NULL,
            'tgl_info' => isset($row['tgl_info']) ? $this->normalize_date_text($row['tgl_info']) : '',
            'keterangan_asal_info' => isset($row['keterangan_asal_info']) ? trim((string) $row['keterangan_asal_info']) : '',
            'raw_payload' => json_encode($row),
            'source_rows' => 1,
            'change_status' => $status,
            'validation_message' => $message
        );
    }

    private function parse_uploaded_file($path, $extension)
    {
        if ($extension === 'xlsx') {
            return $this->parse_xlsx_rows($path);
        }

        $content = file_get_contents($path);
        if (stripos($content, '<table') !== FALSE) {
            $rows = $this->parse_html_table_rows($content);
            if (!empty($rows)) {
                return $rows;
            }
        }

        return $this->parse_delimited_rows($content);
    }

    private function parse_xlsx_rows($path)
    {
        if (!class_exists('ZipArchive')) {
            return array();
        }

        $zip = new ZipArchive();
        if ($zip->open($path) !== TRUE) {
            return array();
        }

        $shared_strings = $this->read_xlsx_shared_strings($zip);
        $sheet_xml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheet_xml === FALSE) {
            return array();
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(TRUE);
        $loaded = $dom->loadXML($sheet_xml);
        libxml_clear_errors();
        if (!$loaded) {
            return array();
        }

        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('m', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
        $rows = array();
        foreach ($xpath->query('//m:sheetData/m:row') as $row_node) {
            $row_number = (int) $row_node->getAttribute('r');
            $rows[$row_number] = array();
            foreach ($xpath->query('m:c', $row_node) as $cell_node) {
                $ref = $cell_node->getAttribute('r');
                $column = $this->cell_column_index($ref);
                $rows[$row_number][$column] = $this->xlsx_cell_value($cell_node, $xpath, $shared_strings);
            }
        }

        $this->apply_xlsx_merged_headers($rows, $xpath);

        return $this->map_matrix_rows_to_pricelist($rows);
    }

    private function read_xlsx_shared_strings($zip)
    {
        $xml = $zip->getFromName('xl/sharedStrings.xml');
        if ($xml === FALSE) {
            return array();
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(TRUE);
        $loaded = $dom->loadXML($xml);
        libxml_clear_errors();
        if (!$loaded) {
            return array();
        }

        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('m', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
        $strings = array();
        foreach ($xpath->query('//m:si') as $si) {
            $text = '';
            foreach ($xpath->query('.//m:t', $si) as $t) {
                $text .= $t->nodeValue;
            }
            $strings[] = $text;
        }

        return $strings;
    }

    private function xlsx_cell_value($cell_node, $xpath, $shared_strings)
    {
        $type = $cell_node->getAttribute('t');
        if ($type === 's') {
            $value_node = $xpath->query('m:v', $cell_node)->item(0);
            $index = $value_node ? (int) $value_node->nodeValue : -1;
            return isset($shared_strings[$index]) ? $shared_strings[$index] : '';
        }

        if ($type === 'inlineStr') {
            $text = '';
            foreach ($xpath->query('.//m:t', $cell_node) as $t) {
                $text .= $t->nodeValue;
            }
            return $text;
        }

        $value_node = $xpath->query('m:v', $cell_node)->item(0);
        return $value_node ? $value_node->nodeValue : '';
    }

    private function apply_xlsx_merged_headers(&$rows, $xpath)
    {
        foreach ($xpath->query('//m:mergeCells/m:mergeCell') as $merge_node) {
            $ref = $merge_node->getAttribute('ref');
            if (strpos($ref, ':') === FALSE) {
                continue;
            }

            list($start, $end) = explode(':', $ref, 2);
            $start_row = $this->cell_row_number($start);
            $end_row = $this->cell_row_number($end);
            if ($start_row !== $end_row || $start_row > 5) {
                continue;
            }

            $start_col = $this->cell_column_index($start);
            $end_col = $this->cell_column_index($end);
            $value = isset($rows[$start_row][$start_col]) ? $rows[$start_row][$start_col] : '';
            if ($value === '') {
                continue;
            }

            for ($col = $start_col; $col <= $end_col; $col++) {
                if (!isset($rows[$start_row][$col]) || $rows[$start_row][$col] === '') {
                    $rows[$start_row][$col] = $value;
                }
            }
        }
    }

    private function parse_html_table_rows($html)
    {
        if (!class_exists('DOMDocument')) {
            return array();
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(TRUE);
        $loaded = $dom->loadHTML($html);
        libxml_clear_errors();
        if (!$loaded) {
            return array();
        }

        $matrix = array();
        $row_number = 1;
        foreach ($dom->getElementsByTagName('tr') as $tr) {
            $matrix[$row_number] = array();
            $column = 1;
            foreach ($tr->childNodes as $cell) {
                if ($cell->nodeName === 'td' || $cell->nodeName === 'th') {
                    $matrix[$row_number][$column] = trim(preg_replace('/\s+/', ' ', $cell->textContent));
                    $column++;
                }
            }
            $row_number++;
        }

        return $this->map_matrix_rows_to_pricelist($matrix);
    }

    private function parse_delimited_rows($text)
    {
        $lines = preg_split('/\r\n|\r|\n/', trim((string) $text));
        $matrix = array();
        $row_number = 1;
        foreach ($lines as $line) {
            if (trim($line) === '') {
                $row_number++;
                continue;
            }
            $delimiter = (strpos($line, ';') !== FALSE) ? ';' : ((strpos($line, "\t") !== FALSE) ? "\t" : ',');
            $cells = str_getcsv($line, $delimiter);
            $column = 1;
            foreach ($cells as $cell) {
                $matrix[$row_number][$column] = $cell;
                $column++;
            }
            $row_number++;
        }

        return $this->map_matrix_rows_to_pricelist($matrix);
    }

    private function map_matrix_rows_to_pricelist($matrix)
    {
        $header_row = $this->find_header_row($matrix);
        if ($header_row === NULL) {
            return array();
        }

        $second_header_row = $this->has_second_header_row(isset($matrix[$header_row + 1]) ? $matrix[$header_row + 1] : array()) ? $header_row + 1 : NULL;
        $header_map = $this->build_header_map(
            isset($matrix[$header_row]) ? $matrix[$header_row] : array(),
            $second_header_row !== NULL && isset($matrix[$second_header_row]) ? $matrix[$second_header_row] : array()
        );

        $columns = $this->resolve_columns($header_map);
        if ($columns['deskripsi'] === NULL) {
            return array();
        }

        $data_start = $second_header_row !== NULL ? $second_header_row + 1 : $header_row + 1;
        $rows = array();
        foreach ($matrix as $row_number => $cells) {
            if ($row_number < $data_start) {
                continue;
            }

            $non_empty = array_filter($cells, function ($value) {
                return trim((string) $value) !== '';
            });
            if (empty($non_empty)) {
                continue;
            }

            $qty_prices = array();
            foreach ($columns['qty_prices'] as $column) {
                $qty_prices[] = isset($cells[$column]) ? $cells[$column] : NULL;
            }

            $rows[] = array(
                'row_number' => (int) $row_number,
                'kode_barang' => $this->cell($cells, $columns['kode_barang']),
                'deskripsi' => $this->cell($cells, $columns['deskripsi']),
                'supplier' => $this->cell($cells, $columns['supplier']),
                'price' => $this->cell($cells, $columns['price']),
                'price_2' => $this->cell($cells, $columns['price_2']),
                'price_3' => $this->cell($cells, $columns['price_3']),
                'qty_prices' => $qty_prices,
                'tgl_info' => $this->cell($cells, $columns['tgl_info']),
                'keterangan_asal_info' => $this->cell($cells, $columns['keterangan_asal_info'])
            );
        }

        return $rows;
    }

    private function resolve_columns($header_map)
    {
        $columns = array(
            'kode_barang' => NULL,
            'deskripsi' => NULL,
            'supplier' => NULL,
            'price' => NULL,
            'price_2' => NULL,
            'price_3' => NULL,
            'tgl_info' => NULL,
            'keterangan_asal_info' => NULL,
            'qty_prices' => array()
        );

        foreach ($header_map as $column => $labels) {
            $combined = $labels['combined'];
            $primary = $labels['primary'];
            $secondary = $labels['secondary'];

            if ($columns['kode_barang'] === NULL && $this->label_has_any($combined, array('kodebarang', 'kodeproduk', 'sku'))) {
                $columns['kode_barang'] = $column;
            }
            if ($columns['deskripsi'] === NULL && $this->label_has_any($combined, array('deskripsi', 'namabarang', 'namaproduk', 'produk'))) {
                $columns['deskripsi'] = $column;
            }
            if ($columns['supplier'] === NULL && $this->label_has_any($combined, array('supplier', 'suplier', 'pemasok'))) {
                $columns['supplier'] = $column;
            }
            if ($columns['tgl_info'] === NULL && $this->label_has_any($combined, array('tglinfo', 'tanggalinfo', 'tglperubahan', 'tanggalperubahan'))) {
                $columns['tgl_info'] = $column;
            }
            if ($columns['keterangan_asal_info'] === NULL && $this->label_has_any($combined, array('keteranganasalinfo', 'asalinfoperubahanharga', 'keteranganasal'))) {
                $columns['keterangan_asal_info'] = $column;
            }
            if ($columns['price_2'] === NULL && $this->label_has_any($combined, array('hargar1', 'r1', 'hargaterendah', 'partai'))) {
                $columns['price_2'] = $column;
            }
            if ($columns['price_3'] === NULL && $this->label_has_any($combined, array('hargar2', 'r2', 'hargaecer', 'kios'))) {
                $columns['price_3'] = $column;
            }
            if ($columns['price'] === NULL && $this->is_explicit_price_column($combined)) {
                $columns['price'] = $column;
            }
            if ($this->label_has_any($primary, array('hargaqty')) || $this->label_has_any($secondary, array('box', 'dst'))) {
                $columns['qty_prices'][] = $column;
            }
        }

        return $columns;
    }

    private function is_explicit_price_column($label)
    {
        if (!$this->label_has_any($label, array('hargaumum', 'harga1', 'price'))) {
            return FALSE;
        }

        return !$this->label_has_any($label, array('program', 'promo', 'qty', 'terendah', 'partai', 'r1', 'r2', 'ecer', 'kios'));
    }

    private function find_header_row($matrix)
    {
        foreach ($matrix as $row_number => $cells) {
            if ($row_number > 10) {
                break;
            }

            $joined = $this->normalize_label(implode(' ', $cells));
            if ($this->label_has_any($joined, array('deskripsi', 'namabarang', 'namaproduk')) && $this->label_has_any($joined, array('harga', 'tglinfo', 'kodebarang'))) {
                return (int) $row_number;
            }
        }

        return NULL;
    }

    private function has_second_header_row($cells)
    {
        $hits = 0;
        foreach ($cells as $cell) {
            $label = $this->normalize_label($cell);
            if ($this->label_has_any($label, array('box', 'dst', 'qty'))) {
                $hits++;
            }
        }

        return $hits >= 2;
    }

    private function build_header_map($primary_row, $secondary_row)
    {
        $columns = array_unique(array_merge(array_keys($primary_row), array_keys($secondary_row)));
        $map = array();
        foreach ($columns as $column) {
            $primary = isset($primary_row[$column]) ? $this->normalize_label($primary_row[$column]) : '';
            $secondary = isset($secondary_row[$column]) ? $this->normalize_label($secondary_row[$column]) : '';
            $map[$column] = array(
                'primary' => $primary,
                'secondary' => $secondary,
                'combined' => trim($primary . $secondary)
            );
        }

        return $map;
    }

    private function label_has_any($label, $needles)
    {
        foreach ($needles as $needle) {
            if ($needle !== '' && strpos($label, $needle) !== FALSE) {
                return TRUE;
            }
        }

        return FALSE;
    }

    private function cell($cells, $column)
    {
        return ($column !== NULL && isset($cells[$column])) ? $cells[$column] : NULL;
    }

    private function pick_lowest_qty_price($values)
    {
        $prices = array();
        foreach ($values as $value) {
            $price = $this->normalize_price($value);
            if ($price !== NULL && $price > 0) {
                $prices[] = $price;
            }
        }

        return empty($prices) ? NULL : min($prices);
    }

    private function normalize_price($price)
    {
        if ($price === NULL) {
            return NULL;
        }

        $text = trim((string) $price);
        if ($text === '') {
            return NULL;
        }

        if (!preg_match('/\d[\d\.,]*/', $text, $match)) {
            return NULL;
        }

        $number = $match[0];
        if (strpos($number, ',') !== FALSE && strpos($number, '.') !== FALSE) {
            $number = str_replace('.', '', $number);
            $number = str_replace(',', '.', $number);
        } else if (strpos($number, ',') !== FALSE) {
            $parts = explode(',', $number);
            $last = end($parts);
            $number = strlen($last) === 3 ? str_replace(',', '', $number) : str_replace(',', '.', $number);
        } else if (substr_count($number, '.') > 1) {
            $number = str_replace('.', '', $number);
        } else if (strpos($number, '.') !== FALSE) {
            $parts = explode('.', $number);
            $last = end($parts);
            if (strlen($last) === 3) {
                $number = str_replace('.', '', $number);
            }
        }

        return (int) round((float) $number);
    }

    private function normalize_date_text($value)
    {
        $text = trim((string) $value);
        if ($text === '') {
            return '';
        }

        if (is_numeric($text) && (float) $text > 25000) {
            $timestamp = ((int) $text - 25569) * 86400;
            return gmdate('Y-m-d', $timestamp);
        }

        return $text;
    }

    private function clean_item_name($name)
    {
        $name = html_entity_decode(strip_tags((string) $name), ENT_QUOTES, 'UTF-8');
        $name = str_replace('*', '', $name);
        $name = preg_replace('/\s+/', ' ', $name);

        return trim($name);
    }

    private function normalize_compare_name($name)
    {
        return strtolower($this->clean_item_name($name));
    }

    private function normalize_label($label)
    {
        $label = strtolower(html_entity_decode(strip_tags((string) $label), ENT_QUOTES, 'UTF-8'));
        return preg_replace('/[^a-z0-9]+/', '', $label);
    }

    private function cell_column_index($ref)
    {
        $letters = preg_replace('/[^A-Z]/', '', strtoupper($ref));
        $index = 0;
        for ($i = 0; $i < strlen($letters); $i++) {
            $index = ($index * 26) + (ord($letters[$i]) - 64);
        }

        return $index;
    }

    private function cell_row_number($ref)
    {
        return (int) preg_replace('/[^0-9]/', '', $ref);
    }

    public function sort_by_deskripsi($a, $b)
    {
        return strcasecmp($a['deskripsi_bersih'], $b['deskripsi_bersih']);
    }

    private function set_flash($type, $message)
    {
        $this->session->set_flashdata('pricelist_import_flash', array(
            'type' => $type,
            'message' => $message
        ));
    }
}
