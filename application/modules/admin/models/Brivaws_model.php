<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Brivaws_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    private $institutionCode = "   22123"; // Ganti dengan kode institusi Anda
    private $brivaNo = "28138102"; // Ganti dengan nomor BRIVA Anda
    private $apiKey = "oiH80rAKywP21AHGRHEtGz34xRE7"; // Ganti dengan API Key Anda

    public function createVA($data)
    {
        $url = "https://sandbox.partner.api.bri.co.id/v1/briva";

        $payload = json_encode([
            "institutionCode" => $this->institutionCode,
            "brivaNo" => $this->brivaNo,
            "custCode" => $data['custCode'],
            "nama" => $data['nama'],
            "amount" => $data['amount'],
            "keterangan" => $data['keterangan'],
            "expiredDate" => date('Y-m-d H:i:s', strtotime("+1 day"))
        ]);

        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->apiKey
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
