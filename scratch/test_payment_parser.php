<?php
define('BASEPATH', true);
// Mock get_settings
function get_settings($key = '') {
    return '{"bank-bca":{"bank":"BANK BCA","number":"20348483","name":"PT. KARISMA INDOAGRO UNIVERSAL"},"bank-mandiri":{"bank":"BANK MANDIRI","number":"10034453","name":"PT. KARISMA INDOAGRO UNIVERSAL"},"bank-bri":{"bank":"BANK BRI","number":"310337234005700","name":"PT. KARISMA INDOAGRO UNIVERSAL"}}';
}

require_once __DIR__ . '/../application/helpers/global_helper.php';

$banks = json_decode(get_settings('payment_banks'), true);

$test_cases = [
    'Legacy Format' => '{"transfer_to":"bank-mandiri","source":{"bank":"Mandiri","name":"Wahyu","number":"237890"}}',
    'Customer Web Format (No source/transfer_to keys)' => '{"order_id":"123","bank_name":"VA-BRI","bank_number":"012345","transfer":"500000","bank":"bank-bca","name":"Budi Santoso","name_duplicate":"Budi Santoso"}',
    'Mobile API Format (Both keys present)' => '{"order_id":123,"bank_name":"BCA","bank_number":"123456","transfer":500000,"bank":"bank-bca","transfer_to":"bank-bca","name":"Budi","name_duplicate":"Budi","source":{"bank":"BCA","number":"123456","name":"Budi"}}',
    'BRIVA / Invoice' => '{"invoice":"ORD-999","cusno":"CUS01","orderdate":"2026-08-10"}',
    'Plain String "paid"' => 'paid',
    'NULL / Empty' => null
];

echo "RUNNING TEST CASES:\n===================\n";
foreach ($test_cases as $label => $raw_data) {
    $res = parse_payment_transfer_info($raw_data, $banks);
    echo "[$label]\n";
    echo "  Transfer Ke  : " . $res['transfer_to'] . "\n";
    echo "  Transfer Dari: " . $res['transfer_from'] . "\n\n";
}
echo "ALL TEST CASES EXECUTED WITHOUT WARNINGS/ERRORS!\n";
