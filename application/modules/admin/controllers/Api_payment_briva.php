<?php
defined('BASEPATH') or exit('No direct script access allowed');

class api_payment_briva extends CI_Controller
{
    private $client_id;
    private $url;
    private $privateKey;

    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set("Asia/Jakarta");

        $this->load->model(array(
            'order_model' => 'order',
            'payment_model' => 'payment'
        ));

        verify_session('admin');

        $this->client_id    = 'RVGRlE9qZ6JWXo15soVDmWGJHwyXZIw6';

        $keyString = file_get_contents(FCPATH . 'key/private.pem');
        $this->privateKey = openssl_pkey_get_private($keyString);

        if (!$this->privateKey) {
            die("Private key tidak valid atau tidak bisa dibuka!");
        }

        $this->url          = 'https://partner.api.bri.co.id';
        $this->secret_key   = 'FLnBAZ5Di1I5GqfS';
        $this->partnerServiceId = '   91118';
    }

    public function index()
    {
        $params['title']    = 'Payment VIA BRIVA-API';
        $data['briva']      = $this->payment->payment_bri();

        $this->load->view('header', $params);
        $this->load->view('payments/brivapayments', $data);
        $this->load->view('footer');
    }

    function inquiryStatusVa()
    {
        global $url, $partnerServiceId, $customerNo;

        $patch = '/snap/v1.0/transfer-va/status';
        $fullUrl = $url . $patch;
        $method = 'POST';
        $timestamp = date('c');
        $token = getToken();

        $body = array(
            'partnerServiceId'  => $partnerServiceId,
            'customerNo'        => $customerNo,
            'virtualAccountNo'  => $partnerServiceId . $customerNo,
            'inquiryRequestId'  => 'trx12345'
        );

        curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, '-- inquiry status va --');
    }

    function deleteVa()
    {
        global $url, $partnerServiceId, $customerNo;

        $patch = '/snap/v1.0/transfer-va/delete-va';
        $fullUrl = $url . $patch;
        $method = 'DELETE';
        $timestamp = date('c');
        $token = getToken();

        $body = array(
            'partnerServiceId'  => $partnerServiceId,
            'customerNo'        => $customerNo,
            'virtualAccountNo'  => $partnerServiceId . $customerNo,
        );

        curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, '-- delete va --');
    }

    function inquiryVa()
    {
        global $url, $partnerServiceId, $customerNo;

        $patch = '/snap/v1.0/transfer-va/inquiry-va';
        $fullUrl = $url . $patch;
        $method = 'POST';
        $timestamp = date('c');
        $token = getToken();


        $body = array(
            'partnerServiceId'  => $partnerServiceId,
            'customerNo'        => $customerNo,
            'virtualAccountNo'  => $partnerServiceId . $customerNo,
            'trxId'             => 'trx12346', // di generate oleh partner 
        );

        curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, '-- inquiry va --');
    }

    function updateStatusVa()
    {
        global $url, $partnerServiceId, $customerNo;

        $patch = '/snap/v1.0/transfer-va/update-status';
        $fullUrl = $url . $patch;
        $method = 'PUT';
        $timestamp = date('c');
        $token = getToken();

        $body = array(
            'partnerServiceId'  => $partnerServiceId,
            'customerNo'        => $customerNo,
            'virtualAccountNo'  => $partnerServiceId . $customerNo,
            'trxId'             => 'trx12346', // di generate oleh partner 
            'paidStatus'        => 'Y',
        );
        curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, '-- update status va --');
    }

    function updateVa()
    {
        global $url, $partnerServiceId, $customerNo;

        $patch = '/snap/v1.0/transfer-va/update-va';
        $fullUrl = $url . $patch;
        $method = 'PUT';
        $timestamp = date('c');
        $token = getToken();

        $body = array(
            'partnerServiceId'  => $partnerServiceId,
            'customerNo'        => $customerNo,
            'virtualAccountNo'  => $partnerServiceId . $customerNo,
            'virtualAccountName' => 'tes va ite malang',
            'trxId'             => 'trx12346', // di generate oleh partner 
            'totalAmount'       => array(
                'value'     => '20000.00',
                'currency'  => 'IDR'
            ),
            'expiredDate'       => date('c', strtotime('2024-06-30 23:00')),
            'additionalInfo'    => array(
                'description'   => 'keterangan'
            )
        );
        #echoPre($body);die;
        curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, '-- update va --');
    }

    public function preview_briva()
    {
        $params['title']    = 'Preview - BRIVA';
        $data['cust_no'] = $this->input->post('cust_no');
        $data['custname'] = $this->input->post('custname');
        $data['totprice'] = $this->input->post('totprice');
        $data['transaksi_all'] = $this->input->post('transaksi_all');

        $this->load->view('header', $params);
        $this->load->view('payments/createva_preview', $data);
        $this->load->view('footer');
    }

    function createVa()
    {
        $patch = '/snap/v1.0/transfer-va/create-va';
        $fullUrl = $this->url . $patch;
        $method = 'POST';
        $timestamp  = gmdate('Y-m-d\TH:i:s.000\Z');
        $token = $this->getToken();

        $customerNo  = $this->input->post('cust_no');
        $custname    = $this->input->post('custname');
        $tot_price   = $this->input->post('totprice');
        $trid        = $this->input->post('transaksi_all');

        $body = array(
            'partnerServiceId'  => $this->partnerServiceId,
            'customerNo'        => $customerNo,
            'virtualAccountNo'  => $this->partnerServiceId . $customerNo,
            'virtualAccountName' => $custname,
            'totalAmount'       => array(
                'value'     => number_format($tot_price, 2, '.', ''),
                'currency'  => 'IDR'
            ),
            'expiredDate'       => date('c', strtotime('2025-08-30 23:00')),
            'trxId'             => $trid,
            'additionalInfo'    => array(
                'description'   => 'UJI COBA'
            )
        );

        $response = $this->curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body);

        header('Content-Type: application/json');
        echo $response;
    }

    function getToken()
    {
        global $url, $client_id;

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
        $token = json_decode($response, true);

        $this->echoPre('-- create token --');
        $this->echoPre($response);

        // Tambahan cek || Cek apakah response valid JSON
        $token = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', "getToken() gagal decode JSON. Response mentah: " . $response);
            return false;
        }

        // Cek apakah accessToken ada
        if (!isset($token['accessToken'])) {
            log_message('error', "getToken() gagal, tidak ada accessToken. Response: " . $response);
            return false;
        }

        return $token['accessToken'];
    }

    function curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, $remark = 'API Request')
    {
        global $xPartnerId;

        $headers = array(
            'Authorization:Bearer ' . $token,
            'X-TIMESTAMP:' . $timestamp,
            'X-SIGNATURE:' . $this->symmetricSignature($method, $patch, $body, $timestamp, $token),
            'Content-Type:application/json',
            'X-PARTNER-ID:' . $xPartnerId,
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
        global $client_secret; //Consumer Secret

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

        $signature = hash_hmac('sha512', $stringToSign, $this->, true);

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
