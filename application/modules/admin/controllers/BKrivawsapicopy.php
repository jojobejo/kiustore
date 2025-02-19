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

    public function __construct()
    {
        parent::__construct();
        $privateKeyPath = FCPATH . 'key/private.pem';

        if (!file_exists($privateKeyPath)) {
            log_message('error', 'Private Key not found!');
            show_error('Private Key not found!', 500);
        }

        $this->privateKey = file_get_contents($privateKeyPath);
    }

    public function getToken()
    {
        $endpoint = '/snap/v1.0/access-token/b2b';
        $fullUrl = $this->url . $endpoint;
        $timestamp = gmdate("Y-m-d\TH:i:s\Z");

        $signature = $this->generate_asymmetric_signature($this->client_id, $timestamp);
        if (!$signature) {
            log_message('error', 'Failed to generate signature');
            echo json_encode(['status' => 'error', 'message' => 'Failed to generate signature']);
            return;
        }

        $headers = [
            'X-SIGNATURE: ' . $signature,
            'X-CLIENT-KEY: ' . $this->client_id,
            'X-TIMESTAMP: ' . $timestamp,
            'Content-Type: application/json',
        ];

        // $body = ['grantType' => 'client_credentials'];
        $body = array(
            'grantType' => 'client_credentials'
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
            echo json_encode([
                'status' => 'success',
                'access_token' => $token['access_token']
            ]);
        } else {
            log_message('error', 'Failed to get token: ' . json_encode($token));
            echo json_encode([
                'status' => 'error',
                'message' => '$token'
            ]);
        }
    }

    private function generate_asymmetric_signature($client_id, $timestamp)
    {
        $data = $client_id . "|" . $timestamp;

        if (!$this->privateKey) {
            return null;
        }

        $signature = '';
        $keyResource = openssl_pkey_get_private($this->privateKey);

        if (!$keyResource) {
            return null;
        }

        if (!openssl_sign($data, $signature, $this->privateKey, OPENSSL_ALGO_SHA256)) {
            return null;
        }

        return base64_encode($signature);
    }

    public function debugSignature()
    {
        $client_id = $this->client_id;
        $timestamp = gmdate("Y-m-d\TH:i:s\Z");
        $stringToSign = $client_id . "|" . $timestamp;
        $signature = $this->generate_asymmetric_signature($client_id, $timestamp);

        echo json_encode([
            "stringToSign" => $stringToSign,
            "signature" => $signature
        ]);
    }

    public function verifySignature()

    {
        $client_id = $this->client_id;
        $timestamp = gmdate("Y-m-d\TH:i:s\Z");
        $stringToSign = $client_id . "|" . $timestamp;
        $signature = $this->generate_asymmetric_signature($client_id, $timestamp);

        $publicKeyPath = FCPATH . 'key/public.pem';
        if (!file_exists($publicKeyPath)) {
            echo json_encode(['status' => 'error', 'message' => 'Public Key not found!']);
            return;
        }

        $publicKey = file_get_contents($publicKeyPath);
        $keyResource = openssl_pkey_get_public($publicKey);

        $isValid = openssl_verify($stringToSign, base64_decode($signature), $keyResource, OPENSSL_ALGO_SHA256);

        echo json_encode([
            "stringToSign" => $stringToSign,
            "signature" => $signature,
            "verification" => $isValid ? "Valid Signature" : "Invalid Signature"
        ]);
    }
}
