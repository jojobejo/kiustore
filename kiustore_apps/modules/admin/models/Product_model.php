<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function count_all_products()
    {
        return $this->db->get('products')->num_rows();
    }

    public function get_all_products()
    {
        $products = $this->db->get('products')->result();

        return $products;
    }

    public function get_confirmed_price()
    {
      $products = $this->db->query("
                SELECT *
                FROM products
                WHERE price > 0
                OR price_2 > 0
                OR price_3 > 0
                ORDER BY id DESC
            ");

            return $products->result();
    }

    public function get_not_confirmed_price()
    {
      $products = $this->db->query("
                SELECT *
                FROM products
                WHERE price < 1
                AND price_2 < 1
                AND price_3 < 1
                ORDER BY id DESC
            ");

            return $products->result();
    }

    public function get_list_products()
    {
        $products = $this->db->get('products')->result();

        return $products;
    }

    public function search_products($query, $limit, $start)
    {
        $products = $this->db->like('name', $query)->or_like('description', $query)->get('products', $limit, $start)->result();

        return $products;
    }

    public function count_search($query)
    {
        $count = $this->db->like('name', $query)->or_like('description', $query)->get('products')->num_rows();

        return $count;
    }

    public function add_new_product(Array $product)
    {
        $this->db->insert('products', $product);

        return $this->db->insert_id();
    }

    public function is_product_exist($id)
    {
        return ($this->db->where('id', $id)->get('products')->num_rows() > 0) ? TRUE : FALSE;
    }

    public function product_data($id)
    {
        $data = $this->db->query("
            SELECT p.*
            , (p.stock / p.product_unit_value) AS result
            , (p.stock - (p.product_unit_value * (p.stock / p.product_unit_value))) AS result2
            , pc.name as category_name
            FROM products p
            JOIN product_category pc
                ON pc.id = p.category_id
            WHERE p.id = '$id'
        ")->row();

        return $data;
    }

    public function delete_product_image($id)
    {
        return $this->db->where('id', $id)->update('products', array('picture_name' => NULL));
    }

    public function is_product_have_image($id)
    {
        $data = $this->product_data($id);
        $file = $data ? $data->picture_name : null;

        if (function_exists('resolve_product_image_name')) {
            return resolve_product_image_name($file) !== null ? TRUE : FALSE;
        }

        return $file && file_exists('./assets/uploads/products/'. $file) ? TRUE : FALSE;
    }

    public function edit_product($id, $product)
    {
        return $this->db->where('id', $id)->update('products', $product);
    }

    public function delete_product($id)
    {
        return $this->db->where('id', $id)->delete('products');
    }

    public function get_all_categories()
    {
        return $this->db->order_by('name', 'ASC')->get('product_category')->result();
    }

    public function category_data($id)
    {
        return $this->db->where('id', $id)->get('product_category')->row();
    }

    public function add_category($name)
    {
        $this->db->insert('product_category', array('name' => $name));

        return $this->db->insert_id();
    }

    public function delete_category($id)
    {
        return $this->db->where('id', $id)->delete('product_category');
    }

    public function edit_category($id, $name)
    {
        return $this->db->where('id', $id)->update('product_category', array('name' => $name));
    }

    public function get_all_coupons()
    {
        return $this->db->order_by('expired_date', 'DESC')->get('coupons')->result();
    }

    public function add_coupon(Array $data)
    {
        $this->db->insert('coupons', $data);

        return $this->db->insert_id();
    }

    public function coupon_data($id)
    {
        return $this->db->where('id', $id)->get('coupons')->row();
    }

    public function edit_coupon($id, $data)
    {
        return $this->db->where('id', $id)->update('coupons', $data);
    }

    public function delete_coupon($id)
    {
        return $this->db->where('id', $id)->delete('coupons');
    }

    public function delete_coupons(Array $ids)
    {
        if (empty($ids)) {
            return false;
        }

        return $this->db->where_in('id', $ids)->delete('coupons');
    }

    public function get_all_promo()
    {
        $data = $this->db->query("
            SELECT p.*, pd.name as product_name
            FROM promo p
            JOIN products pd
                ON pd.id = p.product_id
        ")->result();

        return $data;
    }

    public function add_promo(Array $data)
    {
        $this->db->insert('promo', $data);

        return $this->db->insert_id();
    }

    public function promo_data($id)
    {
        return $this->db->where('id', $id)->get('promo')->row();
    }

    public function edit_promo($id, $data)
    {
        return $this->db->where('id', $id)->update('promo', $data);
    }

    public function delete_promo($id)
    {
        return $this->db->where('id', $id)->delete('promo');
    }

    public function delete_promos(Array $ids)
    {
        return $this->db->where_in('id', $ids)->delete('promo');
    }

    public function latest()
    {
        return $this->db->where('is_available', 1)->order_by('add_date', 'DESC')->limit(5)->get('products')->result();
    }

    public function latest_categories()
    {
        return $this->db->order_by('id', 'DESC')->limit(5)->get('product_category')->result();
    }

    public function get_all_banner()
    {
        $select = "
            b.*,
            a.id AS banner_id,
            a.product_id,
            a.banner_image,
            a.created_at";

        $select .= $this->db->field_exists('banner_title', 'banner_product') ? ", a.banner_title" : ", NULL AS banner_title";
        $select .= $this->db->field_exists('redirect_type', 'banner_product') ? ", a.redirect_type" : ", 'product' AS redirect_type";
        $select .= $this->db->field_exists('redirect_product_id', 'banner_product') ? ", a.redirect_product_id" : ", a.product_id AS redirect_product_id";
        $select .= $this->db->field_exists('redirect_category_id', 'banner_product') ? ", a.redirect_category_id" : ", NULL AS redirect_category_id";
        $select .= $this->db->field_exists('redirect_url', 'banner_product') ? ", a.redirect_url" : ", NULL AS redirect_url";
        $select .= $this->db->field_exists('display_order', 'banner_product') ? ", a.display_order" : ", a.id AS display_order";
        $select .= $this->db->field_exists('is_active', 'banner_product') ? ", a.is_active" : ", 1 AS is_active";
        $select .= $this->db->field_exists('redirect_category_id', 'banner_product') ? ", c.name AS redirect_category_name" : ", NULL AS redirect_category_name";

        $this->db->select($select, FALSE)
            ->from('banner_product a')
            ->join('products b', 'b.id = a.product_id', 'left');

        if ($this->db->field_exists('redirect_category_id', 'banner_product'))
        {
            $this->db->join('product_category c', 'c.id = a.redirect_category_id', 'left');
        }

        if ($this->db->field_exists('display_order', 'banner_product'))
        {
            $this->db->order_by('a.display_order', 'ASC');
        }

        $data = $this->db->order_by('a.id', 'DESC')->get()->result();

        return $data;
    }

    public function is_banner_product_flexible_ready()
    {
        $columns = array(
            'banner_title',
            'redirect_type',
            'redirect_product_id',
            'redirect_category_id',
            'redirect_url',
            'display_order',
            'is_active'
        );

        foreach ($columns as $column)
        {
            if ( ! $this->db->field_exists($column, 'banner_product'))
            {
                return FALSE;
            }
        }

        return TRUE;
    }

    public function banner_data($id)
    {
        $select = "
            a.id AS banner_id,
            a.product_id,
            a.banner_image,
            a.created_at,
            b.name AS product_name,
            b.sku";

        $select .= $this->db->field_exists('banner_title', 'banner_product') ? ", a.banner_title" : ", NULL AS banner_title";
        $select .= $this->db->field_exists('redirect_type', 'banner_product') ? ", a.redirect_type" : ", 'product' AS redirect_type";
        $select .= $this->db->field_exists('redirect_product_id', 'banner_product') ? ", a.redirect_product_id" : ", a.product_id AS redirect_product_id";
        $select .= $this->db->field_exists('redirect_category_id', 'banner_product') ? ", a.redirect_category_id" : ", NULL AS redirect_category_id";
        $select .= $this->db->field_exists('redirect_url', 'banner_product') ? ", a.redirect_url" : ", NULL AS redirect_url";
        $select .= $this->db->field_exists('display_order', 'banner_product') ? ", a.display_order" : ", a.id AS display_order";
        $select .= $this->db->field_exists('is_active', 'banner_product') ? ", a.is_active" : ", 1 AS is_active";

        return $this->db->select($select, FALSE)
            ->from('banner_product a')
            ->join('products b', 'b.id = a.product_id', 'left')
            ->where('a.id', $id)
            ->get()
            ->row();
    }

    public function add_new_banner_product(Array $product)
    {
        $this->db->insert('banner_product', $product);

        return $this->db->insert_id();
    }

    public function edit_banner_product($id, Array $banner)
    {
        return $this->db->where('id', $id)->update('banner_product', $banner);
    }

    public function update_banner_display_settings(Array $settings)
    {
        if (empty($settings))
        {
            return FALSE;
        }

        $this->db->trans_start();

        foreach ($settings as $id => $setting)
        {
            $this->db->where('id', $id)->update('banner_product', $setting);
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function count_active_banner_products($exclude_id = NULL)
    {
        if ( ! $this->db->field_exists('is_active', 'banner_product'))
        {
            return 0;
        }

        $this->db->where('is_active', 1);

        if ($exclude_id !== NULL)
        {
            $this->db->where('id !=', $exclude_id);
        }

        return $this->db->count_all_results('banner_product');
    }

    public function get_next_banner_display_order()
    {
        if ( ! $this->db->field_exists('display_order', 'banner_product'))
        {
            return 1;
        }

        $row = $this->db->select_max('display_order')->get('banner_product')->row();

        return ($row && (int) $row->display_order > 0) ? ((int) $row->display_order + 1) : 1;
    }

    public function delete_banner_product($id)
    {
        return $this->db->where('id', $id)->delete('banner_product');
    }

    public function is_category_exist($id)
    {
        return ($this->db->where('id', $id)->get('product_category')->num_rows() > 0) ? TRUE : FALSE;
    }

}
