<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Prb extends CI_Controller {

		public function __construct(){
            parent:: __construct();
        }

		public function index(){
			$this->template->load("template/dashboard-light-aside","v_prb");
		}

        public function srbbydate(){
            $parameter1 = $this->input->post("startDate");
            $parameter2 = $this->input->post("endDate");

            $response = bpjs::srbbydate($parameter1, $parameter2);

            if (isset($response['response']['prb']['list']) && is_array($response['response']['prb']['list'])) {
                $json["responCode"]   = "00";
                $json["responHead"]   = "success";
                $json["responDesc"]   = "Data Ditemukan";
                $json["responResult"] = $response;
            } else {
                $json["responCode"]   = "01";
                $json["responHead"]   = "error";
                $json["responDesc"]   = "Data Tidak Ditemukan";
                $json["responResult"] = $response;
            }

            echo json_encode($json);
        }

        public function detailsrb(){
            $parameter1 = $this->input->post("nosrb");
            $parameter2 = $this->input->post("nosep");

            $response = bpjs::srbbynosrb($parameter1,$parameter2);

            $json["responCode"]   = "00";
            $json["responHead"]   = "success";
            $json["responDesc"]   = "Data Di Temukan";
            $json['responResult'] = $response;
            
            echo json_encode($json);
        }
        
	}
?>