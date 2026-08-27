<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Zahir_stock extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        verify_session('admin');

        if (!in_array(admin_role(), array('admin', 'adminonline'), TRUE)) {
            show_error('Akses integrasi stock Zahir Digital hanya untuk admin.', 403, 'Akses Ditolak');
        }

        $this->load->model('zahir_stock_model', 'zahir_stock');
        $this->load->config('zahirdigital', TRUE);
    }

    public function index()
    {
        $params['title'] = 'Integrasi Stock Zahir Digital';
        $data = $this->build_stock_payload();
        $data['flash'] = $this->session->flashdata('zahir_stock_flash');

        $this->load->view('header', $params);
        $this->load->view('zahir_stock/index', $data);
        $this->load->view('footer');
    }

    public function data()
    {
        $this->json_response($this->build_stock_payload());
    }

    public function approve()
    {
        $approve_all = $this->input->post('approve_all') === '1';
        $product_ids = $this->input->post('product_ids');
        $product_ids = is_array($product_ids) ? $product_ids : array();
        $product_ids = array_unique(array_filter(array_map('intval', $product_ids)));

        if (!$approve_all && empty($product_ids)) {
            $this->session->set_flashdata('zahir_stock_flash', array(
                'type' => 'warning',
                'message' => 'Pilih minimal satu produk match untuk approve update stock.'
            ));
            redirect('admin/zahir-stock');
            return;
        }

        $payload = $this->build_stock_payload();
        if (!$payload['success']) {
            $this->session->set_flashdata('zahir_stock_flash', array(
                'type' => 'danger',
                'message' => 'Approve dibatalkan karena data Zahir Digital belum berhasil diambil: ' . $payload['error_message']
            ));
            redirect('admin/zahir-stock');
            return;
        }

        $latest_by_product = array();
        foreach ($payload['matched'] as $row) {
            $latest_by_product[(int) $row['product_id']] = $row;
        }

        if ($approve_all) {
            $product_ids = array_keys($latest_by_product);
        }

        $updated = 0;
        $skipped = 0;

        $this->db->trans_start();
        foreach ($product_ids as $product_id) {
            if (!isset($latest_by_product[$product_id])) {
                $skipped++;
                continue;
            }

            $row = $latest_by_product[$product_id];
            $this->zahir_stock->update_product_stock($product_id, $row['zahir_qty']);
            $updated++;
        }
        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            $this->session->set_flashdata('zahir_stock_flash', array(
                'type' => 'danger',
                'message' => 'Approve gagal. Transaction database tidak berhasil.'
            ));
        } else {
            $this->session->set_flashdata('zahir_stock_flash', array(
                'type' => 'success',
                'message' => 'Approve selesai. ' . $updated . ' produk diupdate dari Zahir Digital. ' . $skipped . ' produk dilewati karena tidak match pada data terbaru.'
            ));
        }

        redirect('admin/zahir-stock');
    }

    public function insert_product()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $name = $this->clean_item_name($this->input->post('nama_barang'));
        if ($name === '') {
            $this->json_response(array(
                'success' => false,
                'message' => 'Nama barang tidak valid.'
            ));
            return;
        }

        $payload = $this->build_stock_payload();
        if (!$payload['success']) {
            $this->json_response(array(
                'success' => false,
                'message' => 'Data Zahir Digital belum siap: ' . $payload['error_message']
            ));
            return;
        }

        $zahir_only_map = array();
        foreach ($payload['zahir_only'] as $row) {
            $zahir_only_map[$this->normalize_compare_name($row['nama_barang'])] = $row;
        }

        $key = $this->normalize_compare_name($name);
        if (!isset($zahir_only_map[$key])) {
            $this->json_response(array(
                'success' => false,
                'message' => 'Barang tidak lagi berada pada daftar Zahir yang belum ada di Karisma Online.'
            ));
            return;
        }

        if ($this->zahir_stock->product_name_exists($name)) {
            $this->json_response(array(
                'success' => false,
                'message' => 'Produk dengan nama tersebut sudah ada di Karisma Online.'
            ));
            return;
        }

        $row = $zahir_only_map[$key];

        $this->db->trans_start();
        $product_id = $this->zahir_stock->add_product_from_zahir($row['nama_barang'], $row['qty']);
        $this->db->trans_complete();

        if (!$this->db->trans_status() || !$product_id) {
            $this->json_response(array(
                'success' => false,
                'message' => 'Insert produk gagal.'
            ));
            return;
        }

        $this->json_response(array(
            'success' => true,
            'message' => 'Produk berhasil diinsert ke tabel products.',
            'product_id' => (int) $product_id,
            'nama_barang' => $row['nama_barang'],
            'qty' => (int) $row['qty']
        ));
    }

    public function export_stock_excel()
    {
        $payload = $this->build_stock_payload();
        if (!$payload['success']) {
            show_error('Data Zahir Digital belum siap: ' . $payload['error_message'], 500, 'Export Gagal');
            return;
        }

        $products = $this->zahir_stock->get_products_for_compare();
        $filename = 'export_stock_zahir_products_' . date('Ymd_His') . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        echo "\xEF\xBB\xBF";
        echo '<html><head><meta charset="UTF-8"></head><body>';
        echo '<h3>Data Olah Stock Zahir Digital</h3>';
        echo '<table border="1">';
        echo '<thead><tr><th>Nama Barang</th><th>Qty</th></tr></thead><tbody>';
        foreach ($payload['processed'] as $row) {
            echo '<tr>';
            echo '<td>' . html_escape($row['nama_barang']) . '</td>';
            echo '<td>' . (int) $row['qty'] . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';

        echo '<br><h3>Data Stock Products Karisma Online</h3>';
        echo '<table border="1">';
        echo '<thead><tr><th>Nama Barang</th><th>Qty</th></tr></thead><tbody>';
        foreach ($products as $product) {
            echo '<tr>';
            echo '<td>' . html_escape($product->name) . '</td>';
            echo '<td>' . (int) $product->stock . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        echo '</body></html>';
    }

    private function build_stock_payload()
    {
        $source = $this->fetch_zahir_source();
        $products = $this->zahir_stock->get_products_for_compare();
        $processed = array();
        $matched = array();
        $zahir_only = array();
        $product_only = array();
        $product_map = array();
        $zahir_map = array();

        foreach ($products as $product) {
            $key = $this->normalize_compare_name($product->name);
            if ($key === '') {
                continue;
            }

            $product_map[$key] = $product;
        }

        if ($source['success']) {
            $processed = $this->process_zahir_rows($source['rows']);

            foreach ($processed as $row) {
                $key = $this->normalize_compare_name($row['nama_barang']);
                $zahir_map[$key] = $row;

                if (isset($product_map[$key])) {
                    $product = $product_map[$key];
                    $matched[] = array(
                        'product_id' => (int) $product->id,
                        'nama_barang' => $row['nama_barang'],
                        'zahir_qty' => (int) $row['qty'],
                        'product_name' => $product->name,
                        'product_stock' => (int) $product->stock,
                        'selisih' => (int) $row['qty'] - (int) $product->stock
                    );
                } else {
                    $zahir_only[] = array(
                        'nama_barang' => $row['nama_barang'],
                        'qty' => (int) $row['qty']
                    );
                }
            }

            foreach ($product_map as $key => $product) {
                if (!isset($zahir_map[$key])) {
                    $product_only[] = array(
                        'product_id' => (int) $product->id,
                        'nama_barang' => $product->name,
                        'stock' => (int) $product->stock
                    );
                }
            }
        }

        return array(
            'success' => $source['success'],
            'source_url' => $source['url'],
            'http_code' => $source['http_code'],
            'error_message' => $source['error_message'],
            'raw_count' => $source['raw_count'],
            'processed' => array_values($processed),
            'matched' => array_values($matched),
            'zahir_only' => array_values($zahir_only),
            'product_only' => array_values($product_only),
            'summary' => array(
                'processed_rows' => count($processed),
                'matched_rows' => count($matched),
                'zahir_only_rows' => count($zahir_only),
                'product_only_rows' => count($product_only)
            )
        );
    }

    private function fetch_zahir_source()
    {
        $url = $this->config->item('zahir_stockready_url', 'zahirdigital');
        $token = $this->config->item('zahir_stockready_token', 'zahirdigital');
        $username = $this->config->item('zahir_stockready_username', 'zahirdigital');
        $password = $this->config->item('zahir_stockready_password', 'zahirdigital');
        $timeout = (int) $this->config->item('zahir_stockready_timeout', 'zahirdigital');
        $timeout = $timeout > 0 ? $timeout : 30;

        $response = '';
        $http_code = 0;
        $error_message = '';

        if (function_exists('curl_init')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            $headers = array('Accept: application/json, text/html, text/csv, */*');
            if ($token !== '') {
                $headers[] = 'X-Karisma-Stock-Token: ' . $token;
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            if ($username !== '' || $password !== '') {
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($curl, CURLOPT_USERPWD, $username . ':' . $password);
            }

            $response = curl_exec($curl);
            $http_code = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($curl);
            curl_close($curl);

            if ($response === FALSE) {
                $error_message = $curl_error;
                $response = '';
            }
        } else {
            $context_options = array(
                'http' => array(
                    'timeout' => $timeout,
                    'header' => "Accept: application/json, text/html, text/csv, */*\r\n"
                ),
                'ssl' => array(
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE
                )
            );

            if ($username !== '' || $password !== '') {
                $context_options['http']['header'] .= 'Authorization: Basic ' . base64_encode($username . ':' . $password) . "\r\n";
            }

            if ($token !== '') {
                $context_options['http']['header'] .= 'X-Karisma-Stock-Token: ' . $token . "\r\n";
            }

            $response = @file_get_contents($url, FALSE, stream_context_create($context_options));
            if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $match)) {
                $http_code = (int) $match[1];
            }

            if ($response === FALSE) {
                $error_message = 'Gagal mengambil data sumber Zahir Digital.';
                $response = '';
            }
        }

        if ($error_message === '' && $http_code >= 400) {
            $error_message = 'HTTP ' . $http_code . ' dari sumber Zahir Digital.';
        }

        $rows = array();
        if ($error_message === '') {
            $rows = $this->parse_source_rows($response);
            if (empty($rows)) {
                $error_message = 'Response Zahir Digital berhasil diambil, tetapi kolom nama barang dan qty belum terbaca.';
            }
        }

        return array(
            'success' => ($error_message === ''),
            'url' => $url,
            'http_code' => $http_code,
            'error_message' => $error_message,
            'rows' => $rows,
            'raw_count' => count($rows)
        );
    }

    private function parse_source_rows($response)
    {
        $response = trim((string) $response);
        if ($response === '') {
            return array();
        }

        $json = json_decode($response, TRUE);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $this->parse_json_rows($json);
        }

        if (stripos($response, '<table') !== FALSE) {
            $html_rows = $this->parse_html_table_rows($response);
            if (!empty($html_rows)) {
                return $html_rows;
            }
        }

        return $this->parse_delimited_rows($response);
    }

    private function parse_json_rows($json)
    {
        if (isset($json['data']) && is_array($json['data'])) {
            $json = $json['data'];
        }

        if (!is_array($json)) {
            return array();
        }

        $rows = array();
        foreach ($json as $item) {
            if (!is_array($item)) {
                continue;
            }

            $name = $this->pick_value($item, array('nama_barang', 'Nama Barang', 'NAMA BARANG', 'name', 'nama', 'barang'));
            $qty = $this->pick_value($item, array('qty', 'Qty', 'QTY', 'quantity', 'stock', 'stok'));

            if ($name !== NULL && $qty !== NULL) {
                $rows[] = array('nama_barang' => $name, 'qty' => $qty);
            }
        }

        return $rows;
    }

    private function parse_html_table_rows($html)
    {
        $rows = array();
        if (!class_exists('DOMDocument')) {
            return $rows;
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(TRUE);
        $loaded = $dom->loadHTML($html);
        libxml_clear_errors();

        if (!$loaded) {
            return $rows;
        }

        $tables = $dom->getElementsByTagName('table');
        foreach ($tables as $table) {
            $header = array();
            $tr_list = $table->getElementsByTagName('tr');

            foreach ($tr_list as $tr) {
                $cells = array();
                foreach ($tr->childNodes as $cell) {
                    if ($cell->nodeName === 'td' || $cell->nodeName === 'th') {
                        $cells[] = trim(preg_replace('/\s+/', ' ', $cell->textContent));
                    }
                }

                if (empty($cells)) {
                    continue;
                }

                if (empty($header) && $this->row_looks_like_header($cells)) {
                    $header = $cells;
                    continue;
                }

                $mapped = $this->map_cells_to_stock_row($cells, $header);
                if ($mapped !== NULL) {
                    $rows[] = $mapped;
                }
            }
        }

        return $rows;
    }

    private function parse_delimited_rows($text)
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($text));
        $header = array();
        $rows = array();

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $delimiter = (strpos($line, ';') !== FALSE) ? ';' : ((strpos($line, "\t") !== FALSE) ? "\t" : ',');
            $cells = str_getcsv($line, $delimiter);

            if (empty($header) && $this->row_looks_like_header($cells)) {
                $header = $cells;
                continue;
            }

            $mapped = $this->map_cells_to_stock_row($cells, $header);
            if ($mapped !== NULL) {
                $rows[] = $mapped;
            }
        }

        return $rows;
    }

    private function map_cells_to_stock_row($cells, $header)
    {
        if (!empty($header)) {
            $row = array();
            foreach ($header as $index => $label) {
                $row[$label] = isset($cells[$index]) ? $cells[$index] : '';
            }

            $name = $this->pick_value($row, array('nama_barang', 'Nama Barang', 'NAMA BARANG', 'name', 'nama', 'barang'));
            $qty = $this->pick_value($row, array('qty', 'Qty', 'QTY', 'quantity', 'stock', 'stok'));
        } else {
            $name = isset($cells[0]) ? $cells[0] : NULL;
            $qty = isset($cells[1]) ? $cells[1] : NULL;
        }

        if ($name === NULL || $qty === NULL) {
            return NULL;
        }

        return array('nama_barang' => $name, 'qty' => $qty);
    }

    private function process_zahir_rows($rows)
    {
        $grouped = array();

        foreach ($rows as $row) {
            $name = isset($row['nama_barang']) ? (string) $row['nama_barang'] : '';

            $clean_name = $this->clean_item_name($name);
            if ($clean_name === '') {
                continue;
            }

            $key = $this->normalize_compare_name($clean_name);
            if (!isset($grouped[$key])) {
                $grouped[$key] = array(
                    'nama_barang' => $clean_name,
                    'qty' => 0
                );
            }

            $grouped[$key]['qty'] += $this->normalize_qty(isset($row['qty']) ? $row['qty'] : 0);
        }

        usort($grouped, array($this, 'sort_by_nama_barang'));

        return $grouped;
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
        $name = strtolower($this->clean_item_name($name));
        return $name;
    }

    private function normalize_qty($qty)
    {
        $qty = trim((string) $qty);
        $qty = str_replace(' ', '', $qty);

        if (strpos($qty, ',') !== FALSE && strpos($qty, '.') !== FALSE) {
            $qty = str_replace('.', '', $qty);
            $qty = str_replace(',', '.', $qty);
        } else {
            $qty = str_replace(',', '.', $qty);
        }

        return (float) $qty;
    }

    private function row_looks_like_header($cells)
    {
        $joined = strtolower(implode(' ', $cells));

        return (strpos($joined, 'nama') !== FALSE || strpos($joined, 'barang') !== FALSE)
            && (strpos($joined, 'qty') !== FALSE || strpos($joined, 'stock') !== FALSE || strpos($joined, 'stok') !== FALSE);
    }

    private function pick_value($row, $keys)
    {
        foreach ($keys as $key) {
            if (isset($row[$key])) {
                return $row[$key];
            }
        }

        $normalized = array();
        foreach ($row as $key => $value) {
            $normalized[strtolower(str_replace(array(' ', '_', '-'), '', $key))] = $value;
        }

        foreach ($keys as $key) {
            $lookup = strtolower(str_replace(array(' ', '_', '-'), '', $key));
            if (isset($normalized[$lookup])) {
                return $normalized[$lookup];
            }
        }

        foreach ($normalized as $key => $value) {
            foreach ($keys as $lookup) {
                $lookup = strtolower(str_replace(array(' ', '_', '-'), '', $lookup));
                if ($lookup !== '' && strpos($key, $lookup) !== FALSE) {
                    return $value;
                }
            }
        }

        return NULL;
    }

    public function sort_by_nama_barang($a, $b)
    {
        return strcasecmp($a['nama_barang'], $b['nama_barang']);
    }

    private function json_response($response)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
