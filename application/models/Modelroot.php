<?php
    class Modelroot extends CI_Model{

        function menu(){
            $query =
                    "
                        SELECT A.*
                        FROM SR01_GEN_MODULES_MS A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.SOURCECODE='ANALISA'
                        ORDER BY URUT ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result_array();
            return $recordset;
        }
    }
?>