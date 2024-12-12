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
            'ongkir_model' => 'ongkir',
            'admin_model' => 'admin'
        ));
        $this->load->library('form_validation');
    }
    public $api_key = "197f7e1329685d3ed9d1468c54efc9dd";


    public function index()
    {
        $params['title'] = 'Customer';

        // $data['city']    = $this->customer->count_all_customers();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key:" . $this->api_key,
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $data['province'] = array('error' => true);
        } else {
            $data['province'] = json_decode($response);
        }

        $this->load->view('header', $params);
        $this->load->view('admin/ongkir/view', $data);
        $this->load->view('footer');
    }
}
