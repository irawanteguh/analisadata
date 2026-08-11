<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Summary extends CI_Controller {

		public function __construct(){
            parent:: __construct();
            $this->load->model("Modelsummary","md");
        }

		public function index(){
			$this->template->load("template/dashboard-light-aside","v_summary");
		}

        public function datakunjunganrawatjalan(){
			$startDate = $this->input->post("startDate");
            $result  = $this->md->datakunjunganrawatjalan($startDate);
            
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

		public function dataantrianhelpdesk(){
            $result  = $this->md->dataantrianhelpdesk();
            
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

        public function dataagamapasienri(){
            $result  = $this->md->dataagamapasienri();
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