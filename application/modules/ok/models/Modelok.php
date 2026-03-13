<?php
    class Modelok extends CI_Model{

        function periode(){
            $query =
                    "
                        SELECT (2014 + LEVEL) AS PERIODE
                        FROM DUAL
                        CONNECT BY LEVEL <= EXTRACT(YEAR FROM SYSDATE) - 2014
                        ORDER BY PERIODE DESC

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjungan($periode){
            $query =
                    "
                        SELECT A.TRANS_ID, TGL_TINDAKAN, STATUS_ID, ALASAN_BATAL, RUANG_OK, JAM_MULAI
                        FROM SR01_MED_OK_LOG A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   TO_CHAR(A.TGL_TINDAKAN,'YYYY')='".$periode."'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
    }
?>