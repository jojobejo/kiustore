<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Terms extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // PAGE PRIVACY & POLICY
    public function policy_privacy()
    {
        $developer_name = 'PT. KARISMA INDOAGRO UNIVERSAL';
        $data = [
            'developer_name' => $developer_name,
            'store_email' => '',
            'store_phone' => '',
            'store_address' => '',
            'last_updated' => date('F j, Y')
        ];

        $settings = $this->db
            ->select('`key`, content')
            ->where_in('`key`', ['store_email', 'store_phone_number', 'store_address'])
            ->get('settings')
            ->result();

        foreach ($settings as $setting) {
            if ($setting->key === 'store_email') {
                $data['store_email'] = trim((string) $setting->content);
            } elseif ($setting->key === 'store_phone_number') {
                $data['store_phone'] = trim((string) $setting->content);
            } elseif ($setting->key === 'store_address') {
                $data['store_address'] = trim((string) $setting->content);
            }
        }

        $this->output->set_content_type('text/html', 'utf-8');
        $this->load->view('policy_privacy', $data);
    }

    // SIMPAN AGREEMENT
    public function accept()
    {
        $customer_id = $this->session->userdata('customer_id');

        if (!$customer_id) {
            redirect('auth/login');
        }

        $this->db->where('id', $customer_id);
        $this->db->update('customer', [
            'user_agreement' => 2
        ]);

        redirect('home');
    }
}
