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

    public function get_all_products()
    {
        return $this->db->like('level_product', level_user())->get('v_products')->result();
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
        a.sts_ongkir        
        FROM tmp_cart a
        WHERE a.idcustomer = '$id'
        AND a.create_at = '$tgl'
        limit 1
        ");
    }
    public function gettmpshop($id, $tgl)
    {
        return $this->db->query("SELECT 
        b.kota_id AS kota_id,
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
    public function deleteongkirs($id)
    {
        return $this->db->delete('tbtestongkir', array("idcustomer" => $id));
    }
    public function getongkirs($id, $tgl)
    {
        return $this->db->query("SELECT
        a.*
        FROM tbtestongkir a
        WHERE a.idcustomer = '$id'
        AND a.create_at = '$tgl'
        AND a.status = 0
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

    public function get_home_categories()
    {
        return $this->db->limit(7)->get('product_category')->result();
    }

    public function get_all_categories()
    {
        return $this->db->get('product_category')->result();
    }

    public function get_products_in_category($id)
    {
        return $this->db->where('category_id', $id)
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
        $data = $this->db->query("
            SELECT b.*, a.banner_image
            FROM banner_product a
            JOIN products b
                ON b.id = a.product_id
        ")->result();

        return $data;
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
}
