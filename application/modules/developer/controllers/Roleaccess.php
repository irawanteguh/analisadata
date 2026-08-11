<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Roleaccess extends CI_Controller {

		public function __construct(){
            parent:: __construct();
            $this->load->model("Modelroleaccess","md");
        }

		public function index(){
			$this->template->load("template/dashboard-light-aside","v_roleaccess");
		}

        public function datauser(){
            $result  = $this->md->datauser();
            
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

        public function datamodules(){
            $userid = $this->input->post("userid");
            $result  = $this->md->datamodules($userid);
            
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

        public function addaccess(){
            $userid      = $this->input->post("userid");
            $switchId    = $this->input->post("switchId");
            $switchValue = $this->input->post("switchValue");
    
            if ($switchValue === "true" || $switchValue === true) {
                $data['AKTIF'] = "1";
            } else {
                $data['AKTIF'] = "0";
            }
    
            $resultcheckdata = $this->md->checkdata($userid,$switchId);
    
            if (!empty($resultcheckdata)) {
                if ($this->md->updaterole($userid, $switchId, $data)) {
                    $json["responCode"] = "00";
                    $json["responHead"] = "success";
                    $json["responDesc"] = "Update Role Success";
                } else {
                    $json["responCode"] = "01";
                    $json["responHead"] = "info";
                    $json["responDesc"] = "Update Role Failed";
                }
            } else {
                $data['USER_ID']          = $userid;
                $data['MODULES_ID']       = $switchId;
                $data['CREATED_BY']       = $_SESSION['userid'];
    
                if($this->md->insertrole($data)){
                    $json["responCode"] = "00";
                    $json["responHead"] = "success";
                    $json["responDesc"] = "Update Role Success";
                }else{
                    $json["responCode"] = "01";
                    $json["responHead"] = "info";
                    $json["responDesc"] = "Update Role Failed";
                }
            }
        
            echo json_encode($json);
        }


        
	}
?>