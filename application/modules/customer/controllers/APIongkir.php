<?php
defined('BASEPATH') or exit('No direct script access allowed');

class APIongkir extends CI_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        // verify_session('customer');
        $this->load->model(array(
            'Customer_model' => 'customer',
        ));
    }
    public $api_key = "850366532701e5e36174b032cfd311e9";

    public function index()
    {
        // API RAJA ONGKIR
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
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
            $data['provinsi'] = array('error' => true);
        } else {
            $data['provinsi'] = json_decode($response);
        }
        
        $this->load->view('header');
        $this->load->view('shop/APIongkirtest', $data);
        $this->load->view('footer');
    }
}
