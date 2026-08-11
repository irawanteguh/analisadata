<?php
    defined("BASEPATH") OR exit("No direct script access allowed");

    class Kunjungan extends MX_Controller{ 

        public function __construct(){
            parent:: __construct();
             $this->load->model("Modelkunjungan","md");
        }

        public function index(){
            $data = $this->loadcombobox();
            $this->template->load("template/dashboard-light-aside","v_kunjungan",$data);
        }

        public function loadcombobox(){
			$resultperiode = $this->md->periode();

			$periode="";
            foreach($resultperiode as $a ){
                $periode.="<option value='".$a->PERIODE."'>".$a->PERIODE."</option>";
            }

			$data['periode'] = $periode;
            return $data;
		}

    }
?>