<!-- config/briva.php -->
<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['briva'] = [
    'url'               => 'https://sandbox.partner.api.bri.co.id', // Sesuaikan dengan lingkungan API BRIVA
    'partner_service_id' => 'YOUR_PARTNER_SERVICE_ID',
    'customer_no'       => 'YOUR_CUSTOMER_NUMBER',
    'client_id'         => 'YOUR_CLIENT_ID',
    'client_secret'     => 'YOUR_CLIENT_SECRET'
];

// helpers/briva_helper.php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_token')) {
    function get_token() {
        $CI =& get_instance();
        $CI->load->config('briva');
        
        $url = 'https://sandbox.partner.api.bri.co.id/oauth/client_credential/accesstoken';
        $client_id = $CI->config->item('briva')['client_id'];
        $client_secret = $CI->config->item('briva')['client_secret'];
        
        $headers = [
            'Content-Type: application/x-www-form-urlencoded'
        ];
        
        $body = http_build_query([
            'grant_type' => 'client_credentials'
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$client_id:$client_secret");

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        return $data['access_token'] ?? null;
    }
}

if (!function_exists('curl_request')) {
    function curl_request($url, $token, $method, $body = null) {
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
            'BRI-Timestamp: ' . gmdate('Y-m-d\TH:i:s\Z'),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    
}
