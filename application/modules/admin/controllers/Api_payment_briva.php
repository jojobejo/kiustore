<?php
defined('BASEPATH') or exit('No direct script access allowed');

class api_payment_briva extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Muat library BRIVA yang telah dibuat
        $this->load->helper('Briva_lib');
    }

    /**
     * Contoh fungsi untuk membuat Virtual Account
     */
    public function create_virtual_account()
    {
        // Data request yang sesuai dengan dokumentasi API BRIVA WS SNAP BI
        $data = [
            "partnerServiceId"   => "   77777", // Perhatikan padding jika diperlukan
            "customerNo"         => "20098106",
            "virtualAccountNo"   => "   7777720098106",
            "virtualAccountName" => "Tes Surya",
            "totalAmount"        => [
                "value"    => "10000.00",
                "currency" => "IDR"
            ],
            "expiredDate"        => "2024-02-28T22:38:25+07:00",
            "trxId"              => "abcdefgh1234",
            "additionalInfo"     => [
                "description" => "Keterangan pembuatan VA"
            ]
        ];

        $result = $this->briva_lib->create_va($data);
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
