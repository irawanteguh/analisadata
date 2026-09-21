<?php
    defined("BASEPATH") OR exit("No direct script access allowed");

    class Kpi extends MX_Controller{ 

        public function __construct(){
            parent:: __construct();
            $this->load->model("Modelkpi","md");
        }

        public function index(){
            $data         = $this->loadcombobox();
            $data['view'] = $this->input->get('view');

            switch ($data['view']) {

				case 'detail':
					$this->template->load(
						"template/dashboard-light-aside",
						"v_kpidetail",
						$data
					);
					break;

				default:
					$this->template->load(
						"template/dashboard-light-aside",
						"v_kpi",
						$data
					);
					break;
			}

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

        public function detailwwaktutungguperbulan(){
            $result  = $this->md->detailwwaktutungguperbulan();
            
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

        public function detailwwaktutunggu(){
            $result  = $this->md->detailwwaktutunggu();
            
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