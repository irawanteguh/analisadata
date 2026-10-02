<?php
    class Modelrawatinap extends CI_Model{

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

        function dataeklaim($periode){
            $query =
                    "
                        SELECT A.SEP, KELAS_RAWAT, SL, TARIF_RS, TOTAL_TARIF, IDRG_TOTAL_TARIF, TO_CHAR(A.ADMISSION_DATE,'MM') PERIODE
                        FROM SR01_BPJS_UR_DT A
                        WHERE A.PTD='1'
                        AND   TO_CHAR(A.ADMISSION_DATE,'YYYY')='".$periode."'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }


    }
?>