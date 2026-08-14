<?php
    class Modelkpi extends CI_Model{

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

        function datajampulangpasienbln($periode){
            $query =
                    "
                        SELECT A.*
                        FROM MV_KPI_JAM_PULANG_RANAP_BLN A
                        WHERE A.TAHUN='".$periode."'
                        ORDER BY A.BULAN ASC

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datajampulangharian(){
            $query =
                    "
                        SELECT A.*
                        FROM MV_KPI_JAM_PULANG_RANAP_HARIAN A
                        ORDER BY A.PERIODE ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>