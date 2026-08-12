<?php
    class Modeltindakanrj extends CI_Model{

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

        function datatindakanrj($periode){
            $query =
                    "
                        SELECT
                            LAYAN_ID,
                            NAMA_LAYAN1,
                            KODE_ICD,
                            LONG_DESCRIPTION,
                            UPPER(KETERANGAN) KETERANGAN,
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
                        FROM
                        (
                            SELECT
                                A.LAYAN_ID,
                                A.QTY,
                                TO_CHAR(A.CREATED_DATE, 'MM') AS BULAN,
                                 MSLAYAN.NAMA_LAYAN1, MSLAYAN.ICD_ID,
                                MSKAT.KETERANGAN,
                                ICD.KODE_ICD, ICD.LONG_DESCRIPTION
                                
                            FROM SR01_KEU_TRANSCTR_IT A
                            LEFT JOIN SR01_KEU_LAYAN_MS MSLAYAN
                            ON MSLAYAN.LOKASI_ID = '001'
                            AND MSLAYAN.LAYAN_ID = A.LAYAN_ID
                            LEFT JOIN SR01_KEU_JENISTR_MS MSKAT
                            ON MSKAT.LOKASI_ID = '001'
                            AND MSKAT.KATLYN_ID = MSLAYAN.KATEGORI_ID
                            LEFT JOIN SR01_MED_ICD9_MS ICD
                            ON ICD.SHOW_ITEM = '1'
                            AND ICD.KODE = MSLAYAN.ICD_ID
                                                    
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.LAYAN_ID NOT IN ('ADM00')
                            AND EXISTS
                            (
                                SELECT 1
                                FROM SR01_KEU_EPISODE E
                                WHERE E.LOKASI_ID      = '001'
                                AND E.AKTIF          = '1'
                                AND E.JENIS_EPISODE  = 'O'
                                AND E.STATUS_EPISODE <> '99'
                                AND TO_CHAR(E.TGL_MASUK,'YYYY') = '".$periode."'
                                AND E.PASIEN_ID      = A.PASIEN_ID
                                AND E.EPISODE_ID     = A.EPISODE_ID
                                AND
                                (
                                        (
                                            E.POLI_ID NOT IN
                                            (
                                                'UGD01',
                                                'APS R0000000001',
                                                'POLIFISIO',
                                                'POLIFISOKUP',
                                                'POLIFISWICARA',
                                                'HEMOD0000000000'
                                            )
                                            AND EXISTS
                                            (
                                                SELECT 1
                                                FROM SR01_MED_PRWT_TR T
                                                WHERE T.LOKASI_ID   = '001'
                                                AND T.AKTIF       = '1'
                                                AND T.DONE_STATUS = '01'
                                                AND T.STATUS      = '1'
                                                AND T.PASIEN_ID   = E.PASIEN_ID
                                                AND T.EPISODE_ID  = E.EPISODE_ID
                                            )
                                        )
                                        OR E.POLI_ID IN
                                        (
                                            'POLIFISIO',
                                            'POLIFISOKUP',
                                            'POLIFISWICARA',
                                            'HEMOD0000000000',
                                            'CAPD0000000001'
                                        )
                                )
                            )
                        )
                        PIVOT
                        (
                            SUM(QTY) FOR BULAN IN
                            (
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
                        ORDER BY KETERANGAN ASC, NAMA_LAYAN1 ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
        
    }
?>