<?php defined('BASEPATH') or exit('No direct script access allowed');

class brivaws_lib
{
    protected $CI;
    private $client_id;
    private $url;
    private $privateKey;
    private $secret_key;
    private $partnerServiceId;
    private $expartnerid;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('session');

        date_default_timezone_set("Asia/Jakarta");

        $this->CI->load->model(array(
            'order_model' => 'order',
            'payment_model' => 'payment'
        ));

        $this->client_id    = 'RVGRlE9qZ6JWXo15soVDmWGJHwyXZIw6';

        $keyString = file_get_contents(FCPATH . 'key/private.pem');
        $this->privateKey = openssl_pkey_get_private($keyString);

        if (!$this->privateKey) {
            die("Private key tidak valid atau tidak bisa dibuka!");
        }

        $this->url          = 'https://partner.api.bri.co.id';
        $this->secret_key   = 'FLnBAZ5Di1I5GqfS';
        $this->partnerServiceId = '   91118';
        $this->expartnerid = 'KARISMA';
    }

    public function createVa($customerNo, $custname, $tot_price, $trid)
    {
        $patch = '/snap/v1.0/transfer-va/create-va';
        $fullUrl = $this->url . $patch;
        $method = 'POST';
        $timestamp  = gmdate('Y-m-d\TH:i:s.000\Z');
        $token = $this->getToken();

        $body = array(
            'partnerServiceId'   => $this->partnerServiceId,
            'customerNo'         => $customerNo,
            'virtualAccountNo'   => $this->partnerServiceId . $customerNo,
            'virtualAccountName' => $custname,
            'totalAmount'        => array(
                'value'     => number_format($tot_price, 2, '.', ''),
                'currency'  => 'IDR'
            ),
            'expiredDate'        => date('c', strtotime('+1 day')),
            'trxId'              => $trid,
            'additionalInfo'     => array('description' => 'UJI COBA')
        );

        return $this->curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body);
    }
    function getToken()
    {
        $patch = '/snap/v1.0/access-token/b2b';
        $fullUrl = $this->url . $patch;
        $timestamp = date('c');

        $headers = array(
            'X-SIGNATURE:' . $this->asymmetricSignature($this->client_id, $timestamp),
            'X-CLIENT-KEY:' . $this->client_id,
            'X-TIMESTAMP:' . $timestamp,
            'Content-Type:application/json',
        );

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
        $this->echoPre('-- create token --');
        $this->echoPre($response);

        // Cek response valid JSON
        $token = is_string($response) ? json_decode($response, true) : NULL;

        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', "getToken() gagal decode JSON. Response mentah: " . $response);
            return false;
        }

        // Cek accessToken ada
        if (!isset($token['accessToken'])) {
            log_message('error', "getToken() gagal, tidak ada accessToken. Response: " . $response);
            return false;
        }

        return $token['accessToken'];
    }

    function curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, $remark = 'API Request')
    {


        $headers = array(
            'Authorization:Bearer ' . $token,
            'X-TIMESTAMP:' . $timestamp,
            'X-SIGNATURE:' . $this->symmetricSignature($method, $patch, $body, $timestamp, $token),
            'Content-Type:application/json',
            'X-PARTNER-ID:' . $this->expartnerid,
            'CHANNEL-ID:00001',
            'X-EXTERNAL-ID:' . rand(100000000, 999999999)
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        #curl_setopt($ch, CURLOPT_HEADER,true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        snapResponseDebug($response, $remark);

        return $response;
    }

    function symmetricSignature($method, $path, $body, $timestamp, $accessToken)
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

        $signature = hash_hmac('sha512', $stringToSign, $this->secret_key, true);

        return base64_encode($signature);
    }

    function asymmetricSignature($client_id, $timestamp)
    {

        $stringToSign = $client_id . '|' . $timestamp;
        $signature = "";
        openssl_sign($stringToSign, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($signature);
    }

    function echoPre($var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}
