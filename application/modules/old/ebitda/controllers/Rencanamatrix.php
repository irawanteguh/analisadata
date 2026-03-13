<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Rencanamatrix extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			rootsystem::system();
            $this->load->model("Modelrencanamatrix","md");
        }

		public function index(){
			$this->template->load("template/template-sidebar","v_rencanamatrix.php");
		}

        public function matrixms(){
            $result = $this->md->matrixms();
            
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

        public function addcomponent(){
			$uuidheader = $this->input->post('modal_addmatrix_matrixid');
			$component  = $this->input->post('modal_addmatrix_component');
			$type       = $this->input->post('modal_addmatrix_type');
			$uuid       = generateuuid();

			$data['MATRIX_ID']        = $uuid;
			$data['MATRIX_ID_HEADER'] = $uuidheader;
			$data['COMPONENT']        = $component;
			$data['TYPE']             = $type;
			$data['CREATED_BY']       = 'SIRS01_'.$_SESSION['userid'];

			if($this->md->insertmatrix($data)){
				$json['responCode']="00";
				$json['responHead']="success";
				$json['responDesc']="Data Added Successfully";
			} else {
				$json['responCode']="01";
				$json['responHead']="info";
				$json['responDesc']="Data Failed to Add";
			}

            echo json_encode($json);
        }

        
	}
?>