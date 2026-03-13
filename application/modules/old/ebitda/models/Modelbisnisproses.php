<?php
    class Modelbisnisproses extends CI_Model{

        function databisnisproses(){
            $query =
                    "
                        SELECT A.PROSES_ID, LOKASI, OWNER, PROSES, TO_CHAR(CREATED_DATE,'DD.MM.YYYY HH24:MI:SS')CREATEDDATE,
                               (SELECT NAMA FROM SR01_GEN_USER_DATA WHERE LOKASI_ID='001' AND AKTIF='1' AND 'SIRS01_'||UPPER(USER_ID)=A.CREATED_BY)DIBUATOLEH
                        FROM SR01_EBITDA_BISNIS_PROSES A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        ORDER BY CREATED_DATE ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
    }
?>