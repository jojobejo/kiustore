<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BRI_model extends CI_Model
{

    private $client_id;
    private $client_secret;
    private $base_url;

    public function __construct()
    {
        parent::__construct();

        $this->client_id = $this->config->item('bri_client_id');
        $this->client_secret = $this->config->item('bri_client_secret');
        $this->base_url = $this->config->item('bri_base_url');
        
    }

    public function getAccessToken()
    {
        $url = $this->base_url . "/oauth/client_credential/accesstoken";
        $headers = [
            "Content-Type: application/x-www-form-urlencoded"
        ];
        $body = "client_id={$this->client_id}&client_secret={$this->client_secret}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function generateSignature($path, $access_token, $timestamp)
    {
        $api_key = $this->config->item('bri_api_key');
        $payload = "path={$path}&verb=GET&token=Bearer {$access_token}&timestamp={$timestamp}&body=";

        $signature = hash_hmac('sha256', $payload, $api_key, true);
        return base64_encode($signature);
    }
}
