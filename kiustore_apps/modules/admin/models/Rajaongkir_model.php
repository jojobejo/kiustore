<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rajaongkir_model extends CI_Model
{
    private $api_key = "197f7e1329685d3ed9d1468c54efc9dd";
    private $base_url = "https://rajaongkir.komerce.id/api/v1/";

    private $origin_karisma = "provinsi:18;city:256;2528";

    private function request($endpoint, $method = "GET", $params = [])
    {
        $ch = curl_init();
        $url = $this->base_url . $endpoint;

        if ($method === "GET" && !empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $headers = [
            "key: " . $this->api_key,
        ];

        if ($method === "POST") {
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $raw = curl_exec($ch);
        $err = curl_errno($ch) ? curl_error($ch) : null;
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) {
            return [
                'meta' => [
                    'message' => $err,
                    'code' => 500,
                    'status' => 'error'
                ],
                'data' => null
            ];
        }

        $decoded = json_decode($raw, true);
        if ($decoded === null) {
            return [
                'meta' => [
                    'message' => 'Invalid JSON from API',
                    'code' => $code ?: 500,
                    'status' => 'error'
                ],
                'raw' => $raw,
                'data' => null
            ];
        }
        return $decoded;
    }

    public function get_detail_alamat($prov_id, $city_id, $sub_districts)
    {
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

    public function get_cost($origin_id, $destination_id, $weight_grams, $courier, $price = 'lowest')
    {
        $payload = [
            'origin'      => (int)$origin_id,
            'destination' => (int)$destination_id,
            'weight'      => (int)$weight_grams,
            'courier'     => $courier,
            'price'       => $price,
        ];

        return $this->request('calculate/domestic-cost', 'POST', $payload);
    }
}
