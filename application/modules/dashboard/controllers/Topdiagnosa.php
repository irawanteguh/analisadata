<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Topdiagnosa extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			$this->load->model("Modeltopdiagnosa","md");
        }

		public function index(){
			$data = $this->loadcombobox();
			$data['view'] = $this->input->get('view');

			switch ($data['view']) {

				case 'detailrjsmf':
					$this->template->load(
						"template/dashboard-light-aside",
						"v_topdiagnosadetailrjsmf",
						$data
					);
					break;

				case 'detailrismf':
					$this->template->load(
						"template/dashboard-light-aside",
						"v_topdiagnosadetailrismf",
						$data
					);
					break;

				default:
					$this->template->load(
						"template/dashboard-light-aside",
						"v_topdiagnosasummary",
						$data
					);
					break;
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
        
		public function datarjgeriatri(){
			$periode  = $this->input->post("selectperiode");
			$result  = $this->md->datarjgeriatri($periode);

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

		public function datarj(){
			$periode  = $this->input->post("selectperiode");
			$result  = $this->md->datarj($periode);

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

		public function datarjsmf(){
			$periode  = $this->input->post("selectperiode");
			$result  = $this->md->datarjsmf($periode);

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

		public function dataigd(){
			$periode  = $this->input->post("selectperiode");
			$result  = $this->md->dataigd($periode);

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

		public function datari(){
			$periode  = $this->input->post("selectperiode");
			$result  = $this->md->datari($periode);

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

		public function datarismf(){
			$periode  = $this->input->post("selectperiode");
			$result  = $this->md->datarismf($periode);

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