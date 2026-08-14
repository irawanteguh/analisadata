<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Resumemedis extends CI_Controller {

		public function __construct(){
            parent:: __construct();
            $this->load->model("Modelresumemedis","md");
        }

        public function index(){
			$data = $this->loadcombobox();
			$data['view'] = $this->input->get('view');

			switch ($data['view']) {

				case 'indikatormutu':
					$this->template->load(
						"template/dashboard-light-aside",
						"v_resumemedismutu",
						$data
					);
					break;

				case 'rawdata':
					$this->template->load(
						"template/dashboard-light-aside",
						"v_resumemedisrawdata",
						$data
					);
					break;

				default:
					$this->template->load(
						"template/dashboard-light-aside",
						"v_resumemedissummary",
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

        public function resumemedis(){
			$periode  = $this->input->post("selectperiode");
			$variable = "AND   TO_CHAR(A.TGL_KELUAR,'YYYY')='".$periode."'";

            $result = $this->md->resumemedis($variable);
			if(!empty($result)){
                $json["responCode"]="00";
                $json["responHead"]="success";
                $json["responDesc"]="Data Di Temukan";
				$json['responResult']=$result;
            }else{
                $json["responCode"]="01";
                $json["responHead"]="info";
                $json["responDesc"]="Data Tidak Di Temukan";
            }

            echo json_encode($json);
        }

		public function rawresumemedis(){
			$startdate = $this->input->post("startDate");
			$enddate   = $this->input->post("endDate");
			$variable  = "AND   A.TGL_KELUAR >= TO_DATE('".$startdate."','YYYY-MM-DD') AND   A.TGL_KELUAR < TO_DATE('".$enddate."','YYYY-MM-DD') + 1";

            $result = $this->md->resumemedis($variable);

			if(!empty($result)){
                $json["responCode"]="00";
                $json["responHead"]="success";
                $json["responDesc"]="Data Di Temukan";
				$json['responResult']=$result;
            }else{
                $json["responCode"]="01";
                $json["responHead"]="info";
                $json["responDesc"]="Data Tidak Di Temukan";
            }

            echo json_encode($json);
        }

	}
?>