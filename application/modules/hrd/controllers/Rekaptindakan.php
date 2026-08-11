<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Rekaptindakan extends CI_Controller {

		public function __construct(){
            parent:: __construct();
            $this->load->model("Modelrekaptindakan","md");
        }

		public function index(){
			$this->template->load("template/dashboard-light-aside","v_rekaptindakan");
		}

        public function datajumlahpasien(){
			$startdate = $this->input->post("startDate");
			$endate    = $this->input->post("endDate");
			$result    = $this->md->datajumlahpasien($_SESSION['dokterid'],$startdate,$endate);
            
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

        public function dataaktifitasdokter(){
			$dokterid  = "DR. B0000000000";
			$startdate = $this->input->post("startDate");
			$endate    = $this->input->post("endDate");
			$result    = $this->md->dataaktifitasdokter($dokterid,$startdate,$endate);
            
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