<?php
    defined("BASEPATH") OR exit("No direct script access allowed");

    class Channelpendaftaran extends MX_Controller{ 

        public function __construct(){
            parent:: __construct();
             $this->load->model("Modelchannelpendaftaran","md");
        }

        public function index(){
            $data = $this->loadcombobox();
            $this->template->load("template/dashboard-light-aside","v_channelpendaftaran",$data);
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

        public function datachannelpendaftaranrj(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datachannelpendaftaranrj($periode);
            
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