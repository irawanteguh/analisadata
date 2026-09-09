<?php
    defined("BASEPATH") OR exit("No direct script access allowed");

    class Berkas extends MX_Controller{

        public function __construct(){
            parent:: __construct();
            $this->load->model("Modelberkas","md");
        }

        public function index(){
            $this->template->load("template/dashboard-light-aside","v_berkas");
        }

        public function datakunjungan(){
            $startdate = $this->input->post("startdate");
            $endate    = $this->input->post("endate");
            $result    = $this->md->datakunjungan($startdate,$endate);

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