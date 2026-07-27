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

// <?php
// defined('BASEPATH') or exit('No direct script access allowed');

// class Brivawsapi extends CI_Controller
// {

//     private $client_id;
//     private $url;
//     private $privateKey;
//     private $secret_key;
//     private $xPartnerId;

//     public function __construct()
//     {
//         parent::__construct();
//         $this->client_id    = 'APRGrJBHviW0cLSKZlJDZ4AHXXW9JAki';
//         $this->privateKey   = file_get_contents(FCPATH . 'key/private.pem');
//         $this->url          = 'https://sandbox.partner.api.bri.co.id';
//         $this->secret_key   = 'oSsY5SM5svjj2mY9';
//         $this->xpartnerid   = 'kuionline';
//         $this->partnerid    = '22123';
//     }

//     private function asymmetricSignature($client_id, $timestamp)
//     {
//         $stringToSign = $client_id . '|' . $timestamp;
//         $signature = "";
//         if (!openssl_sign($stringToSign, $signature, $this->privateKey, OPENSSL_ALGO_SHA256)) {
//             throw new Exception("Failed to generate signature");
//         }

//         return base64_encode($signature);
//     }

//     private function symmetricSignature($method, $path, $body, $timestamp, $accessToken)
//     {
//         $hashBody = json_encode($body);
//         $hashBody = hash('sha256', $hashBody);
//         $signedBody = strtolower($hashBody);

//         $stringToSign = implode(':', [
//             $method,
//             $path,
//             $accessToken,
//             $signedBody,
//             $timestamp
//         ]);

//         $signature = hash_hmac('sha512', $stringToSign, $this->secret_key, true);

//         return base64_encode($signature);
//     }

//     public function create_va()
//     {
//         $patch = '/snap/v1.0/transfer-va/create-va';
//         $fullurl = $this->url . $patch;
//         $method = 'POST';
//         $timestamp  = gmdate('Y-m-d\TH:i:s.000\Z');
//         $cust = '102030';

//         $token_response = $this->get_token();
//         $token_data = json_decode($token_response, true);

//         if (isset($token_data['status']) && $token_data['status'] === 'success') {
//             $access_token = $token_data['access_token'];

//             $body = array(
//                 'partnerServiceId'  => $this->client_id,
//                 'customerNo'        => $cust,
//                 'virtualAccountNo'  => $this->client_id . $cust,
//                 'virtualAccountName' => 'CUST01',
//                 'totalAmount'       => array(
//                     'value'     => '11000.00',
//                     'currency'  => 'IDR'
//                 ),
//                 'expiredDate'       => date('c', strtotime('2024-06-30 23:00')),
//                 'trxId'             => 'trx12346',
//                 'additionalInfo'    => array(
//                     'description'   => 'keterangan'
//                 )
//             );

//             $sysmetricsignature = $this->symmetricSignature($method, $patch, $body, $timestamp, $access_token);

//             $headers = array(
//                 'Authorization:Bearer ' . $access_token,
//                 'X-TIMESTAMP:' . $timestamp,
//                 'X-SIGNATURE:' . $sysmetricsignature,
//                 'Content-Type:application/json',
//                 'X-PARTNER-ID:' . $this->partnerid,
//                 'CHANNEL-ID:00001',
//                 'X-EXTERNAL-ID:' . rand(100000000, 999999999) // di generate oleh partner , random setiap hari
//             );
//             $bodycreate = array(
//                 'partnerServiceId'  => $this->client_id,
//                 'customerNo'        => $cust,
//                 'virtualAccountNo'  => $this->client_id . $cust,
//                 'virtualAccountName' => 'CUST01',
//                 'totalAmount'       => array(
//                     'value'     => '11000.00',
//                     'currency'  => 'IDR'
//                 ),
//                 'expiredDate'       => date('c', strtotime('2024-06-30 23:00')),
//                 'trxId'             => 'trx12346',
//                 'additionalInfo'    => array(
//                     'description'   => 'keterangan'
//                 )
//             );

//             $ch = curl_init();
//             curl_setopt($ch, CURLOPT_URL, $fullurl);
//             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
//             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//             curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($bodycreate));
//             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//             curl_setopt($ch, CURLOPT_TIMEOUT, 30);

//             $response = curl_exec($ch);
//             $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//             $curlError = curl_error($ch);
//             curl_close($ch);

//             if ($response === false) {
//                 return json_encode([
//                     "status" => "error",
//                     "message" => "cURL Error: " . $curlError
//                 ], JSON_PRETTY_PRINT);
//                 var_dump($response);
//                 exit;
//             }

//             $createva = json_decode($response, true);

//             if ($httpCode == 200) {
//                 header('Content-Type: application/json');
//                 return json_encode([
//                     "status" => "success",
//                     "message" => "VA telah terbuat"
//                 ], JSON_PRETTY_PRINT);
//                 var_dump($createva);
//                 exit;
//             } else {
//                 header('Content-Type: application/json');
//                 return json_encode([
//                     "status" => "error",
//                     "message" => "Gagal",
//                     "http_code" => $httpCode,
//                     "response" => $createva
//                 ], JSON_PRETTY_PRINT);
//                 var_dump($createva);
//                 exit;
//             }
//         } else {
//             $responses = [
//                 "status" => "error",
//                 "message" => "Gagal membuat Virtual Account, tidak bisa mendapatkan access token",
//                 "error_detail" => $token_data
//             ];
//         }

//         echo json_encode($responses, JSON_PRETTY_PRINT);
//     }

//     public function get_token()
//     {
//         $clientid   = $this->client_id;
//         $patch      = '/snap/v1.0/access-token/b2b';
//         $fullurl    = $this->url . $patch;
//         $timestamp  = gmdate('Y-m-d\TH:i:s.000\Z');

//         $signature = $this->asymmetricSignature($clientid, $timestamp);

//         $headers = array(
//             'X-SIGNATURE:' . $signature,
//             'X-CLIENT-KEY:' . $clientid,
//             'X-TIMESTAMP:' . $timestamp,
//             'Content-Type: application/json',
//         );

//         $body = json_encode(['grantType' => 'client_credentials']);

//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, $fullurl);
//         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//         curl_setopt($ch, CURLOPT_TIMEOUT, 30);

//         $response = curl_exec($ch);
//         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//         $curlError = curl_error($ch);
//         curl_close($ch);

//         if ($response === false) {
//             return json_encode([
//                 "status" => "error",
//                 "message" => "cURL Error: " . $curlError
//             ], JSON_PRETTY_PRINT);
//         }

//         $token = json_decode($response, true);

//         if ($httpCode == 200 && isset($token['accessToken'])) {
//             header('Content-Type: application/json');
//             return json_encode([
//                 "status" => "success",
//                 "access_token" => $token['accessToken']
//             ], JSON_PRETTY_PRINT);
//         } else {
//             header('Content-Type: application/json');
//             return json_encode([
//                 "status" => "error",
//                 "message" => "Failed to retrieve access token",
//                 "http_code" => $httpCode,
//                 "response" => $token
//             ], JSON_PRETTY_PRINT);
//         }
//     }
// }





// <?php
// defined('BASEPATH') or exit('No direct script access allowed');

// class Brivawsapi extends CI_Controller
// {
//     private $client_id = 'APRGrJBHviW0cLSKZlJDZ4AHXXW9JAki';
//     private $secret_key = 'oSsY5SM5svjj2mY9';
//     private $url = 'https://sandbox.partner.api.bri.co.id';
//     private $privateKey;
//     private $xPartnerId = 'kuionline'; // Generated by BRI
//     private $partnerServiceId = '22123'; // Generated by BRI
//     private $customerNo = '00218322'; // Generated by partner
//     private $idcus = '111'; // Generated by partner

//     public function __construct()
//     {
//         parent::__construct();

//         $this->privateKey = file_get_contents(FCPATH . 'key/private.pem');
//     }

//     private function generate_asymmetric_signature($client_id, $timestamp)
//     {
//         $data = $client_id . "|" . $timestamp;
//         openssl_sign($data, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);
//         return base64_encode($signature);
//     }

//     public function getToken()
//     {
//         $endpoint = '/snap/v1.0/access-token/b2b';
//         $fullUrl = $this->url . $endpoint;
//         $timestamp = gmdate("Y-m-d\TH:i:s\Z");

//         $headers = [
//             'X-SIGNATURE: ' . $this->generate_asymmetric_signature($this->client_id, $timestamp),
//             'X-CLIENT-KEY: ' . $this->client_id,
//             'X-TIMESTAMP: ' . $timestamp,
//             'Content-Type: application/json',
//         ];

//         $body = json_encode([
//             'grantType' => 'client_credentials'
//         ]);

//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, $fullUrl);
//         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//         $response = curl_exec($ch);
//         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//         curl_close($ch);

//         $result = json_decode($response, true);

//         if (!isset($result['accessToken'])) {
//             return ['status' => false, 'message' => 'Failed to retrieve token'];
//         }

//         if ($httpCode == 200) {

//             $data = [
//                 'status' => 'success',
//                 'accessToken' => $result['accessToken'],
//                 'tokenType' => $result['tokenType'],
//                 'expiresIn' => $result['expiresIn']
//             ];

//             $this->output
//                 ->set_content_type('application/json')
//                 ->set_output(json_encode($data));
//         } else {
//             echo json_encode([
//                 'status' => 'gagal mendapati token',
//             ]);
//             return [
//                 'status' => 'error',
//                 'message' => $result
//             ];
//         }
//     }
// }

