<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Kematian extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			rootsystem::system();
            $this->load->model("Modelkematian","md");
        }

		public function index(){
			$this->template->load("template/template-sidebar","v_kematian");
		}

        public function laporankematian(){
            $startdate = $this->input->post("startdate");
            $endate    = $this->input->post("endate");

            $startdate = "2026-01-19";
            $endate    = "2026-01-19";

            $result = $this->md->laporankematian($startdate,$endate);
            
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

        public function chartews(){
            $result = $this->md->chartews();
            
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