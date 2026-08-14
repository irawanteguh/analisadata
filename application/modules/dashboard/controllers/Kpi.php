<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Kpi extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			$this->load->model("Modelkpi","md");
        }

        public function index(){
            $data = $this->loadcombobox();
			$this->template->load("template/dashboard-light-aside","v_kpi",$data);
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

        public function datajampulangpasienbln(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datajampulangpasienbln($periode);
            
			if(!empty($result)){
				$json["responCode"]   = "00";
				$json["responHead"]   = "success";
				$json["responDesc"]   = "Data Di Temukan";
				$json['responResult'] = $result;
            }else{
                $json["responCode"] = "01";
                $json["responHead"] = "info";
                $json["responDesc"] = "Data Tidak Di Temukan";
            }

            echo json_encode($json);
        }

        public function datajampulangharian(){
            $result  = $this->md->datajampulangharian();
            
			if(!empty($result)){
				$json["responCode"]   = "00";
				$json["responHead"]   = "success";
				$json["responDesc"]   = "Data Di Temukan";
				$json['responResult'] = $result;
            }else{
                $json["responCode"] = "01";
                $json["responHead"] = "info";
                $json["responDesc"] = "Data Tidak Di Temukan";
            }

            echo json_encode($json);
        }

	}
?>