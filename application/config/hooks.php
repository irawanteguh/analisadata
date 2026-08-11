<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $hook['post_controller_constructor'] = array(
        'class'    => 'Routingsystem_hook',
        'function' => 'run',
        'filename' => 'Routingsystem_hook.php',
        'filepath' => 'hooks',
    );
?>