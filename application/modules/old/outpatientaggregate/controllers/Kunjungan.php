<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Kunjungan extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			rootsystem::system();
			$this->load->model("Modelkunjungan","md");
        }

		public function index(){
			$data = $this->loadcombobox();
			$this->template->load("template/template-sidebar","v_kunjungan",$data);
		}

		public function loadcombobox(){
			$resultmasterdokter = $this->md->masterdokter();
			$resultmasterpoli   = $this->md->masterpoli();


            $listdoctor="";
            foreach($resultmasterdokter as $a ){
                $listdoctor.="<option value='".$a->DOKTER_ID."'>".$a->NAMA."</option>";
            }

			$listpoli="";
            foreach($resultmasterpoli as $a ){
                $listpoli.="<option value='".$a->POLI_ID."'>".$a->KETERANGAN."</option>";
            }



			$data['listdoctor'] = $listdoctor;
			$data['listpoli']   = $listpoli;
            return $data;
		}

		public function datakunjungan(){
			$startdate = $this->input->post("startdate");
			$endate    = $this->input->post("endate");
			$dokterid  = $this->input->post("dokterid") ? "AND A.DOKTER_ID='".$this->input->post("dokterid")."'" : "";
			$poliid    = $this->input->post("poliid") ? "AND A.POLI_ID='".$this->input->post("poliid")."'" : "";
			$result    = $this->md->datakunjungan($startdate,$endate,$dokterid,$poliid);

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