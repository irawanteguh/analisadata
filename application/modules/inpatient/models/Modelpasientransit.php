<?php
    class Modelpasientransit extends CI_Model{

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

        function datapasientransit($periode){
            $query =
                    "
                        SELECT *
                        FROM (
                            SELECT
                                A.PASIEN_ID,
                                A.EPISODE_ID,
                                TO_CHAR(KMR.TGL_MASUK,'MM') PERIODE,
                                TO_CHAR(KMR.TGL_MASUK,'DD.MM.YYYY HH24:MI:SS') TGLMASUKTRANSIT,
                                TO_CHAR(KMR.TGL_KELUAR,'DD.MM.YYYY HH24:MI:SS') TGLKELUARTRANSIT,
                                PS.INT_PASIEN_ID AS MRPAS,
                                PS.SEX_ID AS SEXID,
                                SR01_GET_SUFFIX(A.PASIEN_ID) AS NAMAPASIEN,
                                ROW_NUMBER() OVER (
                                    PARTITION BY A.EPISODE_ID
                                    ORDER BY KMR.TGL_MASUK DESC
                                ) AS RN
                            FROM SR01_KEU_EPISODE A
                            LEFT JOIN SR01_GEN_PASIEN_MS PS
                                ON PS.LOKASI_ID='001'
                            AND PS.AKTIF='1'
                            AND PS.PASIEN_ID=A.PASIEN_ID
                            LEFT JOIN SR01_KEU_TRANSKMR_IT KMR
                                ON KMR.LOKASI_ID='001'
                            AND KMR.AKTIF='1'
                            AND KMR.RUANG_ID='TRANSIT'
                            AND KMR.PASIEN_ID=A.PASIEN_ID
                            AND KMR.EPISODE_ID=A.EPISODE_ID
                            WHERE A.LOKASI_ID='001'
                            AND A.AKTIF='1'
                            AND A.JENIS_EPISODE='I'
                            AND A.STATUS_EPISODE<>'99'
                            AND A.RUANGRWT_ID LIKE 'TRANSIT%'
                            AND TO_CHAR(KMR.TGL_MASUK,'YYYY')='".$periode."'
                        )
                        WHERE RN = 1
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
        
    }
?>