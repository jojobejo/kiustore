<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ongkir extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        verify_session('admin');

        $this->load->model(array(
            'customer_model' => 'customer',
            'order_model' => 'order',
            'salesman_model' => 'salesman',
            'payment_model' => 'payment',
            'admin_model' => 'admin'
        ));
        $this->load->library('form_validation');
    }
    public $api_key = "197f7e1329685d3ed9d1468c54efc9dd";


    public function index()
    {
        $params['title'] = 'Customer';

        $this->load->view('header', $params);
        $this->load->view('admin/ongkir/view');
        $this->load->view('footer');
    }
}
