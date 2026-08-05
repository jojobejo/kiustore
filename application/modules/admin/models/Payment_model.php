<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->user_id = get_current_user_id();
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

    public function count_all_payments()
    {
        return $this->db->get('payments')->num_rows();
    }

    public function sum_success_payment()
    {
        $row = $this->db
            ->select('SUM(o.total_price) as total_payment')
            ->from('orders o')
            ->join('users u', 'u.id = o.user_id')
            ->where_in('o.order_status', array(3, 4))
            ->where($this->external_user_where('u'), null, false)
            ->get()
            ->row();

        return $row ? $row->total_payment : 0;
    }

    public function payment_overview()
    {
        $data = $this->db->query("
            SELECT p.*, o.order_number, c.name, c.profile_picture, o.user_id
            FROM payments p
            JOIN orders o
	            ON o.id = p.order_id
            JOIN customers c
	            ON c.user_id = o.user_id
            JOIN users u
                ON u.id = o.user_id
            WHERE p.payment_status = '1'
            AND " . $this->external_user_where('u') . "
            LIMIT 5")->result();

        return $data;
    }

    public function order_data($id)
    {
        $data = $this->db->query("
            SELECT o.*, c.name, c.code, p.id as payment_id, p.payment_price, p.payment_date, p.picture_name, p.payment_status, p.confirmed_date, p.payment_data, (o.total_price + IFNULL(shipping_cost, 0) + IFNULL(insurance, 0)) as total
            FROM orders o
            LEFT JOIN coupons c
                ON c.id = o.coupon_id
            LEFT JOIN payments p
                ON p.order_id = o.id
            WHERE o.id = '$id'
        ");

        return $data->row();
    }

    public function set_payment_status($id, $status, $order_id)
    {
        $data = $this->order_data($order_id);
        $payment_method = $data->payment_method;
        $total_order = $data->total;
        $customer_id = $data->user_id;
        // print_r($customer_id);exit;
        if ($payment_method == 2) {
            $this->db->where('id', $order_id)->update('orders', array('order_status' => 3));
        } else if ($payment_method == 1) {
            $this->db->where('id', $order_id)->update('orders', array('order_status' => 6));
        } else if ($payment_method == 3) {
            $this->db->where('id', $order_id)->update('orders', array('order_status' => 3));
        }
        return $this->db->where('id', $id)->update('payments', array('payment_status' => $status));
    }

    public function set_payment_status_gagal($id, $status, $order_id)
    {
        $data = $this->order_data($order_id);
        $payment_method = $data->payment_method;
        $total_order = $data->total;
        $customer_id = $data->user_id;
        // print_r($customer_id);exit;
        if ($payment_method == 2) {
            $this->db->where('id', $order_id)->update('orders', array('order_status' => 2));
        } else if ($payment_method == 1) {
            $this->db->where('id', $order_id)->update('orders', array('order_status' => 2));
        }
        return $this->db->where('id', $id)->update('payments', array('payment_status' => $status));
    }

    public function get_all_payments($limit, $start)
    {
        $id = $this->user_id;
        if (admin_role() == 'admin' || admin_role() == 'keuangan') {

            // $payments = $this->db->query("
            //     SELECT p.id, p.payment_date, p.order_id, p.payment_price, p.payment_status as status, o.order_number, c.name AS customer
            //     FROM payments p
            //     JOIN orders o
            //         ON o.id = p.order_id
            //     JOIN customers c
            //         ON c.user_id = o.user_id
            //     ORDER BY p.payment_date DESC
            // ");

            $payments = $this->db->query("SELECT 
            b.id,
            b.order_number,
            b.kd_faktur,
            c.name as customer,
            b.total_price_topay AS payment_price,
            o.order_status as status,
            o.order_date as payment_date
            FROM briva_api b
            JOIN orders o
            ON o.order_number = b.order_number
            JOIN customers c
            ON c.user_id = b.user_id
            WHERE o.order_status != '7'
            ");
            return $payments->result();
        } else {
            $payments = $this->db->query("
                SELECT p.id, p.payment_date, p.order_id, p.payment_price, p.payment_status as status, o.order_number, c.name AS customer
                FROM payments p
                JOIN orders o
                    ON o.id = p.order_id
                JOIN customers c
                    ON c.user_id = o.user_id
                JOIN users us
                    ON us.id = c.salesman_id
                WHERE us.id = $id
                ORDER BY p.payment_date DESC
            ");

            return $payments->result();

            $orders = $this->db->query("
                SELECT o.id, o.order_number, o.order_date, o.order_status, o.payment_method, o.total_price, o.total_items, c.name AS coupon, cu.name AS customer
                FROM orders o
                LEFT JOIN coupons c
                    ON c.id = o.coupon_id
                JOIN customers cu
                    ON cu.user_id = o.user_id
                JOIN users us
                    ON us.id = cu.salesman_id
                WHERE us.id = $id
                ORDER BY o.order_date DESC
                LIMIT $start, $limit
            ");

            return $orders->result();
        }
    }

    public function get_confirmed_payments($limit, $start)
    {
        $id = $this->user_id;
        if (admin_role() == 'admin' || admin_role() == 'keuangan') {

            $payments = $this->db->query("SELECT
            b.id,
            b.order_number,
            b.kd_faktur,
            c.name as customer,
            b.total_price_topay AS payment_price,
            o.order_status as status,
            o.order_date as payment_date
            FROM briva_api b
            JOIN orders o
            ON o.order_number = b.order_number
            JOIN customers c
            ON c.user_id = b.user_id
            WHERE o.order_status != '7' AND o.order_status != '2' AND o.order_status != '10'  
                
            ");

            return $payments->result();
        } else {
            $payments = $this->db->query("SELECT 
            b.id,
            b.order_number,
            b.kd_faktur,
            c.name as customer,
            b.total_price_topay AS payment_price,
            o.order_status as status,
            o.order_date as payment_date
            FROM briva_api b
            JOIN orders o
            ON o.order_number = b.order_number
            JOIN customers c
            ON c.user_id = b.user_id
            WHERE o.order_status != '7' AND o.order_status != '2'
            ");

            return $payments->result();

            $orders = $this->db->query("
                SELECT o.id, o.order_number, o.order_date, o.order_status, o.payment_method, o.total_price, o.total_items, c.name AS coupon, cu.name AS customer
                FROM orders o
                LEFT JOIN coupons c
                    ON c.id = o.coupon_id
                JOIN customers cu
                    ON cu.user_id = o.user_id
                JOIN users us
                    ON us.id = cu.salesman_id
                WHERE us.id = $id and p.payment_status=2
                ORDER BY o.order_date DESC
                LIMIT $start, $limit
            ");

            return $orders->result();
        }
    }

    public function get_not_confirmed_payments($limit, $start)
    {
        $id = $this->user_id;
        if (admin_role() == 'admin' || admin_role() == 'keuangan') {

            $payments = $this->db->query("SELECT
            b.id,
            b.order_number,
            b.kd_faktur,
            c.name as customer,
            b.total_price_topay AS payment_price,
            o.order_status as status,
            o.order_date as payment_date
            FROM briva_api b
            JOIN orders o
            ON o.order_number = b.order_number
            JOIN customers c
            ON c.user_id = b.user_id
            WHERE o.order_status = '2' OR o.order_status = '10'
            ");

            return $payments->result();
        } else {
            $payments = $this->db->query("
                SELECT p.id, p.payment_date, p.order_id, p.payment_price, p.payment_status as status, o.order_number, c.name AS customer
                FROM payments p
                JOIN orders o
                    ON o.id = p.order_id
                JOIN customers c
                    ON c.user_id = o.user_id
                JOIN users us
                    ON us.id = c.salesman_id
                WHERE us.id = $id and p.payment_status=1
                ORDER BY p.payment_date DESC
            ");

            return $payments->result();

            $orders = $this->db->query("
                SELECT o.id, o.order_number, o.order_date, o.order_status, o.payment_method, o.total_price, o.total_items, c.name AS coupon, cu.name AS customer
                FROM orders o
                LEFT JOIN coupons c
                    ON c.id = o.coupon_id
                JOIN customers cu
                    ON cu.user_id = o.user_id
                JOIN users us
                    ON us.id = cu.salesman_id
                WHERE us.id = $id and p.payment_status=1
                ORDER BY o.order_date DESC
                LIMIT $start, $limit
            ");

            return $orders->result();
        }
    }

    public function is_payment_exist($id)
    {
        return ($this->db->where('id', $id)->get('payments')->num_rows() > 0) ? TRUE : FALSE;
    }

    public function payment_data($id)
    {
        $payment = $this->db->query("
        SELECT
              p.*,
              o.order_number,
              (
                  (
                  SELECT
                      SUM(oi.order_qty * oi.order_price) AS total_belanja
                  FROM
                      order_items oi
                  WHERE
                      oi.order_id = o.id
              ) + o.shipping_cost + o.insurance
              ) AS final_price,
              c.name AS customer
              FROM
              payments p
              JOIN orders o ON
              o.id = p.order_id
              JOIN order_items oi ON
              o.id = oi.order_id
              JOIN customers c ON
              c.user_id = o.user_id
              WHERE
              p.id = '$id'
        ");

        return $payment->row();
    }

    public function delete($id)
    {
        $this->db->query("
            DELETE
            FROM payments p
            WHERE p.id = '$id'
        ");
    }

    public function payment_by($id)
    {
        $payments = $this->db->query("
            SELECT p.id, p.payment_date, p.order_id, p.payment_price, p.payment_status as status, o.order_number, c.name AS customer, p.payment_status
            FROM payments p
            JOIN orders o
                ON o.id = p.order_id
            JOIN customers c
                ON c.user_id = o.user_id
            WHERE o.user_id = '$id'
        ");

        return $payments->result();
    }

    public function total_payment_by_id($id)
    {
        $totpayments = $this->db->query("SELECT 
            o.user_id,
            c.name AS customer,
            SUM(p.payment_price) AS total_pembelian
        FROM payments p
        JOIN orders o
            ON o.id = p.order_id
        JOIN customers c
            ON c.user_id = o.user_id
        WHERE o.user_id = '$id' and o.order_status = '6'
        GROUP BY o.user_id, c.name;
        ");

        return $totpayments->result();
    }

    public function payment_bri()
    {
        $payment_briva = $this->db->query("SELECT 
        a.*
        FROM payments a ");

        return $payment_briva->result();
    }

    public function riwayat_invoice($id)
    {
        return $this->db->query("SELECT 
            o.order_number,
            o.id AS order_id,
            o.user_id,
            c.name AS customer,
            CASE 
                WHEN o.order_status = 1 THEN 'Proses oleh Sales'
                WHEN o.order_status = 2 THEN 'Menunggu Pembayaran'
                WHEN o.order_status = 3 THEN 'Pengemasan'
                WHEN o.order_status = 4 THEN 'Pengiriman'
                WHEN o.order_status = 5 THEN 'Barang Diterima'
                WHEN o.order_status = 6 THEN 'Selesai'
                WHEN o.order_status = 7 THEN 'Dibatalkan'
                WHEN o.order_status = 8 THEN 'Menunggu Konfirmasi Pembayaran'
                WHEN o.order_status = 9 THEN 'Dalam Pengajuan Kredit'
                ELSE 'Unknown'
            END AS order_status,
            oi.product_id,
            CASE 
                WHEN pr.product_type IN (1,2,3) THEN 'ABC'
                WHEN pr.product_type = 4 THEN 'fastmoving'
                ELSE 'other'
            END AS product_category,
            oi.order_qty,
            oi.order_price,
            (oi.order_qty * oi.order_price) AS nominal_belanja,
            -- silver point
            CASE 
                WHEN pr.product_type IN (1,2,3)
                THEN FLOOR((oi.order_qty * oi.order_price) / 10000000)
                WHEN pr.product_type = 4
                THEN FLOOR((oi.order_qty * oi.order_price) / 100000000)
                ELSE 0
            END AS silver_point,
            -- gold point
            FLOOR(
                CASE 
                    WHEN pr.product_type IN (1,2,3)
                    THEN FLOOR((oi.order_qty * oi.order_price) / 10000000)
                    WHEN pr.product_type = 4
                    THEN FLOOR((oi.order_qty * oi.order_price) / 100000000)
                    ELSE 0
                END / 50
            ) AS gold_point,
            -- platinum point
            FLOOR(
                FLOOR(
                    CASE 
                        WHEN pr.product_type IN (1,2,3)
                        THEN FLOOR((oi.order_qty * oi.order_price) / 10000000)
                        WHEN pr.product_type = 4
                        THEN FLOOR((oi.order_qty * oi.order_price) / 100000000)
                        ELSE 0
                    END / 50
                ) / 2
            ) AS platinum_point
        FROM orders o
        JOIN order_items oi
            ON oi.order_id = o.id
        JOIN products pr
            ON pr.id = oi.product_id
        JOIN customers c
            ON c.user_id = o.user_id
        WHERE o.user_id = '$id' AND o.order_status = '6'
        ORDER BY o.id DESC;
        ")->result();
    }

    public function konversi_point_extravaganza_ABC($id)
    {
        return $this->db->query("SELECT 
            t.user_id,
            t.customer,
            t.total_nominal,
            FLOOR(t.total_nominal / 10000000) AS total_silver,
            FLOOR(FLOOR(t.total_nominal / 10000000) / 50) AS total_gold,
            FLOOR(FLOOR(FLOOR(t.total_nominal / 10000000) / 50) / 2) AS total_platinum
        FROM 
        (
            SELECT 
                o.user_id,
                c.name AS customer,
                SUM(oi.order_qty * oi.order_price) AS total_nominal
            FROM orders o
            JOIN order_items oi
                ON oi.order_id = o.id
            JOIN products pr
                ON pr.id = oi.product_id
            JOIN customers c
                ON c.user_id = o.user_id
            WHERE 
                o.user_id = '$id'
                AND o.order_status = 6
                AND pr.product_type IN (1,2,3)
            GROUP BY 
                o.user_id,
                c.name) t")->result();
    }

    public function konversi_point_extravaganza_fastmoving($id)
    {
        return $this->db->query("SELECT 
            t.user_id,
            t.customer,
            t.total_nominal,
            FLOOR(t.total_nominal / 100000000) AS total_silver,
            FLOOR(FLOOR(t.total_nominal / 100000000) / 50) AS total_gold,
            FLOOR(FLOOR(FLOOR(t.total_nominal / 100000000) / 50) / 2) AS total_platinum
        FROM 
        (
            SELECT 
                o.user_id,
                c.name AS customer,
                SUM(oi.order_qty * oi.order_price) AS total_nominal
            FROM orders o
            JOIN order_items oi
                ON oi.order_id = o.id
            JOIN products pr
                ON pr.id = oi.product_id
            JOIN customers c
                ON c.user_id = o.user_id
            WHERE 
                o.user_id = '$id'
                AND o.order_status = 6
                AND pr.product_type IN (4)
            GROUP BY 
                o.user_id,
                c.name) t")->result();
    }

    public function konversi_point_extravaganza_total_silver($id)
    {
        return $this->db->query("SELECT 
            SUM(
                CASE 
                    WHEN pr.product_type IN (1,2,3)
                        THEN FLOOR((oi.order_qty * oi.order_price) / 10000000)
                    WHEN pr.product_type = 4
                        THEN FLOOR((oi.order_qty * oi.order_price) / 100000000)
                    ELSE 0
                END
            ) AS total_silver
        FROM orders o
        JOIN order_items oi
            ON oi.order_id = o.id
        JOIN products pr
            ON pr.id = oi.product_id
        WHERE o.user_id = '$id' AND o.order_status = 6
        ")->row();
    }
}
