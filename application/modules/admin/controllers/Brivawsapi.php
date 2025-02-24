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

    public function getToken()
    {
        $patch = '/snap/v1.0/access-token/b2b';
        $fullUrl = $this->url . $patch;
        $timestamp = gmdate('Y-m-d\TH:i:s.000\Z'); // Format UTC

        $headers = array(
            'X-SIGNATURE: ' . $this->asymmetricSignature($this->client_id, $timestamp, $this->privateKey),
            'X-CLIENT-KEY: ' . $this->client_id,
            'X-TIMESTAMP: ' . $timestamp,
            'Content-Type: application/json',
        );

        $body = array(
            'grant_type' => 'client_credentials' // Sesuai dokumentasi API
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $token = json_decode($response, true);

        if ($httpCode == 200 && isset($token['access_token'])) {
            return $token['access_token'];
        } else {
            return "Error: " . $response;
        }
    }

    function asymmetricSignature($client_id, $timestamp, $privateKey)
    {
        $stringToSign = $client_id . '|' . $timestamp;
        $signature = "";

        if (!openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new Exception("Failed to generate signature");
        }

        return base64_encode($signature);
    }
}
