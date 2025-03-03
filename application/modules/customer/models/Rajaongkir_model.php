<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rajaongkir_model extends CI_Model {

    private $api_key;
    private $base_url;

    public function __construct() {
        parent::__construct();
        $this->config->load('rajaongkir');
        $this->api_key = $this->config->item('rajaongkir_api_key');
        $this->base_url = $this->config->item('rajaongkir_base_url');
    }

    private function request($endpoint, $params = []) {
        $url = $this->base_url . $endpoint;
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url . '?' . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["key: $this->api_key"],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    public function get_provinces() {
        return $this->request('province');
    }

    public function get_cities($province_id) {
        return $this->request('city', ['province' => $province_id]);
    }

    public function get_subdistricts($city_id) {
        return $this->request('subdistrict', ['city' => $city_id]);
    }

    public function get_shipping_cost($origin, $destination, $weight, $courier) {
        return $this->request('cost', [
            'origin' => $origin,
            'originType' => 'subdistrict',
            'destination' => $destination,
            'destinationType' => 'subdistrict',
            'weight' => $weight,
            'courier' => $courier
        ]);
    }
}
?>
