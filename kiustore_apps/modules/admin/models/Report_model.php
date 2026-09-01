<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    private function has_internal_column()
    {
        return $this->db->field_exists('is_internal', 'users');
    }

    private function external_user_where($alias = 'u')
    {
        if (!$this->has_internal_column()) {
            return '1=1';
        }

        return "COALESCE({$alias}.is_internal, 0) = 0";
    }

    public function count_all_orders()
    {
        return $this->db
            ->from('orders o')
            ->join('users u', 'u.id = o.user_id')
            ->where($this->external_user_where('u'), null, false)
            ->count_all_results();
    }

    public function tabel($bulan, $tahun)
    {
        $sql = "
        SELECT 	o.id
                , p.sku
                , p.name AS nama_product
                , ca.name AS nama_kategori
                , oi.order_qty
                , oi.order_price
                , (oi.order_qty * oi.order_price) total
                , p.product_unit_1 AS satuan
                , o.order_number
                , o.order_date
                , o.order_status
                , o.payment_method
                , o.total_price
                , o.total_items
                , c.name AS coupon
                , cu.name AS customer
          FROM orders o
          LEFT JOIN order_items oi ON oi.id = o.id
          LEFT JOIN products p ON p.id = oi.product_id
          LEFT JOIN product_category ca ON ca.id = p.category_id
          LEFT JOIN coupons c ON c.id = o.coupon_id
          JOIN customers cu  ON cu.user_id = o.user_id
          JOIN users u ON u.id = o.user_id
          WHERE
          month(o.finish_date) = '" . $bulan . "' and year(o.finish_date) = '" . $tahun . "'
          AND o.order_status != 7
          AND " . $this->external_user_where('u') . "
          ORDER BY o.order_date DESC
        ";

        return $this->db->query($sql);
    }

    public function revenue_report($filters)
    {
        $this->apply_revenue_report_query($filters);

        return $this->db
            ->order_by('o.order_date', 'DESC')
            ->get();
    }

    public function revenue_report_total($filters)
    {
        $this->apply_revenue_report_query($filters, true);

        $row = $this->db->get()->row();

        return $row ? (float) $row->total_revenue : 0;
    }

    private function apply_revenue_report_query($filters, $total_only = false)
    {
        if ($total_only) {
            $this->db->select('COALESCE(SUM(o.total_price), 0) AS total_revenue', false);
        } else {
            $this->db->select("
                o.id,
                o.kd_faktur,
                o.order_number,
                o.order_date,
                o.order_status,
                o.payment_method,
                o.total_price,
                cu.shop_name,
                cu.name AS customer
            ", false);
        }

        $this->db
            ->from('orders o')
            ->join('customers cu', 'cu.user_id = o.user_id')
            ->join('users u', 'u.id = o.user_id')
            ->where_in('o.order_status', array(3, 4))
            ->where($this->external_user_where('u'), null, false);

        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(o.order_date) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(o.order_date) <=', $filters['end_date']);
        }
    }

    public function latest_orders()
    {
        $orders = $this->db->query("
            SELECT o.id, o.order_number, o.order_date, o.order_status, o.payment_method, o.total_price, o.total_items, c.name AS coupon, cu.name AS customer
            FROM orders o
            LEFT JOIN coupons c
                ON c.id = o.coupon_id
            JOIN customers cu
                ON cu.user_id = o.user_id
            JOIN users u
                ON u.id = o.user_id
            WHERE " . $this->external_user_where('u') . "
            ORDER BY o.order_date DESC
            LIMIT 5
        ");

    return $orders->result();
    }

    public function is_order_exist($id)
    {
        return ($this->db->where('id', $id)->get('orders')->num_rows() > 0) ? TRUE : FALSE;
    }

    public function order_data($id)
    {
        $data = $this->db->query("
            SELECT o.*, c.name, c.code, p.id as payment_id, p.payment_price, p.payment_date, p.picture_name, p.payment_status, p.confirmed_date, p.payment_data
            FROM orders o
            LEFT JOIN coupons c
                ON c.id = o.coupon_id
            LEFT JOIN payments p
                ON p.order_id = o.id
            WHERE o.id = '$id'
        ");

        return $data->row();
    }

    public function order_items($id)
    {
        $items = $this->db->query("
            SELECT oi.product_id, oi.order_qty, oi.order_price, p.name, p.picture_name
            FROM order_items oi
            JOIN products p
	            ON p.id = oi.product_id
            WHERE order_id = '$id'");

        return $items->result();
    }

    public function set_status($status, $order)
    {
        return $this->db->where('id', $order)->update('orders', array('order_status' => $status));
    }

    public function product_ordered($id)
    {
        $orders = $this->db->query("
            SELECT oi.*, o.id as order_id, o.order_number, o.order_date, c.name, p.product_unit AS unit
            FROM order_items oi
            JOIN orders o
	            ON o.id = oi.order_id
            JOIN customers c
                ON c.user_id = o.user_id
            JOIN products p
	            ON p.id = oi.product_id
            WHERE oi.product_id = '1'");

        return $orders->result();
    }

    public function order_by($id)
    {
        return $this->db->where('user_id', $id)->order_by('order_date', 'DESC')->get('orders')->result();
    }

    public function order_overview()
    {
        $overview = $this->db->query("
            SELECT MONTH(order_date) month, COUNT(order_date) sale
            FROM orders o
            JOIN users u
                ON u.id = o.user_id
            WHERE o.order_date >= NOW() - INTERVAL 1 YEAR
            AND " . $this->external_user_where('u') . "
            GROUP BY MONTH(order_date)");

        return $overview->result();
    }

    public function income_overview()
    {
        $data = $this->db->query("
            SELECT  MONTH(order_date) AS month, SUM(total_price) AS income
            FROM orders o
            JOIN users u
                ON u.id = o.user_id
            WHERE " . $this->external_user_where('u') . "
            GROUP BY MONTH(order_date)");

        return $data->result();
    }
}
