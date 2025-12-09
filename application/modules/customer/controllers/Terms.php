<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Terms extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // load model jika perlu
        $this->load->model([
            'product_model' => 'product',
            'payment_model' => 'payment',
            'review_model'  => 'review'
        ]);
    }

    // PAGE PRIVACY & POLICY
    public function policy_privacy()
    {
        $params['title']     = 'Selamat Datang di Official Store PT. KARISMA INDOAGRO UNIVERSAL';
        $params['page_name'] = 'Privacy & Policy';

        $this->load->view('header');
        $this->load->view('policy_privacy');
        $this->load->view('footer_single');
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
