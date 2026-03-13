<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Kunjungan extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			rootsystem::system();
            $this->load->model("Modelkunjungan","md");
        }

		public function index(){
			$this->template->load("template/template-sidebar","v_kunjungan");
		}

		public function databooking(){
			$startdate = $this->input->post("startDate");
			$endate    = $this->input->post("endDate");
			$result    = $this->md->databooking($startdate,$endate);

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
        

        // public function datakunjungan(){
		// 	$startdate = $this->input->post("startdate");
        //     $endate    = $this->input->post("endate");
		// 	$result  = $this->md->datakunjungan($startdate,$endate);

		// 	if(!empty($result)){
		// 		$json["responCode"]   = "00";
		// 		$json["responHead"]   = "success";
		// 		$json["responDesc"]   = "Data Di Temukan";
		// 		$json['responResult'] = $result;
        //     }else{
        //         $json["responCode"] = "01";
        //         $json["responHead"] = "info";
        //         $json["responDesc"] = "Data Tidak Di Temukan";
        //     }

        //     echo json_encode($json);
        // }
	}
?>