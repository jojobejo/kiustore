<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('get_token')) {
    function get_token()
    {
        $CI = &get_instance();
        $CI->load->config('briva');

        $url = 'https://sandbox.partner.api.bri.co.id/snap/v1.0/access-token/b2b';
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

// if (!function_exists('curl_request')) {
//     function curl_request($url, $token, $method, $body = null)
//     {
//         $headers = [
//             'Content-Type: application/json',
//             'Authorization: Bearer ' . $token,
//             'BRI-Timestamp: ' . gmdate('Y-m-d\TH:i:s\Z'),
//         ];

//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, $url);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//         if ($method === 'POST') {
//             curl_setopt($ch, CURLOPT_POST, true);
//             curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
//         }

//         $response = curl_exec($ch);
//         curl_close($ch);

//         return json_decode($response, true);
//     }
// }

if (!function_exists('curl_request')) {
    function curl_request($url, $endpoint, $timestamp, $token, $method, $body = [])
    {
        $xPartnerId = 'kuionline';

        $headers = [
            'Authorization: BearerToken ' . ' ' . $token,
            'X-TIMESTAMP:' . $timestamp,
            'X-SIGNATURE:' . symmetricSignature($method, $endpoint, $body, $timestamp, $token),
            'Content-Type:application/json',
            'X-PARTNER-ID:' . $xPartnerId,
            'CHANNEL-ID:00001',
            'X-EXTERNAL-ID:' . rand(100000000, 999999999)
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        file_put_contents('curl_response_log.txt', json_encode([
            'http_code' => $httpCode,
            'response' => $response
        ]) . PHP_EOL, FILE_APPEND);

        if ($response === false) {
            return ['status' => false, 'message' => curl_error($ch)];
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    if (!function_exists('symmetricSignature')) {
        function symmetricSignature($method, $endpoint, $body, $timestamp, $accessToken)
        {
            global $client_secret; //Consumer Secret

            $hashBody = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $hashBody = hash('sha256', $hashBody);
            $signedBody = strtolower($hashBody); // Convert to lowercase

            $parsedUrl = parse_url($endpoint);
            $endpointPath = $parsedUrl['path']; // Gunakan path tanpa domain

            $stringToSign = implode(':', [
                $method,
                $endpointPath,
                $accessToken,
                $signedBody,
                $timestamp
            ]);

            $signature = hash_hmac('sha512', $stringToSign, $client_secret, true);

            file_put_contents('signature_log.txt', json_encode([
                'stringToSign' => $stringToSign,
                'signature' => base64_encode($signature)
            ]) . PHP_EOL, FILE_APPEND);

            // X-SIGNATURE
            return base64_encode($signature);
        }
    }
}
