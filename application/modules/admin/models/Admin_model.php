<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function latest_customers()
    {
        return $this->db->order_by('id', 'DESC')->get('customers')->result();
    }

    private function has_internal_column()
    {
        return $this->db->field_exists('is_internal', 'users');
    }

    public function get_role_options()
    {
        return $this->db
            ->select('role')
            ->from('users')
            ->where('role IS NOT NULL', null, false)
            ->where('role !=', '')
            ->group_by('role')
            ->order_by('role', 'ASC')
            ->get()
            ->result();
    }

    public function get_all_users($filters = array())
    {
        $internalSelect = $this->has_internal_column() ? 'u.is_internal' : '0';

        $this->db
            ->select("u.id, COALESCE(u.name, c.name, u.email) AS display_name, u.name, c.name AS customer_name, c.shop_name, u.email, u.role, u.register_date, u.status, {$internalSelect} AS is_internal", false)
            ->from('users u')
            ->join('customers c', 'c.user_id = u.id', 'left')
            ->order_by('u.register_date', 'DESC');

        if (!empty($filters['role'])) {
            $this->db->where('u.role', $filters['role']);
        }

        if ($this->has_internal_column() && isset($filters['is_internal']) && $filters['is_internal'] !== '') {
            $this->db->where('u.is_internal', (int) $filters['is_internal']);
        }

        return $this->db->get()->result();
    }

    public function get_all_admin()
    {
        $admin = $this->db->query("
            SELECT *
            FROM users
            WHERE role = 'salesman'
            AND status = '1'
            ORDER BY name asc
        ");

        return $admin->result();
    }

    public function register_user($data)
    {
        if ($this->has_internal_column()) {
            if (!array_key_exists('is_internal', $data)) {
                $data['is_internal'] = 0;
            }
        } else {
            unset($data['is_internal']);
        }

        $this->db->insert('users', $data);

        return $this->db->insert_id();
    }

    public function delete_user($id)
    {
        $this->db->where('id', $id)->delete('users');
        $this->db->where('id', $id)->update('users', array('status' => 0));
    }

    public function activate_user($id)
    {
        $this->db->where('id', $id)->update('users', array('status' => 1));
    }

    public function is_users_exist($id)
    {
        return ($this->db->where('id', $id)->get('users')->num_rows() > 0) ? TRUE : FALSE;
    }

    public function users_data($id)
    {
        $internalSelect = $this->has_internal_column() ? 'p.is_internal' : '0';
        $data = $this->db->query("
            SELECT p.*, {$internalSelect} AS is_internal
            FROM users p
            WHERE p.id = '$id'
        ")->row();

        return $data;
    }

    public function is_users_have_image($id)
    {
        $data = $this->users_data($id);
        $file = $data->picture_name;

        return file_exists('./assets/uploads/users/'. $file) ? TRUE : FALSE;
    }

    public function edit_users($id, $users)
    {
        if (!$this->has_internal_column()) {
            unset($users['is_internal']);
        }

        return $this->db->where('id', $id)->update('users', $users);
    }

    public function update_internal_status($id, $is_internal)
    {
        if (!$this->has_internal_column()) {
            return false;
        }

        return $this->db
            ->where('id', $id)
            ->update('users', array('is_internal' => (int) $is_internal));
    }

}
