<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{
    public $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->user_id = get_current_user_id();
    }

    public function count_all_products()
    {
        return $this->db->like('level_product', level_user())
            ->count_all_results('v_products');
    }

    public function get_all_products($limit = null, $offset = 0)
    {
        $this->db->like('level_product', level_user());

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get('v_products')->result();
    }

    public function search_products($like)
    {
        return $this->db->like('level_product', level_user())->like('name', $like)->get('v_products')->result();
    }

    public function count_search_products($like)
    {
        return $this->db->like('level_product', level_user())->like('name', $like)->get('v_products')->num_rows();
    }

    public function get_stock($id)
    {
        return $this->db->where('id', $id)->get('products')->row()->stock;
    }

    public function get_cart_product($id)
    {
        return $this->db
            ->select('id, product_type, product_unit_1, product_unit_value, product_unit_weight')
            ->where('id', $id)
            ->get('products')
            ->row();
    }

    public function count_tmp_cart($id, $now)
    {
        return $this->db->query("SELECT 
        COUNT(a.idcustomer) AS item_cart
        FROM tmp_cart a
        WHERE a.idcustomer = '$id'
        AND a.create_at = '$now'
        ");
    }

    public function get_tmp_cart($id, $now)
    {
        return $this->db->query("SELECT 
        a.*
        FROM tmp_cart a
        WHERE a.idcustomer = '$id'
        AND a.create_at = '$now'
        LIMIT 1
        ");
    }
    public function tmp_cart_customer($data)
    {
        $this->db->insert('tmp_cart', $data);
    }

    public function insertgenerate($data)
    {
        $this->db->insert('generate_kdchart', $data);
    }

    public function getstatusongkir($id, $tgl)
    {
        return $this->db->query("SELECT 
        a.sts_ongkir,
        a.kdchart,
        b.subdistrict_id AS sub
        FROM tmp_cart a
        JOIN customers b ON b.user_id = a.idcustomer
        WHERE a.idcustomer = '$id'
        AND a.create_at = '$tgl'
        limit 1
        ");
    }

    public function getstatusongkirss($id, $tgl)
    {
        return $this->db->query("SELECT 
        a.sts_ongkir,
        a.kdchart
        FROM tmp_cart a
        WHERE a.idcustomer = '$id'
        AND a.create_at = '$tgl'
        limit 1
        ");
    }

    public function gettmpshop($id, $tgl)
    {
        return $this->db->query("SELECT 
        b.province_id AS province_id,
        b.kota_id AS kota_id,
        b.subdistrict_id AS sub_id,
        SUM(a.qty)* a.product_weight AS total_weights
        FROM tmp_cart a
        JOIN customers b ON b.user_id = a.idcustomer
        WHERE a.idcustomer = '$id'
        AND a.create_at = '$tgl'
        GROUP BY a.idcustomer
        ");
    }

    public function updatests($id, $tgl, $data)
    {
        $this->db->where('idcustomer', $id);
        $this->db->where('create_at', $tgl);
        return $this->db->update('tmp_cart', $data);
    }

    public function stsongkir($id, $tgl, $data)
    {
        $this->db->where('idcustomer', $id);
        $this->db->where('create_at', $tgl);
        $this->db->where('status', '1');
        return $this->db->update('tbtestongkir', $data);
    }

    public function getongkirs($id, $tgl)
    {
        return $this->db->query("SELECT
        a.*
        FROM tbtestongkir a
        WHERE a.idcustomer = '$id'
        AND a.create_at = '$tgl'
        AND a.status = 1
        ");
    }

    public function getcustomer($id)
    {
        return $this->db->query("SELECT a.*
        FROM customers a 
        WHERE a.user_id = '$id'
        ");
    }

    public function addongkir($data)
    {
        return $this->db->insert('tbtestongkir', $data);
    }

    // public function get_products_for_home()
    // {
    //     return $this->db->get('products')->limit(10)->result();
    // }

    // public function get_home_categories()
    // {
    //     return $this->db->limit(15)->get('product_category')->result();
    // }

    public function get_home_categories()
    {
        return $this->db->get('product_category')->result();
    }

    public function get_all_categories()
    {
        return $this->db->get('product_category')->result();
    }

    public function count_products_in_category($id)
    {
        return $this->db->where('category_id', $id)
            ->count_all_results('products');
    }

    public function get_products_in_category($id, $limit = null, $offset = 0)
    {
        $this->db->where('category_id', $id);

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        return $this->db
            ->get('products')->result();
    }

    public function best_deal_product()
    {
        $data = $this->db->where('is_available', 1)
            ->order_by('current_discount', 'DESC')
            ->limit(1)
            ->get('products')
            ->row();

        return $data;
    }

    public function is_product_exist($id, $sku)
    {
        return ($this->db->where(array('id' => $id, 'sku' => $sku))->get('products')->num_rows() > 0) ? TRUE : FALSE;
    }

    public function product_data($id)
    {
        $data = $this->db->query("
            SELECT p.*, pc.name as category_name
            FROM v_products p
            JOIN product_category pc
                ON pc.id = p.category_id
            WHERE p.id = '$id'
        ")->row();

        return $data;
    }

    public function last_order()
    {
        $id = $this->user_id;
        $data = $this->db->query("
            SELECT c.*
            FROM order_items a
            JOIN orders b
                ON a.order_id = b.id
            JOIN products c
                ON a.product_id = c.id
            WHERE b.user_id = '$id'
            ORDER BY order_date
            LIMIT 10
        ")->result();

        return $data;
    }

    public function promo_products()
    {
        return $this->db->like('level_product', level_user())->where('promo', 1)
            ->get('v_products')->result();
    }

    public function best_products()
    {
        $data = $this->db->query("
            SELECT c.*, sum(a.order_qty)
            FROM order_items a
            JOIN orders b ON b.id=a.order_id
            JOIN v_products c ON a.product_id=c.id
            WHERE b.order_status in (5,6) AND c.level_product like '" . level_user() . "'
            GROUP BY a.product_id
            ORDER BY count(a.order_qty)
            LIMIT 10
        ")->result();

        return $data;
    }

    public function related_products($current, $category)
    {
        return $this->db->where(array('id !=' => $current, 'category_id' => $category))->like('level_product', level_user())->limit(4)->get('v_products')->result();
    }

    public function create_order(array $data)
    {
        $this->db->insert('orders', $data);

        return $this->db->insert_id();
    }

    public function add_credit_limit($total)
    {
        $id = $this->user_id;
        //  $this->db->set('credit', 'credit+'.$total, FALSE);
        $this->db->where('user_id', $id);
        $this->db->update('customers');
    }

    public function create_order_items($data)
    {
        return $this->db->insert_batch('order_items', $data);
    }

    public function get_all_banner()
    {
        $select = "
            a.id AS banner_id,
            a.product_id,
            a.banner_image,
            a.created_at,
            b.id,
            b.sku,
            b.name";

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

        $this->db
            ->where('a.banner_image IS NOT NULL', NULL, FALSE)
            ->where('a.banner_image !=', '')
            ->limit(3);

        if ($this->db->field_exists('is_active', 'banner_product'))
        {
            $this->db->where('a.is_active', 1);
        }

        if ($this->db->field_exists('display_order', 'banner_product'))
        {
            $this->db->order_by('a.display_order', 'ASC');
        }

        $data = $this->db
            ->order_by('a.id', 'DESC')
            ->get()
            ->result();

        foreach ($data as $banner)
        {
            $this->_hydrate_banner_redirect($banner);
        }

        return $data;
    }

    private function _hydrate_banner_redirect($banner)
    {
        $banner->banner_title = ! empty($banner->banner_title) ? $banner->banner_title : (! empty($banner->name) ? $banner->name : 'Promo Produk');
        $redirect_type = ! empty($banner->redirect_type) ? $banner->redirect_type : 'product';

        if ($redirect_type === 'category' && ! empty($banner->redirect_category_id))
        {
            $category_name = ! empty($banner->redirect_category_name) ? $banner->redirect_category_name : 'kategori';
            $banner->target_url = site_url('category/' . $banner->redirect_category_id . '/' . rawurlencode($category_name) . '/');
            return;
        }

        if ($redirect_type === 'custom' && ! empty($banner->redirect_url))
        {
            $banner->target_url = $this->_normalize_banner_custom_url($banner->redirect_url);
            return;
        }

        if ( ! empty($banner->id) && ! empty($banner->sku))
        {
            $banner->target_url = site_url('product/' . $banner->id . '/' . $banner->sku . '/');
            return;
        }

        $banner->target_url = site_url('category');
    }

    private function _normalize_banner_custom_url($url)
    {
        $url = trim($url);

        if (preg_match('/^https?:\/\//i', $url))
        {
            return $url;
        }

        return site_url(ltrim($url, '/'));
    }

    public function getweight($id)
    {
        return $this->db->query("
        ");
    }

    function kdnonkomersial($idcus)
    {
        $cd1 = $this->db->query("SELECT MAX(RIGHT(kdchart,4)) AS kd_max FROM generate_kdchart WHERE DATE(create_at)=CURDATE()");
        $kd1 = "";
        if ($cd1->num_rows() > 0) {
            foreach ($cd1->result() as $k) {
                $tmp = ((int)$k->kd_max) + 1;
                $kd1 = sprintf("%04s", $tmp);
            }
        } else {
            $kd1 = "0001";
        }
        date_default_timezone_set('Asia/Jakarta');
        $kdnk1 = 'KIU' . $idcus . date('dmy') . $kd1;
        return $kdnk1;
    }

    function removechart($kdchart, $idrow)
    {
        $this->db->where('kdchart', $kdchart);
        $this->db->where('idbarang', $idrow);
        return $this->db->delete('tmp_cart');
    }
    function removechartall($kdchart)
    {
        $this->db->where('kdchart', $kdchart);
        return $this->db->delete('tmp_cart');
    }

    public function getprice_ongkir($iduser, $kdchart)
    {
        // Ambil data dari database (misalnya dari tabel `shipping`)
        return $this->db->get_where('tbtestongkir', ['idcustomer' => $iduser, 'kd_faktur' => $kdchart, 'status' => '1'])->result();
    }

    public function getongkir_checkout($id, $now)
    {
        return $this->db->query("SELECT
        a.*
        FROM tbtestongkir a
        WHERE a.idcustomer = '$id'
        AND a.status = 1
        AND a.create_at = '$now'
        ")->result();
    }

    public function is_ongkir($id)
    {
        return $this->db->query("SELECT
        COUNT(a.id) as ongkir
        FROM tbtestongkir a
        WHERE a.idcustomer = '$id'
        ")->result();
    }


    public function getkdchart($id)
    {
        return $this->db->query("SELECT
        a.idcustomer , a.kdchart
        FROM tmp_cart a
        WHERE a.idcustomer = '$id'
        LIMIT 1
        ")->result();
    }
}
