<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Openwa {

    protected $CI;

    private $base_url  = 'http://192.168.200.41:2785';
    private $api_key   = 'owa_k1_d97c9ab8faabbe4f14aac38858d4caa8fe9d5de9c789f5cfffdf863a0c543212';

    public function __construct(){
        $this->CI =& get_instance();
    }

    public function sendTemplate($session_id,$nomor_hp,$template_id,$template_name,$vars = []) {

        $nomor_hp = $this->normalizePhone($nomor_hp);

        if ($nomor_hp == '') {
            return [
                'success' => false,
                'message' => 'Nomor HP tidak valid'
            ];
        }

        if ($session_id == '') {
            return [
                'success' => false,
                'message' => 'Session ID tidak boleh kosong'
            ];
        }

        $url = $this->base_url.'/api/sessions/'.rawurlencode($session_id).'/messages/send-template';

        $payload = [
            'chatId'       => $nomor_hp . '@c.us',
            'templateId'   => $template_id,
            'templateName' => $template_name,
            'vars'         => $vars
        ];

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'x-api-key: ' . $this->api_key
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($error) {
            return [
                'success'   => false,
                'http_code' => $httpcode,
                'message'   => $error,
                'response'  => null
            ];
        }

        $result = json_decode($response, true);

        return [
            'success'   => ($httpcode >= 200 && $httpcode < 300),
            'http_code' => $httpcode,
            'message'   => ($httpcode >= 200 && $httpcode < 300) ? 'Pesan berhasil dikirim' : 'Gagal mengirim pesan',
            'response'  => $result
        ];
    }

    public function sendImage($session_id, $nomor_hp, $image_url, $caption = '') {

        $nomor_hp = $this->normalizePhone($nomor_hp);

        if ($nomor_hp == '') {
            return [
                'success' => false,
                'message' => 'Nomor HP tidak valid'
            ];
        }

        if ($session_id == '') {
            return [
                'success' => false,
                'message' => 'Session ID tidak boleh kosong'
            ];
        }

        if ($image_url == '') {
            return [
                'success' => false,
                'message' => 'URL gambar tidak boleh kosong'
            ];
        }

        $url = $this->base_url . '/api/sessions/' .
            rawurlencode($session_id) .
            '/messages/send-image';

        $payload = [
            'chatId'  => $nomor_hp . '@c.us',
            'url'     => $image_url,
            'caption' => $caption
        ];

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'x-api-key: ' . $this->api_key
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($error) {
            return [
                'success'   => false,
                'http_code' => $httpcode,
                'message'   => $error,
                'response'  => null
            ];
        }

        $result = json_decode($response, true);

        return [
            'success'   => ($httpcode >= 200 && $httpcode < 300),
            'http_code' => $httpcode,
            'message'   => ($httpcode >= 200 && $httpcode < 300)
                ? 'Gambar berhasil dikirim'
                : 'Gagal mengirim gambar',
            'response'  => $result
        ];
    }

    public function sendText($session_id, $nomor_hp, $message){
        $nomor_hp = $this->normalizePhone($nomor_hp);

        if ($nomor_hp == '') {
            return [
                'success' => false,
                'message' => 'Nomor HP tidak valid'
            ];
        }

        if ($session_id == '') {
            return [
                'success' => false,
                'message' => 'Session ID tidak boleh kosong'
            ];
        }

        if ($message == '') {
            return [
                'success' => false,
                'message' => 'Pesan tidak boleh kosong'
            ];
        }

        $url = $this->base_url . '/api/sessions/' .
            rawurlencode($session_id) .
            '/messages/send-text';

        $payload = [
            'chatId' => $nomor_hp . '@c.us',
            'text'   => $message
        ];

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'x-api-key: ' . $this->api_key
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($error) {
            return [
                'success'   => false,
                'http_code' => $httpcode,
                'message'   => $error,
                'response'  => null
            ];
        }

        $result = json_decode($response, true);

        return [
            'success'   => ($httpcode >= 200 && $httpcode < 300),
            'http_code' => $httpcode,
            'message'   => ($httpcode >= 200 && $httpcode < 300)
                ? 'Pesan berhasil dikirim'
                : 'Gagal mengirim pesan',
            'response'  => $result
        ];
    }

    public function sendTextChatid($session_id, $chat_id, $message){

        // Validasi Session ID
        if(empty($session_id)){
            return [
                'success' => false,
                'message' => 'Session ID tidak boleh kosong'
            ];
        }

        // Validasi Chat ID
        if(empty($chat_id)){
            return [
                'success' => false,
                'message' => 'Chat ID tidak boleh kosong'
            ];
        }

        // Validasi pesan
        if(empty($message)){
            return [
                'success' => false,
                'message' => 'Pesan tidak boleh kosong'
            ];
        }

        $url = $this->base_url . '/api/sessions/' .
            rawurlencode($session_id) .
            '/messages/send-text';

        $payload = [
            'chatId' => $chat_id,
            'text'   => $message
        ];

        $json_payload = json_encode(
            $payload,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        if($json_payload === false){
            return [
                'success' => false,
                'message' => 'Gagal membuat JSON payload',
                'response' => null
            ];
        }

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'x-api-key: ' . $this->api_key
            ],
            CURLOPT_POSTFIELDS     => $json_payload,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        // Error CURL
        if($error){
            return [
                'success'   => false,
                'http_code' => $httpcode,
                'message'   => $error,
                'response'  => null
            ];
        }

        // Decode response OpenWA
        $result = json_decode($response, true);

        return [
            'success'   => ($httpcode >= 200 && $httpcode < 300),
            'http_code' => $httpcode,
            'message'   => ($httpcode >= 200 && $httpcode < 300)
                ? 'Pesan berhasil dikirim'
                : 'Gagal mengirim pesan',
            'response'  => $result
        ];
    }

    public function sendImageChatid($session_id, $chat_id, $image_url, $caption = ''){

        if($session_id == ''){
            return [
                'success' => false,
                'message' => 'Session ID tidak boleh kosong'
            ];
        }

        if($chat_id == ''){
            return [
                'success' => false,
                'message' => 'Chat ID tidak boleh kosong'
            ];
        }

        if($image_url == ''){
            return [
                'success' => false,
                'message' => 'URL image tidak boleh kosong'
            ];
        }

        $url = $this->base_url . '/api/sessions/' .
            rawurlencode($session_id) .
            '/messages/send-image';

        $payload = [
            'chatId' => $chat_id,
            'url'    => $image_url,
            'caption'=> $caption
        ];

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'x-api-key: ' . $this->api_key
            ],
            CURLOPT_POSTFIELDS     => json_encode(
                $payload,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if($error){
            return [
                'success'   => false,
                'http_code' => $httpcode,
                'message'   => $error,
                'response'  => null
            ];
        }

        $result = json_decode($response, true);

        return [
            'success'   => ($httpcode >= 200 && $httpcode < 300),
            'http_code' => $httpcode,
            'message'   => ($httpcode >= 200 && $httpcode < 300)
                ? 'Image berhasil dikirim'
                : 'Gagal mengirim image',
            'response'  => $result
        ];
    }

    public function shareloc($session_id, $chat_id, $latitude, $longitude, $description = ''){
        // Validasi Session ID
        if(empty($session_id)){
            return [
                'success' => false,
                'message' => 'Session ID tidak boleh kosong'
            ];
        }

        // Validasi Chat ID
        if(empty($chat_id)){
            return [
                'success' => false,
                'message' => 'Chat ID tidak boleh kosong'
            ];
        }

        // Validasi Latitude
        if($latitude === '' || $latitude === null){
            return [
                'success' => false,
                'message' => 'Latitude tidak boleh kosong'
            ];
        }

        // Validasi Longitude
        if($longitude === '' || $longitude === null){
            return [
                'success' => false,
                'message' => 'Longitude tidak boleh kosong'
            ];
        }

        $url = $this->base_url . '/api/sessions/' .
            rawurlencode($session_id) .
            '/messages/send-location';

        $payload = [
            'chatId'    => $chat_id,
            'latitude'  => (float) $latitude,
            'longitude' => (float) $longitude
        ];

        if(!empty($description)){
            $payload['description'] = $description;
        }

        $json_payload = json_encode(
            $payload,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        if($json_payload === false){
            return [
                'success'   => false,
                'message'   => 'Gagal membuat JSON payload',
                'response'  => null
            ];
        }

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'x-api-key: ' . $this->api_key
            ],
            CURLOPT_POSTFIELDS     => $json_payload,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        // Error CURL
        if($error){
            return [
                'success'   => false,
                'http_code' => $httpcode,
                'message'   => $error,
                'response'  => null
            ];
        }

        // Decode response OpenWA
        $result = json_decode($response, true);

        return [
            'success'   => ($httpcode >= 200 && $httpcode < 300),
            'http_code' => $httpcode,
            'message'   => ($httpcode >= 200 && $httpcode < 300)
                ? 'Lokasi berhasil dikirim'
                : 'Gagal mengirim lokasi',
            'response'  => $result
        ];
    }

    private function normalizePhone($nomor){
        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        if ($nomor == '') {
            return '';
        }

        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        if (substr($nomor, 0, 2) !== '62') {
            $nomor = '62' . $nomor;
        }

        return $nomor;
    }
}