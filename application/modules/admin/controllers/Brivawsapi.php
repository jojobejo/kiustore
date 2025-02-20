<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Brivawsapi extends CI_Controller
{
    private $client_id = 'APRGrJBHviW0cLSKZlJDZ4AHXXW9JAki';
    private $secret_key = 'oSsY5SM5svjj2mY9';
    private $url = 'https://sandbox.partner.api.bri.co.id';
    private $privateKey;
    private $xPartnerId = 'kuionline'; // Generated by BRI
    private $partnerServiceId = '22123'; // Generated by BRI
    private $customerNo = '00218322'; // Generated by partner
    private $idcus = '111'; // Generated by partner

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('briva');
        $this->load->config('briva');

        $this->load->model('Brivaws_model');
        $this->privateKey = file_get_contents(FCPATH . 'key/private.pem');
    }

    public function create_va()
    {
        $endpoint = '/snap/v1.0/transfer-va/create-va';
        $fullUrl = $this->url . $endpoint;
        $timestamp = gmdate("Y-m-d\TH:i:s\Z");

        $token = getToken();
        if (!$token) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to get token']));
        }

        $body = [
            'partnerServiceId'   => $this->partnerServiceId,
            'customerNo'         => $this->partnerServiceId . $this->customerNo,
            'virtualAccountNo'   => $this->partnerServiceId . $this->customerNo . $this->idcus,
            'virtualAccountName' => 'kuionline',
            'totalAmount'        => [
                'value'     => '11000.00',
                'currency'  => 'IDR'
            ],
            'expiredDate'        => date('c', strtotime('2024-06-30 23:00')),
            'trxId'              => 'trx' . time(),
            'additionalInfo'     => [
                'description' => 'Keterangan'
            ]
        ];

        $response = curl_request($fullUrl, $token, 'POST', $body);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function generate_signature()
    {
        $client_id = $this->input->post('X-CLIENT-KEY');
        $timestamp = $this->input->post('X-TIMESTAMP');

        $data = $client_id . "|" . $timestamp;
        openssl_sign($data, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);
        $signature_base64 = base64_encode($signature);

        header('Content-Type: application/json');
        echo json_encode(['signature' => $signature_base64]);
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

        $body = json_encode([
            'grantType' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->secret_key
        ]);

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

        if ($httpCode == 200) {
            echo json_encode([
                'status' => 'success',
                'accessToken' => $result['accessToken'],
                'tokenType' => $result['tokenType'],
                'expiresIn' => $result['expiresIn'] . " seconds (" . round($result['expiresIn'] / 60, 2) . " minutes)"
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => $result
            ]);
        }
    }
}
