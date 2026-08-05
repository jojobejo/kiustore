<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ongkir extends CI_Controller
{
    private $api_key = "197f7e1329685d3ed9d1468c54efc9dd";
    private $base_url = "https://rajaongkir.komerce.id/api/v1/";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        $params['title'] = 'Customer';

        $this->load->view('header', $params);
        $this->load->view('admin/ongkir/view');
        $this->load->view('footer');
    }

    private function request($endpoint, $method = "GET", $data = [])
    {
        $ch = curl_init();

        $url = $this->base_url . $endpoint;
        if ($method == "GET" && !empty($data)) {
            $url .= "?" . http_build_query($data);
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $this->api_key,
                "Content-Type: application/json"
            ]
        ]);

        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function province()
    {
        $result = $this->request("destination/province");
        echo json_encode($result);
    }

    // Get Kota berdasarkan ID Provinsi
    public function city($province_id = null)
    {
        $result = $this->request("destination/city", "GET", ["province_id" => $province_id]);
        echo json_encode($result);
    }

    // Get Kecamatan
    public function district($city_id = null)
    {
        $result = $this->request("destination/district", "GET", ["city_id" => $city_id]);
        echo json_encode($result);
    }

    // Get Kelurahan
    public function sub_district($district_id = null)
    {
        $result = $this->request("destination/sub-district", "GET", ["district_id" => $district_id]);
        echo json_encode($result);
    }

    // Hitung ongkir
    public function cost()
    {
        $origin = $this->input->post("origin_district_id");
        $destination = $this->input->post("destination_district_id");
        $weight = $this->input->post("weight");
    }
}
