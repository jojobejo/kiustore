<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['briva'] = [
    'url'               => 'https://sandbox.partner.api.bri.co.id',
    'partner_service_id' => '22123',
    'customer_no'       => '00218322',
    'client_id'         => 'APRGrJBHviW0cLSKZlJDZ4AHXXW9JAki',
    'client_secret'     => 'oSsY5SM5svjj2mY9',
    'privateKey'        => file_get_contents(FCPATH . 'key/private.pem')
];
