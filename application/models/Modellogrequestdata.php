<?php
    class Modellogrequestdata extends CI_Model{

        function insertlog($data){           
            $sql =   $this->db->insert("SR01_ETICKET_MS",$data);
            return $sql;
        }


    }
?>