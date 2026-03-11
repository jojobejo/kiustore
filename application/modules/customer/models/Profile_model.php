<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile_model extends CI_Model
{
    public $user_id;

    public function __construct()
    {
        parent::__construct();

        $this->user_id = get_current_user_id();
    }

    public function get_profile()
    {
        $id = $this->user_id;

        $data = $this->db->query("
            SELECT u.id, c.alamat_kirim, u.email,c.province_id, c.kota_id, c.name, c.phone_number, c.address, c.shop_name, c.shop_address, c.max_credit, c.profile_picture, u.password
            FROM users u
            JOIN customers c
                ON c.user_id = u.id
            WHERE u.id = '$id'
        ");

        return $data->row();
    }

    public function update($data)
    {
        return $this->db->where('user_id', $this->user_id)->update('customers', $data);
    }

    public function update_account($data)
    {
        return $this->db->where('id', $this->user_id)->update('users', $data);
    }

    public function reset_alamat_cust($data)
    {
        return $this->db->where('id', $this->user_id)->update('users', $data);
    }

    public function detail_loc()
    {
        $id = $this->user_id;

        $data = $this->db->query("
            SELECT COUNT(a.user_id) AS loc_sts FROM customer_location a where a.user_id = '$id'
        ");

        return $data->row();
    }

    public function get_total_silver_points()
    {
        $id = $this->user_id;
        $data = $this->db->query("SELECT
                (
                    COALESCE((
                        SELECT FLOOR(SUM(oi.order_qty * oi.order_price) / 10000000)
                        FROM orders o
                        JOIN order_items oi
                            ON oi.order_id = o.id
                        JOIN products pr
                            ON pr.id = oi.product_id
                        WHERE o.user_id = '$id'
                            AND o.order_status = '6'
                            AND pr.product_type IN (1,2,3)
                    ), 0)
                    +
                    COALESCE((
                        SELECT FLOOR(SUM(oi.order_qty * oi.order_price) / 100000000)
                        FROM orders o
                        JOIN order_items oi
                            ON oi.order_id = o.id
                        JOIN products pr
                            ON pr.id = oi.product_id
                        WHERE o.user_id = '$id'
                            AND o.order_status = '6'
                            AND pr.product_type IN (4)
                    ), 0)
                ) AS total_silver
        ");

        return $data->row();
    }
}
