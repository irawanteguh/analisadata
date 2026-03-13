<?php
    function generateuuid($data = null){
        if (!isset($data)) {
            if (function_exists('random_bytes')) {
                $data = random_bytes(16);
            } elseif (function_exists('openssl_random_pseudo_bytes')) {
                $data = openssl_random_pseudo_bytes(16);
            } else {
                $data = '';
                for ($i = 0; $i < 16; $i++) {
                    $data .= chr(mt_rand(0, 255));
                }
            }
        }

        $data = substr($data, 0, 16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); 

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    function generateUniqueCode() {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $uniqueCode = '';
        
        for ($i = 0; $i < 6; $i++) {
            $uniqueCode .= $characters[rand(0, $charactersLength - 1)];
        }
    
        return $uniqueCode;
    }

    function encodedata($data){
        $i2 = 0;
        $s = "";
        $length = strlen($data);
        $pass = $data[$length - 1] . substr($data, 1, $length - 2) . $data[0];
        for ($i = 0; $i < $length; $i++) {
            $i2++;
            if ($i2 = 1) {
                $s = $s . "";
                $i2 = 0;
            }
            $num = dechex(ord($pass[$i]));
            $s = $s . "" . $num;
        }
        $result = $s;
        return $result;
    }

    function decodedata($data){
        $length = strlen($data);
        $res = '';

        if (strlen($data) % 2 !== 0) {
            return '';
        }

        for ($i = 0; $i < $length; $i += 2) {
            $char = $data[$i] . $data[$i + 1];
            $res .= chr(hexdec($char));
        }

        $decodedLength = strlen($res);
        $decodedPassword = $res[$decodedLength - 1] . substr($res, 1, $decodedLength - 2) . $res[0];

        return $decodedPassword;
    }

    function headerlog(){
        echo PHP_EOL;
        echo color('cyan').str_pad("TANGGAL", 15).str_pad("NOMOR KARTU", 25).str_pad("NOMOR SEP", 25).str_pad("NOMOR RUJUKAN", 25)."MESSAGE".PHP_EOL;
    }


    function formatlog($tanggal, $nokartu, $nosep, $norujukan, $message, $colortanggal = 'cyan', $colornokartu = 'cyan', $colornosep = 'cyan', $colornorujukan = 'cyan' , $colorMessage = 'white') {
        $tanggalWidth   = 15;
        $nokartuWidth   = 25;
        $nosepWidth     = 25;
        $norujukanWidth = 25;

        $colorStarttanggal   = color($colortanggal);
        $colorStartnokartu   = color($colornokartu);
        $colorStartnosep     = color($colornosep);
        $colorStartnorujukan = color($colornorujukan);

        $colorStartMessage   = color($colorMessage);
        $reset               = color('reset');

        $formatted  = $colorStarttanggal . str_pad($tanggal, $tanggalWidth) . $reset;
        $formatted .= $colorStartnokartu . str_pad($nokartu, $nokartuWidth) . $reset;
        $formatted .= $colorStartnosep . str_pad($nosep, $nosepWidth) . $reset;
        $formatted .= $colorStartnorujukan . str_pad($norujukan, $norujukanWidth) . $reset;
        $formatted .= $colorStartMessage . $message . $reset;

        return $formatted . PHP_EOL;
    }
?>