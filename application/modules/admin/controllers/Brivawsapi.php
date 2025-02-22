<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Brivawsapi extends CI_Controller
{
    private $client_id = 'APRGrJBHviW0cLSKZlJDZ4AHXXW9JAki';
    private $secret_key = 'oSsY5SM5svjj2mY9';
    private $url = 'https://sandbox.partner.api.bri.co.id';
    private $privateKey;
    private $xPartnerId = 'kuionline';
    private $partnerServiceId = '22123';
    private $customerNo = '00218322';
    private $idcus = '111';

    public function __construct()
    {
        parent::__construct();
        $this->privateKey = file_get_contents(FCPATH . 'key/private.pem');
    }

    private function generate_asymmetric_signature($client_id, $timestamp)
    {
        $data = $client_id . "|" . $timestamp;
        openssl_sign($data, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    public function getToken()
    {
        $endpoint = '/snap/v1.0/access-token/b2b';
        $fullUrl = $this->url . $endpoint;
        $timestamp = gmdate("Y-m-d\TH:i:s\Z");

        $headers = [
            'X-SIGNATURE: ' . $this->generate_asymmetric_signature($this->client_id, $timestamp),
            'X-CLIENT-KEY: ' . $this->client_id,
            'X-TIMESTAMP: ' . $timestamp,
            'Content-Type: application/json',
        ];

        $body = json_encode(['grantType' => 'client_credentials']);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        $result = json_decode($response, true);
    
        // Debugging: Log response ke file
        log_message('error', 'Response from BRI: ' . print_r($result, true));
    
        if ($httpCode != 200) {
            return null;
        }
    
        return $result['accessToken'] ?? null;
    }

    public function createBriva()
    {
        $token = '7UyCLNDQCLOqfCINo085kcB19nwH';
        $endpoint = '/snap/v1.0/briva';
        $fullUrl = $this->url . $endpoint;
        $timestamp = gmdate("Y-m-d\TH:i:s\Z");

        $headers = [
            'Authorization:BearerToken ' . $token,
            'X-TIMESTAMP: ' . $timestamp,
            'Content-Type: application/json',
        ];

        $body = json_encode([
            'partnerServiceId' => $this->partnerServiceId,
            'customerNo' => $this->customerNo,
            'brivaNo' => '123456789012345',
            'amount' => 100000,
            'description' => 'Test Payment',
            'expiredDate' => date('Y-m-d H:i:s', strtotime('+1 day'))
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }

    public function getBrivaStatus($brivaNo)
    {
        $token = $this->getToken();
        $endpoint = "/snap/v1.0/briva/$brivaNo";
        $fullUrl = $this->url . $endpoint;

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }

    public function cancelBriva($brivaNo)
    {
        $token = $this->getToken();
        $endpoint = "/snap/v1.0/briva/$brivaNo";
        $fullUrl = $this->url . $endpoint;

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }
}
