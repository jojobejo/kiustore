<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pricelist_import_model extends CI_Model
{
    public function get_products_for_compare()
    {
        return $this->db
            ->select('id, name, price, price_2, price_3')
            ->order_by('name', 'ASC')
            ->get('products')
            ->result();
    }

    public function get_latest_batch()
    {
        return $this->db
            ->order_by('imported_at', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get('pricelist_import_batches')
            ->row();
    }

    public function get_recent_batches($limit = 10)
    {
        return $this->db
            ->order_by('imported_at', 'DESC')
            ->order_by('id', 'DESC')
            ->limit((int) $limit)
            ->get('pricelist_import_batches')
            ->result();
    }

    public function get_batch($batch_id)
    {
        return $this->db
            ->where('id', (int) $batch_id)
            ->get('pricelist_import_batches')
            ->row();
    }

    public function create_batch($data)
    {
        $this->db->insert('pricelist_import_batches', $data);

        return $this->db->insert_id();
    }

    public function update_batch($batch_id, $data)
    {
        return $this->db
            ->where('id', (int) $batch_id)
            ->update('pricelist_import_batches', $data);
    }

    public function insert_items($items)
    {
        if (empty($items)) {
            return FALSE;
        }

        $columns = array(
            'batch_id',
            'row_number',
            'kode_barang',
            'deskripsi_raw',
            'deskripsi_bersih',
            'supplier',
            'new_price',
            'new_price_2',
            'new_price_3',
            'tgl_info',
            'keterangan_asal_info',
            'raw_payload',
            'source_rows',
            'product_id',
            'product_name',
            'current_price',
            'current_price_2',
            'current_price_3',
            'match_status',
            'change_status',
            'validation_message',
            'update_status',
            'updated_by',
            'updated_at'
        );

        $normalized = array();
        foreach ($items as $item) {
            if (!is_array($item) || empty($item)) {
                continue;
            }

            $row = array();
            foreach ($columns as $column) {
                $row[$column] = array_key_exists($column, $item) ? $item[$column] : NULL;
            }
            $normalized[] = $row;
        }

        if (empty($normalized)) {
            return FALSE;
        }

        return $this->db->insert_batch('pricelist_import_items', $normalized);
    }

    public function get_items($batch_id, $status = NULL)
    {
        if ($status !== NULL) {
            $this->db->where('match_status', $status);
        }

        return $this->db
            ->where('batch_id', (int) $batch_id)
            ->order_by('deskripsi_bersih', 'ASC')
            ->get('pricelist_import_items')
            ->result();
    }

    public function get_changed_items($batch_id)
    {
        return $this->db
            ->where('batch_id', (int) $batch_id)
            ->where('match_status', 'MATCHED')
            ->where('change_status', 'PRICE_CHANGED')
            ->where('update_status', 'PENDING')
            ->order_by('deskripsi_bersih', 'ASC')
            ->get('pricelist_import_items')
            ->result();
    }

    public function get_items_by_ids($batch_id, $item_ids)
    {
        if (empty($item_ids)) {
            return array();
        }

        return $this->db
            ->where('batch_id', (int) $batch_id)
            ->where_in('id', array_map('intval', $item_ids))
            ->order_by('deskripsi_bersih', 'ASC')
            ->get('pricelist_import_items')
            ->result();
    }

    public function get_update_summary($batch_id)
    {
        $rows = $this->db
            ->select('update_status, COUNT(*) AS total')
            ->where('batch_id', (int) $batch_id)
            ->group_by('update_status')
            ->get('pricelist_import_items')
            ->result();

        $summary = array(
            'PENDING' => 0,
            'UPDATED' => 0,
            'SKIPPED' => 0
        );

        foreach ($rows as $row) {
            $summary[$row->update_status] = (int) $row->total;
        }

        return $summary;
    }

    public function update_product_prices($product_id, $price, $price_2, $price_3)
    {
        return $this->db
            ->where('id', (int) $product_id)
            ->update('products', array(
                'price' => (int) $price,
                'price_2' => (int) $price_2,
                'price_3' => (int) $price_3
            ));
    }

    public function get_product($product_id)
    {
        return $this->db
            ->select('id, name, price, price_2, price_3')
            ->where('id', (int) $product_id)
            ->get('products')
            ->row();
    }

    public function mark_item_updated($item_id, $old_prices, $admin_id)
    {
        return $this->db
            ->where('id', (int) $item_id)
            ->update('pricelist_import_items', array(
                'current_price' => (int) $old_prices['price'],
                'current_price_2' => (int) $old_prices['price_2'],
                'current_price_3' => (int) $old_prices['price_3'],
                'update_status' => 'UPDATED',
                'updated_by' => $admin_id ? (int) $admin_id : NULL,
                'updated_at' => date('Y-m-d H:i:s')
            ));
    }

    public function mark_item_skipped($item_id, $message, $admin_id)
    {
        return $this->db
            ->where('id', (int) $item_id)
            ->update('pricelist_import_items', array(
                'update_status' => 'SKIPPED',
                'updated_by' => $admin_id ? (int) $admin_id : NULL,
                'updated_at' => date('Y-m-d H:i:s'),
                'validation_message' => $message
            ));
    }
}
