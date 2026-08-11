<?php
    defined("BASEPATH") OR exit("No direct script access allowed");
    class Welcomepage extends MX_Controller{ 
        public function index(){
            $this->template->load("template/dashboard-light-aside","v_welcomepage");
        }

    }
?>