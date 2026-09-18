<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    $route['default_controller']      = 'welcome';
    $route['404_override']            = '';
    $route['translate_uri_dashes']    = FALSE;

    $route['generateresumeai/(:any)'] = 'restapi/AIGenerator/ResumeAI/generateresumeai/$1';
    $route['generateresume']          = 'restapi/AIGenerator/ResumeAI/generateresume';

    $route['rujukanbpjs']       = 'restapi/BPJS/Rujukankeluar/rujukanbpjs';

    $route['nlp/test']            = 'restapi/Whatsapp/Nlptesting/test';
    $route['nlp/dataset']         = 'restapi/Whatsapp/Nlptesting/dataset';
    $route['nlp/evaluate']        = 'restapi/Whatsapp/Nlptesting/evaluate';
    $route['nlp/miss']            = 'restapi/Whatsapp/Nlptesting/miss';
    $route['nlp/multi']           = 'restapi/Whatsapp/Nlptesting/multi';
    $route['nlp/confusion']       = 'restapi/Whatsapp/Nlptesting/confusion';
    $route['listpasienbooking']   = 'restapi/Whatsapp/Rawatjalan/listpasienbooking';
    $route['listreminderbooking'] = 'restapi/Whatsapp/Rawatjalan/listreminderbooking';
    $route['listbatalbooking']    = 'restapi/Whatsapp/Rawatjalan/listbatalbooking';
    $route['listpasienadaobat']   = 'restapi/Whatsapp/Rawatjalan/listpasienadaobat';
    $route['googlereview']        = 'restapi/Whatsapp/Rawatjalan/googlereview';
    $route['greeting']            = 'restapi/Whatsapp/PeoMagnolia/greeting';
    $route['biaya']               = 'restapi/Whatsapp/PeoMagnolia/biaya';
    $route['pendaftaran']         = 'restapi/Whatsapp/PeoMagnolia/pendaftaran';
    $route['bpjs']                = 'restapi/Whatsapp/PeoMagnolia/bpjs';
    $route['alamat']              = 'restapi/Whatsapp/PeoMagnolia/alamat';
    $route['keluhan']             = 'restapi/Whatsapp/PeoMagnolia/keluhan';
    $route['jambesuk']            = 'restapi/Whatsapp/PeoMagnolia/jambesuk';
    
?>