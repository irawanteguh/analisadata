<?php
    use LZCompressor\LZString;

    class bpjs{

        protected static $app;
        public static $clientid;
        public static $secretkey;
        public static $userkey;
        public static $baseurl;

        protected static function stringDecrypt($key, $string){
            $encrypt_method = 'AES-256-CBC';
            $key_hash       = hex2bin(hash('sha256', $key));
            $iv             = substr(hex2bin(hash('sha256', $key)), 0, 16);
            $output         = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
    
            return $output;
        }
    
        protected static function decompress($string){
            return LZString::decompressFromEncodedURIComponent($string);
        }

        protected static function getheader(){
            date_default_timezone_set('UTC');

            $timestamp        = strval(time()-strtotime('1970-01-01 00:00:00'));
            $data             = self::$clientid.'&'.$timestamp;
            $signature        = hash_hmac('sha256',$data,self::$secretkey,true);
            $encodedSignature = base64_encode($signature);
    
            $headers = [
                'X-cons-id'    => self::$clientid,
                'X-timestamp'  => $timestamp,
                'X-signature'  => $encodedSignature,
                'user_key'     => self::$userkey
            ];
    
            $formattedHeaders = [];
            foreach ($headers as $key => $value) {
                $formattedHeaders[] = $key . ': ' . $value;
            }

            return $formattedHeaders;
        }

        public static function init(){
            self::$app = &get_instance();
            self::$app->load->helper('curl');

            self::$clientid  = BPJS_CID;
            self::$secretkey = BPJS_CKEY;
            self::$userkey   = BPJS_USER_KEY;
            self::$baseurl   = BPJS_BASE_URL_VCLAIM;
        }

        public static function listrujukabydate($parameter1,$parameter2){
            $header = self::getheader();

            $responsecurl = curl([
                'url'     => self::$baseurl."/Rujukan/Keluar/List/tglMulai/".$parameter1."/tglAkhir/".$parameter2,
                'method'  => "GET",
                'header'  => $header,
                'body'    => "",
                'savelog' => false,
                'source'  => "LIST-RUJUKAN"
            ]);

            $result = json_decode($responsecurl,TRUE);

            if(intval($result['metaData']['code']) == 200){
                $restresponse = self::stringdecrypt(self::$clientid.self::$secretkey.str_replace("X-timestamp: ","",$header[1]),$result["response"]);
                $restresponse = self::decompress($restresponse);
                $restresponse = json_decode($restresponse, true);
    
                $result["metaData"]["code"]    = $result["metaData"]["code"];
                $result["metaData"]["message"] = $result["metaData"]["message"];
                $result["response"]            = $restresponse;
            }else{
                $result["metaData"]["code"]    = $result["metaData"]["code"];
                $result["metaData"]["message"] = $result["metaData"]["message"];
            }

            return $result;
        }

        public static function listrujukabynomorrujukan($parameter1){
            $header = self::getheader();

            $responsecurl = curl([
                'url'     => self::$baseurl."/Rujukan/Keluar/".$parameter1,
                'method'  => "GET",
                'header'  => $header,
                'body'    => "",
                'savelog' => false,
                'source'  => "LIST-RUJUKAN"
            ]);

            $result = json_decode($responsecurl,TRUE);

            if(intval($result['metaData']['code']) == 200){
                $restresponse = self::stringdecrypt(self::$clientid.self::$secretkey.str_replace("X-timestamp: ","",$header[1]),$result["response"]);
                $restresponse = self::decompress($restresponse);
                $restresponse = json_decode($restresponse, true);
    
                $result["metaData"]["code"]    = $result["metaData"]["code"];
                $result["metaData"]["message"] = $result["metaData"]["message"];
                $result["response"]            = $restresponse;
            }else{
                $result["metaData"]["code"]    = $result["metaData"]["code"];
                $result["metaData"]["message"] = $result["metaData"]["message"];
            }

            return $result;
        }
        
    }

?>