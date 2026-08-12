<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logrequestdata_hook {

    protected $CI;

    public function __construct(){
        $this->CI =& get_instance();
    }

    public function start(){
        $this->CI->log_request_start = microtime(true);
    }

    public function finish(){
        if(!isset($this->CI->log_request_start)){
            return;
        }

        $controller = $this->CI->router->fetch_class();
        $method = $this->CI->router->fetch_method();

        if(strpos($method, 'data') !== 0){
            return;
        }

        $durasi = round((microtime(true) - $this->CI->log_request_start) * 1000);

        $post = $this->CI->input->post();
        $periode = '';

        if(!empty($post)){
            foreach($post as $key => $value){
                if(is_array($value)){
                    $value = implode(', ', $value);
                }

                if($periode != ''){
                    $periode .= ' | ';
                }

                $periode .= $key.' : '.$value;
            }
        }

        if($periode == ''){
            $periode = date('Y-m-d');
        }

        $data = new stdClass();
        $data->jenisdata = $controller.'/'.$method;
        $data->periode = $periode;
        $data->durasi = $durasi;

        $this->CI->load->library('logrequestdata');
        $this->CI->logrequestdata->simpanrequestdata($data);
    }
}