<?php

    function curl($config){
        $ci = &get_instance();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $config['url'],
            CURLOPT_CUSTOMREQUEST  => $config['method'],
            CURLOPT_POSTFIELDS     => $config['body'],
            CURLOPT_HTTPHEADER     => $config['header'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        
        $response        = curl_exec($curl);
        curl_close($curl);

        return $response;
    }


?>