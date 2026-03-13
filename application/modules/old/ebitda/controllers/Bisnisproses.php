<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Bisnisproses extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			rootsystem::system();
            $this->load->model("Modelbisnisproses","md");
        }

		public function index(){
			$this->template->load("template/template-sidebar","v_bisnisproses");
		}

        public function databisnisproses(){
            $result = $this->md->databisnisproses();
            
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