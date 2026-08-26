<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public $user_id;

    public function __construct()
    {
        parent::__construct();

        $this->user_id = get_current_user_id();
    }

    public function count_all_orders()
    {
        $id = $this->user_id;

        return $this->db->where('user_id', $id)->get('orders')->num_rows();
    }

    public function count_process_order()
    {
        $id = $this->user_id;

        return $this->db->where(array('user_id' => $id, 'order_status' => 2))->get('orders')->num_rows();
    }

    public function get_all_orders($filter = 'semua')
    {
        $id = $this->user_id;

        if ($filter == 'semua') {
            $orders = $this->db->query("SELECT
                  o.id,
                  o.total_price,
                  SUM(oi.order_qty * oi.order_price) AS total_belanja,
                  o.order_number,
                  o.order_date,
                  o.order_status,
                  p.payment_status,
                  o.payment_method,
                  o.shipping_cost,
                  o.insurance,
                  o.total_items,
                  o.due_date,
                  c.name AS coupon,
                  cu.name AS customer,
                  (
                      (
                      SELECT
                          SUM(oi.order_qty * oi.order_price) AS total_belanja
                      FROM
                          order_items oi
                      WHERE
                          oi.order_id = o.id
                  ) + o.shipping_cost + o.insurance
                  ) AS final_price
              FROM
                  orders o
              JOIN order_items oi ON
                  o.id = oi.order_id
              LEFT JOIN coupons c ON
                  c.id = o.coupon_id
              JOIN customers cu ON
                  cu.user_id = o.user_id
              LEFT JOIN payments p ON
                  o.id = p.order_id
              WHERE
                  o.user_id = '$id'
              GROUP BY
                  o.id
              ORDER BY
                  o.order_date
              DESC
            ");
        } elseif ($filter == '1day') {
            $orders = $this->db->query("
            SELECT
                  o.id,
                  o.total_price,
                  SUM(oi.order_qty * oi.order_price) AS total_belanja,
                  o.order_number,
                  o.order_date,
                  o.order_status,
                  p.payment_status,
                  o.payment_method,
                  o.shipping_cost,
                  o.insurance,
                  o.total_items,
                  o.due_date,
                  c.name AS coupon,
                  cu.name AS customer,
                  (
                      (
                      SELECT
                          SUM(oi.order_qty * oi.order_price) AS total_belanja
                      FROM
                          order_items oi
                      WHERE
                          oi.order_id = o.id
                  ) + o.shipping_cost + o.insurance
                  ) AS final_price
                FROM orders o
                JOIN order_items oi ON
                    o.id = oi.order_id
                LEFT JOIN coupons c
                    ON c.id = o.coupon_id
                JOIN customers cu
                    ON cu.user_id = o.user_id
                LEFT JOIN payments p
                	  ON o.id = p.order_id
                WHERE o.user_id = '$id' AND
                DATE(o.order_date) = CURDATE()
                GROUP BY o.id
                ORDER BY o.order_date DESC
            ");
        } elseif ($filter == '1week') {
            $orders = $this->db->query("
            SELECT
                  o.id,
                  o.total_price,
                  SUM(oi.order_qty * oi.order_price) AS total_belanja,
                  o.order_number,
                  o.order_date,
                  o.order_status,
                  p.payment_status,
                  o.payment_method,
                  o.shipping_cost,
                  o.insurance,
                  o.total_items,
                  o.due_date,
                  c.name AS coupon,
                  cu.name AS customer,
                  (
                      (
                      SELECT
                          SUM(oi.order_qty * oi.order_price) AS total_belanja
                      FROM
                          order_items oi
                      WHERE
                          oi.order_id = o.id
                  ) + o.shipping_cost + o.insurance
                  ) AS final_price
                FROM orders o
                JOIN order_items oi ON
                    o.id = oi.order_id
                LEFT JOIN coupons c
                    ON c.id = o.coupon_id
                JOIN customers cu
                    ON cu.user_id = o.user_id
                    LEFT JOIN payments p
                    	  ON o.id = p.order_id
                WHERE o.user_id = '$id' AND
                date(o.order_date) BETWEEN (NOW() - INTERVAL 14 DAY) AND NOW()
                GROUP BY o.id
                ORDER BY o.order_date DESC
            ");
        } elseif ($filter == '1month') {
            $orders = $this->db->query("
            SELECT
                  o.id,
                  o.total_price,
                  SUM(oi.order_qty * oi.order_price) AS total_belanja,
                  o.order_number,
                  o.order_date,
                  o.order_status,
                  p.payment_status,
                  o.payment_method,
                  o.shipping_cost,
                  o.insurance,
                  o.total_items,
                  o.due_date,
                  c.name AS coupon,
                  cu.name AS customer,
                  (
                      (
                      SELECT
                          SUM(oi.order_qty * oi.order_price) AS total_belanja
                      FROM
                          order_items oi
                      WHERE
                          oi.order_id = o.id
                  ) + o.shipping_cost + o.insurance
                  ) AS final_price
                FROM orders o
                JOIN order_items oi ON
                    o.id = oi.order_id
                LEFT JOIN coupons c
                    ON c.id = o.coupon_id
                JOIN customers cu
                    ON cu.user_id = o.user_id
                LEFT JOIN payments p
                    	  ON o.id = p.order_id
                WHERE o.user_id = '$id' AND
                date(o.order_date) BETWEEN (NOW() - INTERVAL 1 MONTH) AND NOW()
                GROUP BY o.id
                ORDER BY o.order_date DESC
            ");
        } elseif ($filter == '6month') {
            $orders = $this->db->query("
            SELECT
                  o.id,
                  o.total_price,
                  SUM(oi.order_qty * oi.order_price) AS total_belanja,
                  o.order_number,
                  o.order_date,
                  o.order_status,
                  p.payment_status,
                  o.payment_method,
                  o.shipping_cost,
                  o.insurance,
                  o.total_items,
                  o.due_date,
                  c.name AS coupon,
                  cu.name AS customer,
                  (
                      (
                      SELECT
                          SUM(oi.order_qty * oi.order_price) AS total_belanja
                      FROM
                          order_items oi
                      WHERE
                          oi.order_id = o.id
                  ) + o.shipping_cost + o.insurance
                  ) AS final_price
                FROM orders o
                JOIN order_items oi ON
                    o.id = oi.order_id
                LEFT JOIN coupons c
                    ON c.id = o.coupon_id
                JOIN customers cu
                    ON cu.user_id = o.user_id
                    LEFT JOIN payments p
                    	  ON o.id = p.order_id
                WHERE o.user_id = '$id' AND
                o.order_date BETWEEN (NOW() - INTERVAL 6 MONTH) AND NOW()
                GROUP BY o.id
                ORDER BY o.order_date DESC
            ");
        } elseif ($filter == '1year') {
            $orders = $this->db->query("
            SELECT
                  o.id,
                  o.total_price,
                  SUM(oi.order_qty * oi.order_price) AS total_belanja,
                  o.order_number,
                  o.order_date,
                  o.order_status,
                  p.payment_status,
                  o.payment_method,
                  o.shipping_cost,
                  o.insurance,
                  o.total_items,
                  o.due_date,
                  c.name AS coupon,
                  cu.name AS customer,
                  (
                      (
                      SELECT
                          SUM(oi.order_qty * oi.order_price) AS total_belanja
                      FROM
                          order_items oi
                      WHERE
                          oi.order_id = o.id
                  ) + o.shipping_cost + o.insurance
                  ) AS final_price
                  FROM orders o
                  JOIN order_items oi ON
                      o.id = oi.order_id
                LEFT JOIN coupons c
                    ON c.id = o.coupon_id
                JOIN customers cu
                    ON cu.user_id = o.user_id
                    LEFT JOIN payments p
                    	  ON o.id = p.order_id
                WHERE o.user_id = '$id' AND
                o.order_date BETWEEN (NOW() - INTERVAL 1 YEAR) AND NOW()
                GROUP BY o.id
                ORDER BY o.order_date DESC
            ");
        }

        $result = $orders->result();

        foreach ($result as $order) {
            $subtotal = (float) $order->total_belanja;
            $total_after_discount = isset($order->total_price) ? (float) $order->total_price : $subtotal;
            $discount_amount = max(0, $subtotal - $total_after_discount);

            $order->discount_amount = $discount_amount;
            $order->final_price = $total_after_discount + (float) $order->shipping_cost + (float) $order->insurance;
            $order->has_coupon_discount = !empty($order->coupon) || $discount_amount > 0;
        }

        return $result;
    }

    // public function get_orders($val)
    // {
    //     $id = $this->user_id;

    //     switch ($val) {
    //         case '1':
    //             $orders = $this->db->query("
    //                 SELECT o.id, o.order_number, o.order_date, o.order_status, o.payment_method, o.total_price, o.total_items, c.name AS coupon, cu.name AS customer
    //                 FROM orders o
    //                 LEFT JOIN coupons c
    //                     ON c.id = o.coupon_id
    //                 JOIN customers cu
    //                     ON cu.user_id = o.user_id
    //                 WHERE o.user_id = '$id'
    //                 AND (o.payment_method < 4 AND o.order_status == 2) OR (o.payment_method >= 4 AND o.order_status == 1)
    //                 ORDER BY o.order_date DESC
    //             ");
    //         break;
    //         case '2':
    //             $orders = $this->db->query("
    //                 SELECT o.id, o.order_number, o.order_date, o.order_status, o.payment_method, o.total_price, o.total_items, c.name AS coupon, cu.name AS customer
    //                 FROM orders o
    //                 LEFT JOIN coupons c
    //                     ON c.id = o.coupon_id
    //                 JOIN customers cu
    //                     ON cu.user_id = o.user_id
    //                 WHERE o.user_id = '$id'
    //                 ORDER BY o.order_date DESC
    //             ");
    //         break;
    //         case '3':
    //             $orders = $this->db->query("
    //                 SELECT o.id, o.order_number, o.order_date, o.order_status, o.payment_method, o.total_price, o.total_items, c.name AS coupon, cu.name AS customer
    //                 FROM orders o
    //                 LEFT JOIN coupons c
    //                     ON c.id = o.coupon_id
    //                 JOIN customers cu
    //                     ON cu.user_id = o.user_id
    //                 WHERE o.user_id = '$id'
    //                 ORDER BY o.order_date DESC
    //             ");
    //         break;
    //         case '4':
    //             $orders = $this->db->query("
    //                 SELECT o.id, o.order_number, o.order_date, o.order_status, o.payment_method, o.total_price, o.total_items, c.name AS coupon, cu.name AS customer
    //                 FROM orders o
    //                 LEFT JOIN coupons c
    //                     ON c.id = o.coupon_id
    //                 JOIN customers cu
    //                     ON cu.user_id = o.user_id
    //                 WHERE o.user_id = '$id'
    //                 ORDER BY o.order_date DESC
    //             ");
    //         break;
    //         case '5':
    //             $orders = $this->db->query("
    //                 SELECT o.id, o.order_number, o.order_date, o.order_status, o.payment_method, o.total_price, o.total_items, c.name AS coupon, cu.name AS customer
    //                 FROM orders o
    //                 LEFT JOIN coupons c
    //                     ON c.id = o.coupon_id
    //                 JOIN customers cu
    //                     ON cu.user_id = o.user_id
    //                 WHERE o.user_id = '$id'
    //                 ORDER BY o.order_date DESC
    //             ");
    //         break;

    //         default:
    //             # code...
    //             break;
    //     }



    //     return $orders->result();
    // }

    public function order_with_bank_payments($id)
    {
        // return $this->db->where(array('user_id' => $this->user_id, 'payment_method' => 1, 'order_status' => 1))->order_by('order_date', 'DESC')->get('orders')->result();

        $items = $this->db->query("
        SELECT
              o.*,
              (
                  (
                  SELECT
                      SUM(oi.order_qty * oi.order_price) AS total_belanja
                  FROM
                      order_items oi
                  WHERE
                      oi.order_id = o.id
              ) + o.shipping_cost + o.insurance
              ) AS final_price
              FROM
              orders o
              JOIN order_items oi ON
              o.id = oi.order_id
              WHERE
              o.id = '$id'
        ");

        return $items->row();
    }

    public function is_order_exist($id)
    {
        $user_id = $this->user_id;
        return ($this->db->where(array('id' => $id, 'user_id' => $user_id))->get('orders')->num_rows() > 0) ? TRUE : FALSE;
    }

    public function is_ongkir_exist($kd)
    {
        $user_id = $this->user_id;
        return $this->db->query("SELECT 
            IFNULL(
                (SELECT a.jsongkir 
                FROM tbtestongkir a 
                WHERE a.kd_faktur = '$kd' 
                AND a.idcustomer = '$user_id' 
                AND a.status = '3'), 
                0
            ) AS ongkir_price
        ")->result();
    }

    public function getongkir_checkout($id, $now)
    {
        return $this->db->query("SELECT
        a.*
        FROM tbtestongkir a
        WHERE a.idcustomer = '$id'
        AND a.status = '3'
        AND a.create_at = '$now'
        ")->result();
    }

    public function resi_order($kd)
    {
        return $this->db->query("SELECT
        a.*
        FROM tbtestongkir a
        WHERE a.kd_faktur = '$kd'
        AND a.status = '3'
        ")->result();
    }

    public function order_data($id)
    {
        $data = $this->db->query("SELECT
                o.id AS order_id,
                (
                  SELECT
                      SUM(oi.order_qty * oi.order_price) AS total_belanja
                  FROM
                      order_items oi
                  WHERE
                      oi.order_id = '$id'
                  ) as total_belanja,
                o.*,
                b.order_number,
                b.userno,
                p.id,
                c.name,
                c.code,
                c.credit kupon,
                p.payment_price,
                p.payment_date,
                p.picture_name,
                p.payment_status,
                p.confirmed_date,
                p.payment_data,
                o.order_number AS number_ordered,
                SUM(
                    o.total_price + o.shipping_cost + o.insurance
                ) AS final_price
            FROM
                orders o
            LEFT JOIN coupons c ON
                c.id = o.coupon_id
            LEFT JOIN payments p ON
                p.order_id = o.id
            LEFT JOIN briva_api b ON
                o.order_number = b.order_number
            WHERE
                o.id = '$id'

    ");

        return $data->row();
    }

    public function is_created($cusid)
    {
        $this->db->select('*');
        $this->db->from('briva_api');
        $this->db->where('user_id', $cusid);
        $this->db->where('exp_date <', date('Y-m-d H:i:s'));
        $this->db->order_by('exp_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function delete_va($invoice)
    {
        $this->db->where('order_number', $invoice)->delete('briva_api');
    }

    public function data_va($id, $invoice)
    {
        $data = $this->db->query("SELECT 
        a.* FROM briva_api a WHERE a.user_id = '$id' AND a.order_number = '$invoice'");
        return $data->result();
    }

    public function get_data_order($invoice, $idcust)
    {
        $data = $this->db->query("SELECT 
        a.order_number,
        a.kd_faktur,
        b.name,
        b.phone_number,
        a.total_price,
        a.user_id,
        c.jsongkir
        FROM orders a
        LEFT JOIN customers b ON b.user_id = a.user_id
        LEFT JOIN tbtestongkir c ON c.kd_faktur = a.kd_faktur
        WHERE a.order_number = '$invoice' AND a.user_id = '$idcust'");
        return $data->result();
    }

    public function get_data_order_by_va($invoice, $idcust)
    {
        $data = $this->db->query("SELECT 
        a.order_number,
        a.kd_faktur,
        b.name,
        b.phone_number,
        a.total_price,
        a.user_id,
        c.jsongkir
        FROM orders a
        LEFT JOIN customers b ON b.user_id = a.user_id
        LEFT JOIN tbtestongkir c ON c.kd_faktur = a.kd_faktur
        WHERE a.order_number = '$invoice' AND a.user_id = '$idcust'");
        return $data->row();
    }

    public function get_by_order_number($invoice)
    {
        $data = $this->db->query("SELECT 
        a.* FROM briva_api a WHERE a.order_number = '$invoice'");
        return $data->result();
    }

    public function is_va_exist($id)
    {
        $user_id = $this->user_id;
        return ($this->db->where(array('order_number' => $id, 'user_id' => $user_id))->get('briva_api')->num_rows() > 0) ? TRUE : FALSE;
    }

    public function get_latest_active_va($user_id)
    {
        return $this->db->select('briva_api.*, od.id as order_id')
            ->from('briva_api')
            ->join('orders od', 'od.order_number = briva_api.order_number', 'left')
            ->where('briva_api.user_id', $user_id)
            ->where('briva_api.status', '1')
            ->where('od.user_id', $user_id)
            ->order_by('briva_api.id', 'DESC')
            ->limit(1)
            ->get()
            ->row();
    }

    public function mark_va_expired_by_id($id)
    {
        return $this->db->where('id', $id)
            ->update('briva_api', ['status' => 3]);
    }


    public function order_data_coba($id)
    {
        $data = $this->db->query("SELECT
            x.order_id,
            x.total_belanja,
            x.kode_faktur,
            x.invoice_number AS invoice_number,
            x.ttb_number as ttb_number,
            x.order_date as order_date,
            x.due_date as due_date,
            x.total_items as total_items,
            x.shipping_cost as shipping_cost
            FROM
            (
                SELECT
                o.order_number AS kode_faktur,
                o.invoice_number AS invoice_number,
                o.ttb_number as ttb_number,
                o.order_date as order_date,
                o.due_date as due_date,
                o.total_items as total_items,
                o.shipping_cost as shipping_cost,
                o.id AS order_id,
                p.id,
                c.name,
                c.code,
                c.credit kupon,
                p.payment_price,
                p.payment_date,
                p.picture_name,
                p.payment_status,
                p.confirmed_date,
                p.payment_data,
                
                SUM(o.total_price + o.shipping_cost + o.insurance) AS final_price,
                (SELECT SUM(oi.order_qty * oi.order_price) FROM order_items oi WHERE oi.order_id = '$id') AS total_belanja
                FROM orders o 
                LEFT JOIN coupons c ON c.id = o.coupon_id
                LEFT JOIN payments p ON p.order_id = o.id
                WHERE o.id = '$id'
            ) AS x
        ");

        return $data->row();
    }

    public function order_items_coba($id)
    {
        $items = $this->db->query("SELECT
        x.product_id,
        x.total_belanja,
        x.order_qty,
        x.name,
        x.satuan_text,
        x.satuan_qty,
        x.order_price
        FROM
        (
            SELECT
            oi.product_id AS product_id,
            (SELECT SUM(oi.order_qty * oi.order_price) FROM order_items oi WHERE oi.order_id = '$id') AS total_belanja,
            oi.order_qty AS order_qty,
            oi.satuan AS satuan ,
            oi.satuan_text AS satuan_text,
            oi.satuan_qty AS satuan_qty,
            oi.order_price AS order_price,
            p.name AS name 
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = '$id'
            GROUP BY oi.id
        ) AS x 
        ");

        return $items->result();
    }

    public function order_items($id)
    {
        $items = $this->db->query("SELECT
              oi.product_id,
              (
              SELECT
                  SUM(oi.order_qty * oi.order_price) AS total_belanja
              FROM
                  order_items oi
              WHERE
                  oi.order_id = '$id'
              ) as total_belanja,
              oi.order_qty,
              oi.satuan,
              oi.satuan_text,
              oi.satuan_qty,
              oi.order_price,
              p.name,
              p.picture_name
              FROM
              order_items oi
              JOIN products p ON
              p.id = oi.product_id
              WHERE
              order_id = '$id'
              GROUP BY
              oi.id
            ");

        return $items->result();
    }

    public function input_va($data)
    {
        return $this->db->insert('briva_api', $data);
    }

    public function cancel_order($id)
    {
        $data = $this->order_data($id);
        $payment_method = $data->payment_method;

        $status =  7;

        return $this->db->where('id', $id)->update('orders', array('order_status' => $status));
    }

    public function terima_order($v)
    {
        $data = $this->order_data($v['id']);
        $payment_method = $data->payment_method;

        $status =  ($payment_method == 1) ? 2 : 6;
        $tanggal_selesai = date('Y-m-d H:i:s');
        return $this->db->where('id', $v['id'])->update('orders', array('finish_date' => $tanggal_selesai, 'rating' => $v['rating'], 'rating_desc' => $v['rating_desc'], 'order_status' => $status));
    }

    public function delete_order($id)
    {
        if (($this->db->where('order_id', $id)->get('order_items')->num_rows() > 0))
            $this->db->where('order_id', $id)->delete('order_items');

        if (($this->db->where('order_id', $id)->get('payments')->num_rows() > 0))
            $this->db->where('order_id', $id)->delete('payments');

        $this->db->where('id', $id)->delete('orders');
    }

    public function all_orders()
    {
        return $this->db->where('user_id', $this->user_id)->order_by('order_date', 'DESC')->get('orders')->result();
    }

    public function get_data_customer($id)
    {
        $customer = $this->db->query("SELECT
            c.user_id as id,
            c.max_credit,
            c.profile_picture,
            c.name,
            u.email,
            c.phone_number,
            c.shop_name,
            c.shop_address,
            c.address,
            c.salesman_id,
            IFNULL (s.name, '-') AS sales_name,
            u.status,
            u.register_date,
            c.shop_name,
            c.level,
            c.kota_id,
            c.va_code,
            CONCAT('91118',c.user_id,(SELECT RIGHT(MAX(c.phone_number), 5))) AS vacode
        FROM
            customers c
            JOIN users u ON u.id = c.user_id
            LEFT JOIN users s ON s.id = c.salesman_id
        WHERE
            c.user_id = '$id'
        ");

        return $customer->row();
    }

    // public function generateva($id)
    // {
    //     $cd = $this->db->query("SELECT MAX(RIGHT(kd_po,4)) AS kd_max FROM tb_po WHERE DATE(create_at)=CURDATE()");
    //     $kd = "";
    //     if ($cd->num_rows() > 0) {
    //         foreach ($cd->result() as $k) {
    //             $tmp = ((int)$k->kd_max) + 1;
    //             $kd = sprintf("%04s", $tmp);
    //         }
    //     } else {
    //         $kd = "0001";
    //     }
    //     date_default_timezone_set('Asia/Jakarta');
    //     return 'KPO' . date('dmy') . $id . $kd;
    // }
}
