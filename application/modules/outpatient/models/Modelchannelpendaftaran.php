<?php
    class Modelchannelpendaftaran extends CI_Model{

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

        function datachannelpendaftaranrj($periode){
            $query = "
                        SELECT *
                        FROM (
                            SELECT 
                                CASE
                                    WHEN P.CREATED_BY = 'ONTHESPOT' THEN 'ON THE SPOT'
                                    WHEN P.CREATED_BY = 'OTS' THEN 'ON THE SPOT'
                                    WHEN P.CREATED_BY = 'RESUME-KONTROL' THEN 'DOKTER'
                                    WHEN P.CREATED_BY = 'NURSE-STATION' THEN 'PERAWAT / BIDAN'
                                    WHEN P.CREATED_BY LIKE 'SIRS01_%' THEN 'ON THE SPOT'
                                    WHEN P.CREATED_BY LIKE 'PRANAP_%' THEN 'POST RANAP'
                                    ELSE P.CREATED_BY
                                END AS CHANNEL,
                                
                                TO_CHAR(A.TGL_MASUK, 'MM') AS BULAN
                                
                            FROM SR01_KEU_EPISODE A
                            
                            LEFT JOIN WEB_CO_REGISTRASI_ONLINE_HD P
                                ON P.PASIEN_ID = A.PASIEN_ID
                                AND P.EPISODE_ID = A.EPISODE_ID
                            
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'O'
                            AND A.STATUS_EPISODE <> '99'
                            AND TO_CHAR(A.TGL_MASUK, 'YYYY') = '".$periode."'
                            
                            AND (
                                (
                                    A.POLI_ID NOT IN (
                                        'UGD01',
                                        'APS R0000000001',
                                        'POLIFISIO',
                                        'POLIFISOKUP',
                                        'POLIFISWICARA',
                                        'HEMOD0000000000'
                                    )
                                    
                                    AND EXISTS (
                                        SELECT 1
                                        FROM SR01_MED_PRWT_TR T
                                        WHERE T.LOKASI_ID = '001'
                                        AND T.AKTIF = '1'
                                        AND T.DONE_STATUS = '01'
                                        AND T.STATUS = '1'
                                        AND T.PASIEN_ID = A.PASIEN_ID
                                        AND T.EPISODE_ID = A.EPISODE_ID
                                    )
                                )
                                
                                OR A.POLI_ID IN (
                                    'POLIFISIO',
                                    'POLIFISOKUP',
                                    'POLIFISWICARA',
                                    'HEMOD0000000000',
                                    'CAPD0000000001'
                                )
                            )
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
                                '08' AS AGU,
                                '09' AS SEP,
                                '10' AS OKT,
                                '11' AS NOV,
                                '12' AS DES
                            )
                        )
                        ORDER BY CHANNEL
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
        
    }
?>