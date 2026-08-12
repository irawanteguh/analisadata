<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bpjs_hook
{
    public function init()
    {
        $CI =& get_instance();

        $CI->bpjs->init();
    }
}