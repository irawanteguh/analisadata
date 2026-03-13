<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Outpatient extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			rootsystem::system();
            $this->load->model("Modelforecasting","md");
        }

		public function index(){
			$data = $this->loadcombobox();
			$this->template->load("template/template-sidebar","v_outpatient",$data);
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

		public function forecastingoutpatient(){
			$periode = $this->input->post("selectperiode");
            $result  = $this->md->forecastingoutpatient($periode);
            
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

        public function simulationforecasting(){
			$periode = $this->input->post("selectperiode");
			$url     = "http://127.0.0.1:5050/forecast?tahun=".$periode;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($httpcode != 200 || !$response) {
				$json = [
					"responCode" => "01",
					"responHead" => "error",
					"responDesc" => "Tidak dapat terhubung ke Python Flask API",
					"responResult" => null
				];
			} else {
				$json = [
					"responCode" => "00",
					"responHead" => "success",
					"responDesc" => "Data Ditemukan",
					"responResult" => json_decode($response, true)
				];
			}

			echo json_encode($json);
        }
	}
?>