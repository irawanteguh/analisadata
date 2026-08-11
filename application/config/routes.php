<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    $route['default_controller']      = 'welcome';
    $route['404_override']            = '';
    $route['translate_uri_dashes']    = FALSE;

    $route['generateresumeai/(:any)'] = 'restapi/AIGenerator/ResumeAI/generateresumeai/$1';
    // $route['generateresume']          = 'restapi/AIGenerator/ResumeAI/generateresume';

    $route['rujukanbpjs']             = 'restapi/BPJS/Rujukankeluar/rujukanbpjs';
    
    $route['oauth']                   = 'restapi/Auth/Oauth/token';
    $route['timelinepatient']         = 'restapi/PEO/Timeline/timelinepatient';
?>