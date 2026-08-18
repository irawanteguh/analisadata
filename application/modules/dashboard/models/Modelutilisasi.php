<?php
    class Modelutilisasi extends CI_Model{

        function periode(){
            $query =
                    "
                        SELECT (2014 + LEVEL) AS PERIODE
                        FROM DUAL
                        CONNECT BY LEVEL < EXTRACT(YEAR FROM SYSDATE) - 2014
                        ORDER BY PERIODE DESC

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function masterdevice(){
            $query =
                    "
                        SELECT A.DEVICE_ID, DEVICE_NAME
                        FROM SR01_MED_DEVICE_MS A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        ORDER BY DEVICE_NAME ASC

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datautilisasiruangok($periode){
            $query = "
                        SELECT
                            RUANG_OK,
                            NAMAOK,
                            JAN,
                            FEB,
                            MAR,
                            APR,
                            MEI,
                            JUN,
                            JUL,
                            AGS,
                            SEP,
                            OKT,
                            NOV,
                            DES
                        FROM (
                            SELECT
                                A.RUANG_OK,
                                (
                                    SELECT MAX(NAMA_RUANGAN)
                                    FROM SR01_MED_OK_MS
                                    WHERE JENIS = '1'
                                    AND RUANG_OK = A.RUANG_OK
                                ) AS NAMAOK,
                                TO_CHAR(A.TGL_TINDAKAN, 'MM') AS BULAN
                            FROM SR01_MED_OK_LOG A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.STATUS_ID = '02'
                            AND TO_CHAR(A.TGL_TINDAKAN,'YYYY') = '".$periode."'
                        )
                        PIVOT (
                            COUNT(*)
                            FOR BULAN IN (
                                '01' AS JAN,
                                '02' AS FEB,
                                '03' AS MAR,
                                '04' AS APR,
                                '05' AS MEI,
                                '06' AS JUN,
                                '07' AS JUL,
                                '08' AS AGS,
                                '09' AS SEP,
                                '10' AS OKT,
                                '11' AS NOV,
                                '12' AS DES
                            )
                        )
                        ORDER BY RUANG_OK
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datautilisasialkes($periode){
            $query = "
                        SELECT
                            DEVICE_ID,
                            DEVICE_NAME,
                            NVL(JAN, 0) AS JAN,
                            NVL(FEB, 0) AS FEB,
                            NVL(MAR, 0) AS MAR,
                            NVL(APR, 0) AS APR,
                            NVL(MEI, 0) AS MEI,
                            NVL(JUN, 0) AS JUN,
                            NVL(JUL, 0) AS JUL,
                            NVL(AGU, 0) AS AGU,
                            NVL(SEP, 0) AS SEP,
                            NVL(OKT, 0) AS OKT,
                            NVL(NOV, 0) AS NOV,
                            NVL(DES, 0) AS DES
                        FROM (
                            SELECT
                                B.DEVICE_ID,
                                C.DEVICE_NAME,
                                A.QTY,
                                TO_CHAR(A.CREATED_DATE, 'MM') AS BULAN
                            FROM SR01_KEU_TRANSCTR_IT A

                            LEFT JOIN SR01_KEU_LAYAN_MS B
                                ON B.LOKASI_ID = '001'
                                AND B.LAYAN_ID = A.LAYAN_ID

                            LEFT JOIN SR01_MED_DEVICE_MS C
                                ON C.LOKASI_ID = '001'
                                AND C.DEVICE_ID = B.DEVICE_ID

                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND TO_CHAR(A.CREATED_DATE,'YYYY') = '".$periode."'
                            AND B.DEVICE_ID IS NOT NULL

                            AND EXISTS
                            (
                                SELECT 1
                                FROM SR01_KEU_EPISODE E
                                WHERE E.LOKASI_ID = '001'
                                AND E.AKTIF = '1'
                                AND E.STATUS_EPISODE <> '99'
                                AND E.PASIEN_ID = A.PASIEN_ID
                                AND E.EPISODE_ID = A.EPISODE_ID
                            )
                        )
                        PIVOT (
                            SUM(QTY)
                            FOR BULAN IN (
                                '01' AS JAN,
                                '02' AS FEB,
                                '03' AS MAR,
                                '04' AS APR,
                                '05' AS MEI,
                                '06' AS JUN,
                                '07' AS JUL,
                                '08' AS AGU,
                                '09' AS SEP,
                                '10' AS OKT,
                                '11' AS NOV,
                                '12' AS DES
                            )
                        )
                        ORDER BY DEVICE_NAME
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datamasterlayan(){
            $query = "
                        SELECT A.LAYAN_ID, NAMA_LAYAN1,
                            C.DEVICE_ID, DEVICE_NAME

                        FROM SR01_KEU_LAYAN_MS A
                        LEFT JOIN SR01_MED_DEVICE_MS C
                        ON C.LOKASI_ID = '001'
                        AND C.DEVICE_ID = A.DEVICE_ID
                                                        
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        ORDER BY NAMA_LAYAN1 ASC
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function updatemapping($layanid,$data){           
            $sql =   $this->db->update("SR01_KEU_LAYAN_MS",$data,array("LAYAN_ID"=>$layanid));
            return $sql;
        }

    }
?>