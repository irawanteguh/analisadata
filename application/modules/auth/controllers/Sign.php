<?php
    defined("BASEPATH") OR exit("No direct script access allowed");
    class Sign extends CI_Controller{ 

        public function __construct(){
            parent:: __construct();
            $this->load->model("Modelsign","md");
        }
        
        public function index(){
            $this->template->load("template/template-blank","v_sign");
            $this->session->sess_destroy();
        }

        public function signin() {
            $username = $this->input->post("username");
            $password = $this->input->post("password");
        
            $checkauth = $this->md->login($username, $password);
            
            if (!empty($checkauth)) {
                $datasession = $this->md->datasession($checkauth->USER_ID);
        
                $sessiondata = array(
                    "userid"       => $datasession->USER_ID,
                    "name"         => $datasession->NAMA,
                    "initial"      => $datasession->INISIAL,
                    "unitid"       => $datasession->UNITID,
                    "target"       => $datasession->TARGET,
                    "targetppc"    => $datasession->TARGETPPC,
                    "targetharian" => $datasession->TARGETHARIAN,
                    "namaunit"     => $datasession->NAMAUNIT,
                    "loggedin"     => true,
                    "timeout"      => false
                );
        
                $this->session->set_userdata($sessiondata);
        
                $json["responCode"] = "00";
                $json["responHead"] = "success";
                $json["responDesc"] = "Hey, ".$datasession->NAMA."<br>Welcome Back and Have a nice day";
                $json["url"]        = base_url()."index.php/dashboard/dashboard";
            } else {
                $json["responCode"] = "01";
                $json["responHead"] = "error";
                $json["responDesc"] = "Username and/or Password Unknown";
            }
        
            echo json_encode($json);
        }

        public function logoutsystem(){                            
            $this->session->sess_destroy();
            redirect("auth/sign");
        }

    }
?>