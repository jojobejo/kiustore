<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Briva_lib {

    private $clientID;
    private $clientSecret;
    private $endpoint;
    private $institutionCode;
    private $brivaNo;

    public function __construct() {
        // Inisialisasi parameter berdasarkan data dari BRI atau konfigurasi Anda
        $this->clientID        = 'YOUR_CLIENT_ID';
        $this->clientSecret    = 'YOUR_CLIENT_SECRET';
        $this->endpoint        = 'https://sandbox.partner.api.bri.co.id'; // gunakan URL Sandbox/Production sesuai kebutuhan
        $this->institutionCode = 'J104408'; // contoh kode institusi
        $this->brivaNo         = '77777';    // nomor BRIVA sesuai yang diberikan
    }

    /**
     * Fungsi untuk membuat Virtual Account (Create VA)
     */
    public function create_va($data) {
        $url = $this->endpoint . '/snap/v1.0/transfer-va/create-va';

        // Header sesuai dokumentasi BRIVA WS SNAP BI
        $headers = [
            'Authorization: Bearer ' . $this->get_access_token(), // fungsi get_access_token() harus mengembalikan token valid
            'X-TIMESTAMP: ' . gmdate("Y-m-d\TH:i:s\Z"), // contoh timestamp dalam format ISO8601 (UTC)
            'X-SIGNATURE: ' . $this->generate_signature($data, $url), // fungsi generate_signature() untuk menghasilkan signature HMAC_SHA512
            'Content-Type: application/json',
            'X-PARTNER-ID: ' . $this->brivaNo,
            'CHANNEL-ID: 5',
            'X-EXTERNAL-ID: 123456789' // contoh nilai, sesuaikan dengan ketentuan
        ];

        $payload = json_encode($data);

        // Menggunakan cURL untuk melakukan POST request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Opsional: untuk debugging aktifkan curl_setopt($ch, CURLOPT_VERBOSE, true);
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ['status' => $http_status, 'response' => json_decode($result, true)];
    }

    /**
     * Contoh fungsi untuk mendapatkan access token (implementasi sesuaikan dengan mekanisme OAuth yang diberikan BRI)
     */
    private function get_access_token() {
        // Implementasikan request untuk mendapatkan token akses berdasarkan Client ID/Secret
        // Contoh: mengembalikan token hardcode untuk testing
        return 'YOUR_ACCESS_TOKEN';
    }

    /**
     * Fungsi untuk menghasilkan signature menggunakan algoritma HMAC_SHA512
     */
    private function generate_signature($data, $url) {
        // Misal: gabungkan method, URL, token, body (dalam bentuk hash) dan timestamp
        // Format stringToSign sesuai dokumentasi, contohnya:
        $httpMethod = 'POST';
        $accessToken = $this->get_access_token();
        $timestamp = gmdate("Y-m-d\TH:i:s\Z");
        // Minify body JSON dan hash SHA256
        $body = json_encode($data, JSON_UNESCAPED_SLASHES);
        $bodyHash = strtolower(hash('sha256', $body));

        $stringToSign = $httpMethod . ':' . $url . ':' . $accessToken . ':' . $bodyHash . ':' . $timestamp;

        // Menghasilkan signature menggunakan HMAC_SHA512 dengan clientSecret sebagai key
        $signature = hash_hmac('sha512', $stringToSign, $this->clientSecret);
        return $signature;
    }
}
