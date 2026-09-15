<?php
    class Modelwhatsapp extends CI_Model{

        function checklogwebhook($parameter){
            $query =
                    "
                        SELECT A.IDEMPOTENCY_KEY
                        FROM SR01_WHATSAPP_WEBHOOK A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.IDEMPOTENCY_KEY='".$parameter."'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function insertlogwhatsapp($data){           
            $sql =   $this->db->insert("SR01_WHATSAPP_BROADCAST_HD",$data);
            return $sql;
        }

        function insertlogwebhook($data){           
            $sql =   $this->db->insert("SR01_WHATSAPP_WEBHOOK",$data);
            return $sql;
        }

        function updatewebhook($id,$data){           
            $sql =   $this->db->update("SR01_WHATSAPP_WEBHOOK",$data,array("IDEMPOTENCY_KEY"=>$id));
            return $sql;
        }

    }
?>