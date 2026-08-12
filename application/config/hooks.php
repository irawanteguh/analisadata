<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    $hook['post_controller_constructor'][] = array(
        'class'    => 'Routingsystem_hook',
        'function' => 'run',
        'filename' => 'Routingsystem_hook.php',
        'filepath' => 'hooks',
    );

    $hook['post_controller_constructor'][] = array(
        'class'    => 'Logrequestdata_hook',
        'function' => 'start',
        'filename' => 'Logrequestdata_hook.php',
        'filepath' => 'hooks',
    );

    $hook['post_system'][] = array(
        'class'    => 'Logrequestdata_hook',
        'function' => 'finish',
        'filename' => 'Logrequestdata_hook.php',
        'filepath' => 'hooks',
    );
?>