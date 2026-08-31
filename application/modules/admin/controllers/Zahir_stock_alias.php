<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Zahir_stock_alias extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        verify_session('admin');

        if (!in_array(admin_role(), array('admin', 'adminonline'), TRUE)) {
            show_error('Akses setting alias Zahir Digital hanya untuk admin.', 403, 'Akses Ditolak');
        }

        $this->load->model('zahir_stock_model', 'zahir_stock');
    }

    public function index()
    {
        $params['title'] = 'Setting Alias Nama Barang Zahir';
        $edit_id = (int) $this->input->get('edit_id');

        $data = array(
            'flash' => $this->session->flashdata('zahir_stock_alias_flash'),
            'aliases' => $this->zahir_stock->get_aliases(),
            'summary' => $this->zahir_stock->get_alias_summary(),
            'products' => $this->zahir_stock->get_products_for_compare(),
            'edit_alias' => $edit_id > 0 ? $this->zahir_stock->get_alias($edit_id) : NULL,
            'table_ready' => $this->db->table_exists('zahir_stock_product_aliases')
        );

        $this->load->view('header', $params);
        $this->load->view('zahir_stock_alias/index', $data);
        $this->load->view('footer');
    }

    public function store()
    {
        $payload = $this->build_alias_payload();
        if (!$payload['success']) {
            $this->set_flash('danger', $payload['message']);
            redirect('admin/zahir-stock-alias');
            return;
        }

        if ($this->zahir_stock->alias_normalized_exists($payload['data']['normalized_zahir_name'])) {
            $this->set_flash('warning', 'Alias nama Zahir tersebut sudah terdaftar.');
            redirect('admin/zahir-stock-alias');
            return;
        }

        $admin_id = get_current_user_id();
        $payload['data']['created_by'] = $admin_id ? (int) $admin_id : NULL;
        $payload['data']['approved_by'] = $admin_id ? (int) $admin_id : NULL;
        $payload['data']['created_at'] = date('Y-m-d H:i:s');

        $alias_id = $this->zahir_stock->create_alias($payload['data']);
        if (!$alias_id) {
            $this->set_flash('danger', 'Alias gagal disimpan.');
            redirect('admin/zahir-stock-alias');
            return;
        }

        $this->set_flash('success', 'Alias berhasil ditambahkan.');
        redirect('admin/zahir-stock-alias');
    }

    public function update($id)
    {
        $alias = $this->zahir_stock->get_alias($id);
        if (!$alias) {
            $this->set_flash('warning', 'Alias tidak ditemukan.');
            redirect('admin/zahir-stock-alias');
            return;
        }

        $payload = $this->build_alias_payload((int) $id);
        if (!$payload['success']) {
            $this->set_flash('danger', $payload['message']);
            redirect('admin/zahir-stock-alias?edit_id=' . (int) $id);
            return;
        }

        if ($this->zahir_stock->alias_normalized_exists($payload['data']['normalized_zahir_name'], (int) $id)) {
            $this->set_flash('warning', 'Alias nama Zahir tersebut sudah dipakai mapping lain.');
            redirect('admin/zahir-stock-alias?edit_id=' . (int) $id);
            return;
        }

        $payload['data']['updated_at'] = date('Y-m-d H:i:s');
        if (!$this->zahir_stock->update_alias($id, $payload['data'])) {
            $this->set_flash('danger', 'Alias gagal diperbarui.');
            redirect('admin/zahir-stock-alias?edit_id=' . (int) $id);
            return;
        }

        $this->set_flash('success', 'Alias berhasil diperbarui.');
        redirect('admin/zahir-stock-alias');
    }

    public function delete($id)
    {
        $alias = $this->zahir_stock->get_alias($id);
        if (!$alias) {
            $this->set_flash('warning', 'Alias tidak ditemukan.');
            redirect('admin/zahir-stock-alias');
            return;
        }

        if (!$this->zahir_stock->deactivate_alias($id)) {
            $this->set_flash('danger', 'Alias gagal dinonaktifkan.');
            redirect('admin/zahir-stock-alias');
            return;
        }

        $this->set_flash('success', 'Alias berhasil dinonaktifkan.');
        redirect('admin/zahir-stock-alias');
    }

    private function build_alias_payload($id = 0)
    {
        if (!$this->db->table_exists('zahir_stock_product_aliases')) {
            return array('success' => false, 'message' => 'Tabel alias belum tersedia. Jalankan migration alias terlebih dahulu.');
        }

        $zahir_name = $this->clean_item_name($this->input->post('zahir_name'));
        $product_id = (int) $this->input->post('product_id');
        $notes = trim((string) $this->input->post('notes'));
        $active = $this->input->post('active') === '1' ? 1 : 0;

        if ($zahir_name === '') {
            return array('success' => false, 'message' => 'Nama barang Zahir wajib diisi.');
        }

        if ($product_id <= 0) {
            return array('success' => false, 'message' => 'Produk Karisma Online wajib dipilih.');
        }

        $product = $this->zahir_stock->get_product($product_id);
        if (!$product) {
            return array('success' => false, 'message' => 'Produk Karisma Online tidak ditemukan.');
        }

        return array(
            'success' => true,
            'data' => array(
                'zahir_name' => $zahir_name,
                'normalized_zahir_name' => $this->normalize_compare_name($zahir_name),
                'product_id' => (int) $product->id,
                'product_name' => $product->name,
                'active' => $active,
                'notes' => $notes !== '' ? substr($notes, 0, 255) : NULL
            )
        );
    }

    private function set_flash($type, $message)
    {
        $this->session->set_flashdata('zahir_stock_alias_flash', array(
            'type' => $type,
            'message' => $message
        ));
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
}
