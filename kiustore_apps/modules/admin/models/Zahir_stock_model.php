<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zahir_stock_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_products_for_compare()
    {
        return $this->db
            ->select('id, name, stock')
            ->order_by('name', 'ASC')
            ->get('products')
            ->result();
    }

    public function get_latest_import_batch()
    {
        return $this->db
            ->order_by('imported_at', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get('zahir_stock_import_batches')
            ->row();
    }

    public function get_import_batch($batch_id)
    {
        return $this->db
            ->where('id', (int) $batch_id)
            ->get('zahir_stock_import_batches')
            ->row();
    }

    public function get_import_items($batch_id)
    {
        return $this->db
            ->where('batch_id', (int) $batch_id)
            ->order_by('nama_barang', 'ASC')
            ->get('zahir_stock_import_items')
            ->result();
    }

    public function get_import_update_summary($batch_id)
    {
        $rows = $this->db
            ->select('update_status, COUNT(*) AS total')
            ->where('batch_id', (int) $batch_id)
            ->group_by('update_status')
            ->get('zahir_stock_import_items')
            ->result();

        $summary = array(
            'PENDING' => 0,
            'UPDATED' => 0,
            'INSERTED' => 0
        );

        foreach ($rows as $row) {
            $summary[$row->update_status] = (int) $row->total;
        }

        return $summary;
    }

    public function create_import_batch($data)
    {
        $this->db->insert('zahir_stock_import_batches', $data);

        return $this->db->insert_id();
    }

    public function insert_import_items($items)
    {
        if (empty($items)) {
            return false;
        }

        return $this->db->insert_batch('zahir_stock_import_items', $items);
    }

    public function update_import_batch($batch_id, $data)
    {
        return $this->db
            ->where('id', (int) $batch_id)
            ->update('zahir_stock_import_batches', $data);
    }

    public function mark_import_item_updated($batch_id, $product_id, $status, $updated_product_id = NULL)
    {
        return $this->db
            ->where('batch_id', (int) $batch_id)
            ->where('product_id', (int) $product_id)
            ->update('zahir_stock_import_items', array(
                'update_status' => $status,
                'updated_product_id' => $updated_product_id,
                'updated_at' => date('Y-m-d H:i:s')
            ));
    }

    public function mark_import_item_inserted_by_name($batch_id, $name, $product_id)
    {
        return $this->db->query(
            "UPDATE zahir_stock_import_items
             SET update_status = 'INSERTED', updated_product_id = ?, updated_at = ?
             WHERE batch_id = ? AND LOWER(TRIM(nama_barang)) = ?",
            array((int) $product_id, date('Y-m-d H:i:s'), (int) $batch_id, strtolower(trim($name)))
        );
    }

    public function update_product_stock($product_id, $stock)
    {
        return $this->db
            ->where('id', (int) $product_id)
            ->update('products', array('stock' => (int) $stock));
    }

    public function add_product_from_zahir($name, $stock)
    {
        $now = date('Y-m-d H:i:s');
        $sku = 'ZD' . date('ymdHis') . mt_rand(10, 99);

        $product = array(
            'category_id' => NULL,
            'sku' => substr($sku, 0, 32),
            'name' => $name,
            'description' => 'Import otomatis dari Zahir Digital pada ' . $now,
            'picture_name' => NULL,
            'price' => 0,
            'price_2' => 0,
            'price_3' => 0,
            'stock' => (int) $stock,
            'current_discount' => 0,
            'product_unit' => 'PCS',
            'product_unit_1' => 'PCS',
            'product_unit_2' => '',
            'product_unit_value' => '1',
            'product_type' => 'GENERAL',
            'product_unit_weight' => 0,
            'is_available' => 1,
            'add_date' => $now,
            'user_level' => 0
        );

        $this->db->insert('products', $product);

        return $this->db->insert_id();
    }

    public function product_name_exists($name)
    {
        $row = $this->db
            ->query('SELECT COUNT(*) AS total FROM products WHERE LOWER(TRIM(name)) = ?', array(strtolower(trim($name))))
            ->row();

        return $row && (int) $row->total > 0;
    }
}
