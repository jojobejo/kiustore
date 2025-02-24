<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Brivawsapi extends CI_Controller
{

    private $client_id;
    private $url;
    private $privateKey;
    private $secret_key;
    private $xPartnerId = 'kuionline';
    private $partnerServiceId = '22123';
    private $customerNo = '00218322';
    private $idcus = '111';

    public function __construct()
    {
        parent::__construct();
        $this->client_id    = 'APRGrJBHviW0cLSKZlJDZ4AHXXW9JAki';
        $this->privateKey   = file_get_contents(FCPATH . 'key/private.pem');
        $this->url          = 'https://sandbox.partner.api.bri.co.id';
        $this->secret_key   = 'oSsY5SM5svjj2mY9';
    }

    private function asymmetricSignature($client_id, $timestamp)
    {
        $stringToSign = $client_id . '|' . $timestamp;
        $signature = "";
        if (!openssl_sign($stringToSign, $signature, $this->privateKey, OPENSSL_ALGO_SHA256)) {
            throw new Exception("Failed to generate signature");
        }

        return base64_encode($signature);
    }

    private function symmetricSignature($method, $path, $body, $timestamp, $accessToken)
    {
        $hashBody = json_encode($body); // Body minify
        $hashBody = hash('sha256', $hashBody); // Calculate Hash with sha256
        $signedBody = strtolower($hashBody); // Convert to lowercase

        $stringToSign = implode(':', [
            $method,
            $path,
            $accessToken,
            $signedBody,
            $timestamp
        ]);

        $signature = hash_hmac('sha512', $stringToSign, $client_secret, true);

        return base64_encode($signature);
    }

    public function create_va()
    {
        $token_response = $this->get_token();
        $token_data = json_decode($token_response, true);

        if (isset($token_data['status']) && $token_data['status'] === 'success') {
            $access_token = $token_data['access_token'];

            $response = [
                "status" => "success",
                "message" => "Virtual Account berhasil dibuat",
                "access_token" => $access_token
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "Gagal membuat Virtual Account, tidak bisa mendapatkan access token",
                "error_detail" => $token_data
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    public function get_token()
    {
        $clientid   = $this->client_id;
        $patch      = '/snap/v1.0/access-token/b2b';
        $fullurl    = $this->url . $patch;
        $timestamp  = gmdate('Y-m-d\TH:i:s.000\Z');

        $signature = $this->asymmetricSignature($clientid, $timestamp);

        $headers = array(
            'X-SIGNATURE:' . $signature,
            'X-CLIENT-KEY:' . $clientid,
            'X-TIMESTAMP:' . $timestamp,
            'Content-Type: application/json',
        );

        $body = json_encode(['grantType' => 'client_credentials']);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullurl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return json_encode([
                "status" => "error",
                "message" => "cURL Error: " . $curlError
            ], JSON_PRETTY_PRINT);
        }

        $token = json_decode($response, true);

        if ($httpCode == 200 && isset($token['accessToken'])) {
            header('Content-Type: application/json');
            return json_encode([
                "status" => "success",
                "access_token" => $token['accessToken']
            ], JSON_PRETTY_PRINT);
        } else {
            header('Content-Type: application/json');
            return json_encode([
                "status" => "error",
                "message" => "Failed to retrieve access token",
                "http_code" => $httpCode,
                "response" => $token
            ], JSON_PRETTY_PRINT);
        }
    }
}
