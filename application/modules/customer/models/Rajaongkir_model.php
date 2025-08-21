<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rajaongkir_model extends CI_Model
{
    private $api_key = "197f7e1329685d3ed9d1468c54efc9dd";
    private $base_url = "https://rajaongkir.komerce.id/api/v1/";

    private function request($endpoint, $params = [])
    {
        $ch = curl_init();
        $url = $this->base_url . $endpoint;

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Key:" . $this->api_key,
                "Content-Type: application/json"
            ]
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function get_provinces()
    {
        return $this->request("destination/province");
    }


    public function get_cities($province_id)
    {
        return $this->request("destination/city/" . $province_id);
    }


    public function get_districts($city_id)
    {
        return $this->request("destination/district/" . $city_id);
    }

    // public function get_subdistricts($district_id)
    // {
    //     return $this->request("destination/district", ["city_id" => $district_id]);
    // }

    public function get_cost($origin_district_id, $destination_district_id, $weight, $courier)
    {
        $data = [
            "origin_district_id" => $origin_district_id,
            "destination_district_id" => $destination_district_id,
            "weight" => $weight,
            "courier" => $courier
        ];

        return $this->request("calculate/district/domestic-cost", "POST", $data);
    }
}
