<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Routingsystem_hook
{
    protected static $appInstance;
    public static $environmentSettings;

    public function run()
    {
        $CI = &get_instance();

        $segment1 = (string) $CI->uri->segment(1);
        $segment2 = (string) $CI->uri->segment(2);

        if ($segment1 === 'auth' && $segment2 === 'sign') {
            return;
        }

        Routingsystem::system();
    }
}
?>