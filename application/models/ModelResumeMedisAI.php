<?php
    class ModelResumeMedisAI extends CI_Model{

        function riwayatpenyakitdahulu(){
            $query =
                    "
                        SELECT DISTINCT A.S AS RESULTSOAP
                        FROM WEB_CO_DIAGNOSA_DT A
                        WHERE A.EPISODE_ID = '126023048688'
                        AND A.FLAG_HAPUS = '1'
                        AND A.SHOW_ITEM = '1'
                        AND A.S IS NOT NULL
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result_array();
            return $recordset;
        }
    }
?>