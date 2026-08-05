<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ongkir_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->user_id = get_current_user_id();
    }

    private $api_key = "197f7e1329685d3ed9d1468c54efc9dd";
    private $base_url = "https://rajaongkir.komerce.id/api/v1/";

    private function curl($endpoint, $postData = null)
    {
        $url = $this->base_url . $endpoint;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "key: {$this->api_key}",
            "Content-Type: application/json"
        ]);

        if ($postData) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        }

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return ['error' => curl_error($ch)];
        }
        curl_close($ch);

        return json_decode($result, true);
    }

    public function get_provinces()
    {
        return $this->curl("destination/province");
    }

    public function get_cities($province_id)
    {
        return $this->curl("destination/city?province={$province_id}");
    }

    public function get_districts($city_id)
    {
        return $this->curl("destination/district/{$city_id}");
    }

    public function get_sub_districts($district_id)
    {
        return $this->curl("destination/sub-district?district={$district_id}");
    }

    public function calculate_cost($origin_subdistrict, $destination_subdistrict, $weight, $courier)
    {
        $postData = [
            'origin_subdistrict' => $origin_subdistrict,
            'destination_subdistrict' => $destination_subdistrict,
            'weight' => $weight,
            'courier' => $courier
        ];
        return $this->curl("calculate/district/domestic-cost", $postData);
    }
}
