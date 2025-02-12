<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->user_id = get_current_user_id();
    }

    public function count_all_payments()
    {
        return $this->db->get('payments')->num_rows();
    }

    public function sum_success_payment()
    {
        return $this->db->select('SUM(total_price) as total_payment')->where('order_status', 4)->or_where('order_status', 3)->get('orders')->row()->total_payment;
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
            WHERE p.payment_status = '1'
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

            $payments = $this->db->query("
                SELECT p.id, p.payment_date, p.order_id, p.payment_price, p.payment_status as status, o.order_number, c.name AS customer
                FROM payments p
                JOIN orders o
                    ON o.id = p.order_id
                JOIN customers c
                    ON c.user_id = o.user_id
                ORDER BY p.payment_date DESC
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

            $payments = $this->db->query("
                SELECT p.id, p.payment_date, p.order_id, p.payment_price, p.payment_status as status, o.order_number, c.name AS customer
                FROM payments p
                JOIN orders o
                    ON o.id = p.order_id
                JOIN customers c
                    ON c.user_id = o.user_id
                WHERE p.payment_status=2
                ORDER BY p.payment_date DESC
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
                WHERE us.id = $id and p.payment_status=2
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

            $payments = $this->db->query("
                SELECT p.id, p.payment_date, p.order_id, p.payment_price, p.payment_status as status, o.order_number, c.name AS customer
                FROM payments p
                JOIN orders o
                    ON o.id = p.order_id
                JOIN customers c
                    ON c.user_id = o.user_id
                WHERE p.payment_status=1
                ORDER BY p.payment_date DESC
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

    public function payment_bri()
    {
        $payment_briva = $this->db->query("SELECT 
        a.*
        FROM payments a ");

        return $payment_briva->result();
    }
}
