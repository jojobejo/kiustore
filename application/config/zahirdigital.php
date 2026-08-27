<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['zahir_stockready_enabled'] = FALSE;
$config['zahir_stockready_url'] = 'https://10.10.10.12/zahirdigital/SALES/GLOBAL/stockready_api.php';
$config['zahir_stockready_token'] = 'karisma-zahir-stock-20260827';
$config['zahir_stockready_username'] = '';
$config['zahir_stockready_password'] = '';
$config['zahir_stockready_timeout'] = 5;
$config['zahir_stockready_unavailable_message'] = 'Integrasi Stock Zahir Digital sedang dinonaktifkan di server ini karena sumber data berada di jaringan lokal/internal. Aktifkan hanya pada server yang memiliki akses LAN/VPN ke Zahir Digital.';
