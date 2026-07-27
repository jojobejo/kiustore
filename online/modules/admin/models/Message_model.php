<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Message_model extends CI_Model
{
    public $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->user_id = get_current_user_id();
    }

    public function get_contact()
    {
        $id = $this->user_id;
        $role = admin_role();

        $this->db
            ->select('a.*, COUNT(CASE WHEN b.status = 2 AND b.chat_from = 2 THEN b.id END) as unread', false)
            ->from('customers a')
            ->join('message b', 'b.customer_id = a.user_id', 'left');

        if ($role != 'admin') {
            $this->db->where('a.salesman_id', $id);
        }

        return $this->db
            ->group_by('a.user_id')
            ->order_by('unread', 'DESC')
            ->order_by('a.name', 'ASC')
            ->get()
            ->result();
    }

    public function get_message($id)
    {
        $message = $this->db->query("
            SELECT *
            FROM message a
            JOIN customers b
                ON a.customer_id = b.user_id
            WHERE a.customer_id = '$id'
            ORDER BY created_at
        ")->result();

        $customer_detail = $this->db->where('user_id', $id)->get('customers')->row();

        return array('message' => $message, 'customer_detail' => $customer_detail);
        // return ($this->db->order_by('created_at')->where('customer_id', $id)->get('message')->result());
    }

    public function get_message_after($customer_id, $last_id = 0)
    {
        return $this->db
            ->select('a.*, b.name')
            ->from('message a')
            ->join('customers b', 'a.customer_id = b.user_id')
            ->where('a.customer_id', (int) $customer_id)
            ->where('a.id >', (int) $last_id)
            ->order_by('a.id', 'ASC')
            ->get()
            ->result();
    }

    public function send(array $data)
    {
        $this->db->insert('message', $data);

        return $this->db->insert_id();
    }

    public function count_all_unread()
    {
        $id = $this->user_id;
        $role = admin_role();

        $this->db
            ->select('1')
            ->where(array('status' => 2, 'chat_from' => 2));

        if ($role != 'admin') {
            $this->db->where('salesman_id', $id);
        }

        $query = $this->db->get('message');

        return $query->num_rows();
    }

    public function count_unread()
    {
        $id = $this->user_id;
        $role = admin_role();

        $this->db
            ->select('1')
            ->where(array('status' => 2, 'chat_from' => 2));

        if ($role != 'admin') {
            $this->db->where('salesman_id', $id);
        }

        $query = $this->db
            ->group_by('customer_id')
            ->get('message');

        return $query->num_rows();
    }

    public function read_message($customer_id)
    {
        $id = $this->user_id;
        $role = admin_role();

        $this->db->where(array('customer_id' => $customer_id, 'chat_from' => 2));

        if ($role != 'admin') {
            $this->db->where('salesman_id', $id);
        }

        return $this->db->update('message', array('status' => 1));
    }







    public function contact_data($id)
    {
        return $this->db->where('id', $id)->get('contacts')->row();
    }

    public function set_status($id, $status)
    {
        return $this->db->where('id', $id)->update('contacts', array('status' => $status));
    }
}
