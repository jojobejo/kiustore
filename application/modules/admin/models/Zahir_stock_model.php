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
