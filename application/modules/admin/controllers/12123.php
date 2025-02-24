<?php
$url = 'https://sandbox.partner.api.bri.co.id';
$privateKey = file_get_contents('private_key_rsa.pem');
$client_secret = '5JwtO5v1EkO0yswO'; // Consumer Secret
$client_id = 'AGZJwGBUqlThM2XBzuLdA31qKzvujGKV'; // Consumer Key
$xPartnerId = 'ROMalang'; // Diberikan oleh BRI
$partnerServiceId = '22001'; // Diberikan oleh BRI
$customerNo = '00218322'; // Diberikan oleh Partner

function getToken($client_id, $privateKey, $url) {
    $patch = '/snap/v1.0/access-token/b2b';
    $fullUrl = $url . $patch;
    $timestamp = gmdate('Y-m-d\TH:i:s.000\Z'); // Format UTC

    $headers = array(
        'X-SIGNATURE: ' . asymmetricSignature($client_id, $timestamp, $privateKey),
        'X-CLIENT-KEY: ' . $client_id,
        'X-TIMESTAMP: ' . $timestamp,
        'Content-Type: application/json',
    );

    $body = array(
        'grant_type' => 'client_credentials' // Sesuai dokumentasi API
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

    if ($httpCode == 200 && isset($token['access_token'])) {
        return $token['access_token'];
    } else {
        return "Error: " . $response;
    }
}

function asymmetricSignature($client_id, $timestamp, $privateKey) {
    $stringToSign = $client_id . '|' . $timestamp;
    $signature = "";
    
    if (!openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
        throw new Exception("Failed to generate signature");
    }

    return base64_encode($signature);
}

// Panggil fungsi untuk mendapatkan token
$accessToken = getToken($client_id, $privateKey, $url);
echo "Access Token: " . $accessToken;
?>
