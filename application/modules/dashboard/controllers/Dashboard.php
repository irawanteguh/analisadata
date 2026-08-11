<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Dashboard extends CI_Controller {

		public function __construct(){
            parent:: __construct();
            $this->load->model("Modeldashboard","md");
        }

		public function index(){
            $data = $this->loadcombobox();
			$this->template->load("template/dashboard-light-aside","v_dashboard",$data);
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

        // public function datakunjungandokterigd(){
        //     $periode = $this->input->post("selectperiode");
        //     $result  = $this->md->datakunjungandokterigd($periode);
            
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

        public function datakunjunganigdprovider(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datakunjunganigdprovider($periode);
            
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

        public function datakunjunganrjpoliklinik(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datakunjunganrjpoliklinik($periode);
            
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

        public function datakunjunganrjdokter(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datakunjunganrjdokter($periode);
            
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

        public function datakunjunganrjprovider(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datakunjunganrjprovider($periode);
            
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

        public function datakunjunganriprovider(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datakunjunganriprovider($periode);
            
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

        public function datakunjunganridokter(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datakunjunganridokter($periode);
            
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