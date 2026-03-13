<?php
    class Modelforecasting extends CI_Model{

        function periode(){
            $query =
                    "
                        SELECT (2016 + LEVEL) AS PERIODE
                        FROM DUAL
                        CONNECT BY LEVEL <= EXTRACT(YEAR FROM SYSDATE) - 2016
                        ORDER BY PERIODE DESC

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function forecastingoutpatient($periode){
            $query =
                    "
                        SELECT A.*
                        FROM SR01_FORECASTING_DT A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.JENIS='KUNJUNGAN RJ'
                        AND   A.TAHUN='".$periode."'

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
    }
?>