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
            SELECT u.id, u.email, c.kota_id, c.name, c.phone_number, c.address, c.shop_name, c.shop_address, c.max_credit, c.profile_picture, u.password
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
}
