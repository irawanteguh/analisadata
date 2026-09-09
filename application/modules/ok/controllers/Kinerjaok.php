<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Kinerjaok extends CI_Controller {

		public function __construct(){
            parent:: __construct();
            $this->load->model("Modelkinerjaok","md");
        }

        public function index(){
            $data = $this->loadcombobox();
			$this->template->load("template/dashboard-light-aside","v_kinerjaok",$data);
		}


        public function loadcombobox(){
			$resultperiode = $this->md->periode();

			$periode="";
            foreach($resultperiode as $a ){
                $periode.="<option value='".$a->PERIODE."'>".$a->PERIODE."</option>";
            }

			$data['periode']      = $periode;
            return $data;
		}

	}
?>