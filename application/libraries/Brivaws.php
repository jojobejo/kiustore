<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Brivaws
{
    private $client_id;
    private $url;
    private $privateKey;
    private $secret_key;
    private $partnerServiceId;
    private $expartnerid;

    public function __construct($config = array())
    {
        $this->CI = &get_instance();

        date_default_timezone_set("Asia/Jakarta");

        $this->client_id        = isset($config['client_id']) ? $config['client_id'] : 'RVGRlE9qZ6JWXo15soVDmWGJHwyXZIw6';
        $this->url              = isset($config['url']) ? $config['url'] : 'https://partner.api.bri.co.id';
        $this->secret_key       = isset($config['secret_key']) ? $config['secret_key'] : 'FLnBAZ5Di1I5GqfS';
        $this->partnerServiceId = isset($config['partnerServiceId']) ? $config['partnerServiceId'] : '   91118';
        $this->expartnerid      = isset($config['expartnerid']) ? $config['expartnerid'] : 'KARISMA';

        $keyPath = isset($config['private_key_path']) ? $config['private_key_path'] : FCPATH . 'key/private.pem';
        $keyString = file_get_contents($keyPath);
        $this->privateKey = openssl_pkey_get_private($keyString);

        if (!$this->privateKey) {
            log_message('error', 'Private key tidak valid!');
            throw new Exception("Private key tidak valid!");
        }
    }

    public function createVa($customerNo, $custname, $tot_price, $trxId)
    {
        $patch = '/snap/v1.0/transfer-va/create-va';
        $fullUrl = $this->url . $patch;
        $method = 'POST';
        $timestamp  = gmdate('Y-m-d\TH:i:s.000\Z');
        $token = $this->getToken();

        $body = array(
            'partnerServiceId'  => $this->partnerServiceId,
            'customerNo'        => $customerNo,
            'virtualAccountNo'  => $this->partnerServiceId . $customerNo,
            'virtualAccountName' => $custname,
            'totalAmount'       => array(
                'value'     => number_format($tot_price, 2, '.', ''),
                'currency'  => 'IDR'
            ),
            'expiredDate'       => date('c', strtotime('+15 minutes')),
            'trxId'             => $trxId,
            'additionalInfo'    => array(
                'description'   => 'KARISMAONLINE#' . $trxId
            )
        );

        return $this->curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, 'create-va');
    }

    function updateVa($customerNo, $vaname, $vatrxid, $value)
    {
        $patch = '/snap/v1.0/transfer-va/update-va';
        $fullUrl = $this->url . $patch;
        $method = 'PUT';
        $timestamp = date('c');
        $token = $this->getToken();

        $body = array(
            'partnerServiceId'  => $this->partnerServiceId,
            'customerNo'        => $customerNo,
            'virtualAccountNo'  => $this->partnerServiceId . $customerNo,
            'virtualAccountName' => $vaname,
            'trxId'             => $vatrxid,
            'totalAmount'       => array(
                'value'     => $value,
                'currency'  => 'IDR'
            ),
            'expiredDate'       => date('c', strtotime('+15 minutes')),
            'additionalInfo'    => array(
                'description'   => 'KARISMAONLINE#' . $vatrxid
            )
        );
        return $this->curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, '-- update va --');
    }

    function updateStatusVa($customerNo, $vatrxid)
    {
        $patch = '/snap/v1.0/transfer-va/update-status';
        $fullUrl = $this->url . $patch;
        $method = 'PUT';
        $timestamp = date('c');
        $token = $this->getToken();

        $body = array(
            'partnerServiceId'  => $this->partnerServiceId,
            'customerNo'        => $customerNo,
            'virtualAccountNo'  => $this->partnerServiceId . $customerNo,
            'trxId'             => $vatrxid,
            'paidStatus'        => 'N',
        );
        return $this->curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, '-- update status va --');
    }

    function deleteVa($customerNo, $invoice)
    {

        $patch = '/snap/v1.0/transfer-va/delete-va';
        $fullUrl = $this->url . $patch;
        $method = 'DELETE';
        $timestamp = date('c');
        $token = $this->getToken();

        $body = array(
            'partnerServiceId'  => $this->partnerServiceId,
            'customerNo'        => $customerNo,
            'virtualAccountNo'  => $this->partnerServiceId . $customerNo,
            'trxId'             => $invoice
        );

        $this->curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, '-- delete va --');
    }

    function inquiryVa($customerNo, $trxId)
    {
        $patch = '/snap/v1.0/transfer-va/inquiry-va';
        $fullUrl = $this->url . $patch;
        $method = 'POST';
        $timestamp = date('c');
        $token = $this->getToken();

        $body = array(
            'partnerServiceId'  => $this->partnerServiceId,
            'customerNo'        => $customerNo,
            'virtualAccountNo'  => $this->partnerServiceId . $customerNo,
            'trxId'             => $trxId,
        );
        return $this->curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, '-- Inquiry VA --');
    }

    function inquiryStatusVa($customerNo, $invoice)
    {
        $patch = '/snap/v1.0/transfer-va/status';
        $fullUrl = $this->url . $patch;
        $method = 'POST';
        $timestamp = date('c');
        $token = $this->getToken();

        $body = array(
            'partnerServiceId'  => $this->partnerServiceId,
            'customerNo'        => $customerNo,
            'virtualAccountNo'  => $this->partnerServiceId . $customerNo,
            'inquiryRequestId'  => $invoice
        );

        return $this->curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, '-- inquiry status va --');
    }

    public function getToken()
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
        curl_close($ch);

        $token = json_decode($response, true);

        if (!isset($token['accessToken'])) {
            log_message('error', "getToken gagal. Response: " . $response);
            return false;
        }

        return $token['accessToken'];
    }

    private function curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, $remark = 'API Request')
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
        $response = curl_exec($ch);
        curl_close($ch);

        log_message('debug', $remark . ' Response: ' . $response);

        return $response;
    }

    private function symmetricSignature($method, $path, $body, $timestamp, $accessToken)
    {
        $hashBody = json_encode($body);
        $hashBody = hash('sha256', $hashBody);
        $signedBody = strtolower($hashBody);

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

    private function asymmetricSignature($client_id, $timestamp)
    {
        $stringToSign = $client_id . '|' . $timestamp;
        $signature = "";
        openssl_sign($stringToSign, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($signature);
    }
}
