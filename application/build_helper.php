<?php

    date_default_timezone_set("Asia/Jakarta");
    
    $url = 'https://sandbox.partner.api.bri.co.id';

    $privateKey = file_get_contents(FCPATH . 'key/private.pem');
    
    $client_secret = trim('oSsY5SM5svjj2mY9');
    $client_id = trim('APRGrJBHviW0cLSKZlJDZ4AHXXW9JAki');
    $xPartnerId = 'kuionline'; 
    $partnerServiceId = trim('22123'); 
    $customerNo = trim('00218322');
    
    function createVa(){
        global $url, $partnerServiceId, $customerNo;
        
        $patch = '/snap/v1.0/transfer-va/create-va';
        $fullUrl = $url . $patch;
        $method = 'POST';
        $timestamp = date('c');
        $token = getToken();
    
        if (!$token) {
            die("Failed to retrieve access token");
        }
    
        $body = [
            'partnerServiceId'  => $partnerServiceId,
            'customerNo'        => $customerNo,
            'virtualAccountNo'  => $partnerServiceId . $customerNo,
            'virtualAccountName'=> 'tes lagi ite malang',
            'totalAmount'       => [
                'value'     => '11000.00',
                'currency'  => 'IDR'
            ],
            'expiredDate'       => date('c', strtotime('2024-06-30 23:00')),
            'trxId'             => 'trx12346',
            'additionalInfo'    => [
                'description' => 'keterangan'
            ]    
        ];
        
        curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, '-- inquiry va --');
    }
    
    function getToken(){
        global $url, $client_id;
        
        $patch = '/snap/v1.0/access-token/b2b';
        $fullUrl = $url . $patch;
        $timestamp = date('c');
        
        $headers = [
            'X-SIGNATURE:' . asymmetricSignature($client_id, $timestamp),
            'X-CLIENT-KEY:' . $client_id,
            'X-TIMESTAMP:' . $timestamp,
            'Content-Type:application/json',
        ];
    
        $body = json_encode(['grantType' => 'client_credentials']);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($httpCode != 200) {
            die("Error fetching token: " . $response);
        }
    
        $tokenData = json_decode($response, true);
        return $tokenData['accessToken'] ?? null;
    }
    
    function curlEndpoint($fullUrl, $token, $timestamp, $method, $patch, $body, $remark){
        global $xPartnerId;
        
        $headers = [
            'Authorization:Bearer ' . $token,
            'X-TIMESTAMP:' . $timestamp,
            'X-SIGNATURE:' . symmetricSignature($method, $patch, $body, $timestamp, $token),
            'Content-Type:application/json',
            'X-PARTNER-ID:' . $xPartnerId,
            'CHANNEL-ID:00001',
            'X-EXTERNAL-ID:' . rand(100000000, 999999999)
        ];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($httpCode != 200) {
            die("Error in API request: " . $response);
        }
    
        echo "<pre>$remark</pre>";
        echo "<pre>" . print_r(json_decode($response, true), true) . "</pre>";
    }
    
    function symmetricSignature($method, $path, $body, $timestamp, $accessToken){
        global $client_secret;
        
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
        
        $signature = hash_hmac('sha512', $stringToSign, $client_secret, true);
        return base64_encode($signature);
    }
    
    function asymmetricSignature($client_id, $timestamp){
        global $privateKey;
        
        $stringToSign = $client_id . '|' . $timestamp;
        $signature = "";
        
        if (!openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            die("Failed to generate signature");
        }
        
        return base64_encode($signature);
    }
