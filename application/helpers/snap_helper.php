<?php
if (!function_exists('snapResponseDebug')) {
    /**
     * Cetak response API SNAP BI ke log + layar
     *
     * @param string|array $response JSON string atau array hasil decode
     * @param string $context pesan tambahan untuk log (opsional)
     */
    function snapResponseDebug($response, $context = 'SNAP API Response')
    {
        // kalau masih string, coba decode
        if (is_string($response)) {
            $decoded = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data = $decoded;
            } else {
                $data = ['raw' => $response];
            }
        } else {
            $data = $response;
        }

        $code    = isset($data['responseCode']) ? $data['responseCode'] : 'UNKNOWN';
        $message = isset($data['responseMessage']) ? $data['responseMessage'] : 'No message';

        // simpan ke log CodeIgniter
        log_message('debug', "$context => Code: $code | Message: $message");

        // cetak ke layar (development mode)
        echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
        echo "<strong>$context</strong><br>";
        echo "Code: <b>$code</b><br>";
        echo "Message: <b>$message</b><br>";
        echo "<pre style='background:#f9f9f9; padding:10px;'>" . print_r($data, true) . "</pre>";
        echo "</div>";
    }
}
