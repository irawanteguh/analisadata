<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Rawatjalan extends CI_Controller {

		public function __construct(){
            parent:: __construct();
            $this->load->model("Modelrawatjalan","md");
        }

		public function index(){
			$data = $this->loadcombobox();
			$data['view'] = $this->input->get('view');

			if ($data['view'] == 'detail') {
				$this->template->load("template/dashboard-light-aside","v_rawatjalandetail",$data);
			} else {
				$this->template->load("template/dashboard-light-aside","v_rawatjalansummary",$data);
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

		public function datarrjdetail(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datarrjdetail($periode);
            
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

		public function quadrantdokter(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->quadrantdokter($periode);
            
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

		public function quadrantsmf(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->quadrantsmf($periode);
            
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

        public function quadrantresource(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->quadrantresource($periode);
            
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

        public function datadetailtidakadasep(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datadetailtidakadasep($periode);
            
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