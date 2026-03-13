<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    $route['default_controller']   = 'welcome';
    $route['404_override']         = '';
    $route['translate_uri_dashes'] = FALSE;

    $route['generateresumeai/(:num)'] = 'restapi/AIGenerator/ResumeMedisAI/generateresumeai/$1';
    $route['rujukanbpjs']             = 'restapi/BPJS/Rujukankeluar/rujukanbpjs';

?>