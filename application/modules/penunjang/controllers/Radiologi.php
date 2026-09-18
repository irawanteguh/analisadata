<?php
    defined("BASEPATH") OR exit("No direct script access allowed");

    class Radiologi extends MX_Controller{ 

        public function __construct(){
            parent:: __construct();
            $this->load->model("Modelradiologi","md");
        }

        public function index(){
            $this->template->load("template/dashboard-light-aside","v_radiologi");
        }

        public function datapemeriksaan(){
            $startDate = $this->input->post('startDate');
            $endDate   = $this->input->post('endDate');

            $result  = $this->md->datapemeriksaan($startDate,$endDate);
            
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