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

        public function radiologi(){
			$periode = $this->input->post("selectperiode");
            $result  = $this->md->radiologi($periode);
            
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

        public function kpipasienrawatinappulangagregate(){
			$periode = $this->input->post("selectperiode");
            $result  = $this->md->kpipasienrawatinappulangagregate($periode);
            
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

        // public function kpipasienrawatinappulangdetail(){
        //     $result  = $this->md->kpipasienrawatinappulangdetail();
            
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

        // public function pembatalanoperasielektif(){
		// 	$periode = $this->input->post("selectperiode");
        //     $result  = $this->md->pembatalanoperasielektif($periode);
            
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