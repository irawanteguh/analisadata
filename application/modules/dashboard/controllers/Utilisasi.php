<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Utilisasi extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			$this->load->model("Modelutilisasi","md");
        }

        public function index(){
			$data = $this->loadcombobox();
			$data['view'] = $this->input->get('view');

			switch ($data['view']) {

				case 'ruangok':
					$this->template->load(
						"template/dashboard-light-aside",
						"v_utilisasiok",
						$data
					);
					break;
				
				case 'mapping':
					$this->template->load(
						"template/dashboard-light-aside",
						"v_utilisasimaaping",
						$data
					);
					break;

				default:
					$this->template->load(
						"template/dashboard-light-aside",
						"v_utilisasialkes",
						$data
					);
					break;
			}
		}

        public function loadcombobox(){
			$resultperiode = $this->md->periode();
			$resultmasterdevice = $this->md->masterdevice();

			$periode="";
            foreach($resultperiode as $a ){
                $periode.="<option value='".$a->PERIODE."'>".$a->PERIODE."</option>";
            }

			$masterdevice="";
            foreach($resultmasterdevice as $a ){
                $masterdevice.="<option value='".$a->DEVICE_ID."'>".$a->DEVICE_NAME."</option>";
            }

			$data['periode']      = $periode;
			$data['masterdevice'] = $masterdevice;
            return $data;
		}

        public function datautilisasiruangok(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datautilisasiruangok($periode);
            
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

        public function datautilisasialkes(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datautilisasialkes($periode);
            
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

		public function datamasterlayan(){
            $result  = $this->md->datamasterlayan();
            
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

		public function mappingtindakanalkes(){
			$layanid  = trim($this->input->post('layanid'));
			$deviceid = trim($this->input->post('deviceid'));

			if (empty($layanid) || empty($deviceid)) {
				echo json_encode([
					'responCode'   => '01',
					'responMessage'=> 'Data mapping tidak lengkap.',
					'responResult' => []
				]);
				return;
			}

			$data['DEVICE_ID'] =$deviceid;

			if ($this->md->updatemapping($layanid,$data)) {
				$json["responCode"] = "00";
				$json["responHead"] = "success";
				$json["responDesc"] = "Mapping Berhasil Disimpan";
			} else {
				$json["responCode"] = "01";
				$json["responHead"] = "info";
				$json["responDesc"] = "Mapping Gagal Disimpan";
			}

			echo json_encode($json);
		}
	}
?>