<?php
    class Modeldashboard extends CI_Model{
        
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

        function datakunjunganigd($periode){
            $query = "
                        SELECT TO_CHAR(A.TGL_MASUK,'MM') AS BULAN, COUNT(*)JML
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.STATUS_EPISODE <> '99'
                        AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                        AND (
                            (A.JENIS_EPISODE = 'O' AND A.POLI_ID = 'UGD01')
                            OR
                            (A.JENIS_EPISODE = 'I' AND EXISTS (SELECT 1 FROM SR01_PASIEN_IGD B WHERE B.PASIEN_ID = A.PASIEN_ID AND B.EPISODE_ID = A.EPISODE_ID))
                        )
                        GROUP BY TO_CHAR(A.TGL_MASUK, 'MM')
                        ORDER BY BULAN ASC
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganrj($periode){
            $query = "
                        SELECT TO_CHAR(A.TGL_MASUK,'MM') AS BULAN, COUNT(*)JML
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'
                        AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                        AND A.POLI_ID NOT IN (
                            'APS',
                            'APS L0000000001',
                            'APS R0000000001',
                            'UGD01',
                            'UGD02',
                            'RUJUKANLUAR'
                        )
                        AND (
                            (
                                A.POLI_ID IN (
                                    'POLIFISIO',
                                    'POLIFISOKUP',
                                    'POLIFISWICARA',
                                    'HEMOD0000000000',
                                    'CAPD0000000001'
                                )
                                OR EXISTS (
                                    SELECT 1
                                    FROM SR01_MED_PRWT_TR T
                                    WHERE T.LOKASI_ID   = '001'
                                        AND T.AKTIF       = '1'
                                        AND T.DONE_STATUS = '01'
                                        AND T.STATUS      = '1'
                                        AND T.PASIEN_ID   = A.PASIEN_ID
                                        AND T.EPISODE_ID  = A.EPISODE_ID
                                )
                            )
                        )
                        GROUP BY TO_CHAR(A.TGL_MASUK, 'MM')
                        ORDER BY BULAN ASC
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganrjnonexe($periode){
            $query = "
                        SELECT TO_CHAR(A.TGL_MASUK,'MM') AS BULAN, COUNT(*)JML
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'
                        AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                        AND A.EPISODE_ID NOT IN (SELECT EPISODE_ID FROM WEB_CO_REGISTRASI_ONLINE_HD WHERE AKTIF='1' AND IS_EXE='Y' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        AND A.POLI_ID NOT IN (
                            'APS',
                            'APS L0000000001',
                            'APS R0000000001',
                            'UGD01',
                            'UGD02',
                            'RUJUKANLUAR'
                        )
                        AND (
                            (
                                A.POLI_ID IN (
                                    'POLIFISIO',
                                    'POLIFISOKUP',
                                    'POLIFISWICARA',
                                    'HEMOD0000000000',
                                    'CAPD0000000001'
                                )
                                OR EXISTS (
                                    SELECT 1
                                    FROM SR01_MED_PRWT_TR T
                                    WHERE T.LOKASI_ID   = '001'
                                        AND T.AKTIF       = '1'
                                        AND T.DONE_STATUS = '01'
                                        AND T.STATUS      = '1'
                                        AND T.PASIEN_ID   = A.PASIEN_ID
                                        AND T.EPISODE_ID  = A.EPISODE_ID
                                )
                            )
                        )
                        GROUP BY TO_CHAR(A.TGL_MASUK, 'MM')
                        ORDER BY BULAN ASC
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganrjexe($periode){
            $query = "
                        SELECT TO_CHAR(A.TGL_MASUK,'MM') AS BULAN, COUNT(*)JML
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'
                        AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                        AND A.EPISODE_ID = (SELECT EPISODE_ID FROM WEB_CO_REGISTRASI_ONLINE_HD WHERE AKTIF='1' AND IS_EXE='Y' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        AND A.POLI_ID NOT IN (
                            'APS',
                            'APS L0000000001',
                            'APS R0000000001',
                            'UGD01',
                            'UGD02',
                            'RUJUKANLUAR'
                        )
                        AND (
                            (
                                A.POLI_ID IN (
                                    'POLIFISIO',
                                    'POLIFISOKUP',
                                    'POLIFISWICARA',
                                    'HEMOD0000000000',
                                    'CAPD0000000001'
                                )
                                OR EXISTS (
                                    SELECT 1
                                    FROM SR01_MED_PRWT_TR T
                                    WHERE T.LOKASI_ID   = '001'
                                        AND T.AKTIF       = '1'
                                        AND T.DONE_STATUS = '01'
                                        AND T.STATUS      = '1'
                                        AND T.PASIEN_ID   = A.PASIEN_ID
                                        AND T.EPISODE_ID  = A.EPISODE_ID
                                )
                            )
                        )
                        GROUP BY TO_CHAR(A.TGL_MASUK, 'MM')
                        ORDER BY BULAN ASC
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganrjpoliklinik($periode){
            $query = "
                        SELECT *
                        FROM (
                            SELECT A.POLI_ID,
                                (SELECT KETERANGAN
                                FROM SR01_MED_POLI_MS P
                                WHERE P.POLI_ID = A.POLI_ID) AS POLIKLINIK,
                                TO_CHAR(A.TGL_MASUK,'MM') AS BULAN
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'O'
                            AND A.STATUS_EPISODE <> '99'
                            AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                            AND A.POLI_ID NOT IN (
                                'APS',
                                'APS L0000000001',
                                'APS R0000000001',
                                'UGD01',
                                'UGD02',
                                'RUJUKANLUAR'
                            )
                            AND (
                                (
                                    A.POLI_ID IN (
                                        'POLIFISIO',
                                        'POLIFISOKUP',
                                        'POLIFISWICARA',
                                        'HEMOD0000000000',
                                        'CAPD0000000001'
                                    )
                                    OR EXISTS (
                                        SELECT 1
                                        FROM SR01_MED_PRWT_TR T
                                        WHERE T.LOKASI_ID   = '001'
                                            AND T.AKTIF       = '1'
                                            AND T.DONE_STATUS = '01'
                                            AND T.STATUS      = '1'
                                            AND T.PASIEN_ID   = A.PASIEN_ID
                                            AND T.EPISODE_ID  = A.EPISODE_ID
                                    )
                                )
                            )
                        )
                        PIVOT (
                            COUNT(*) FOR BULAN IN (
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
                        ORDER BY POLIKLINIK
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganrjdokter($periode){
            $query = "
                        SELECT *
                        FROM (
                            SELECT
                                (SELECT UPPER(NAMA)
                                FROM SR01_MED_DOKTER_MS P
                                WHERE P.DOKTER_ID = A.DOKTER_ID) AS NAMADOKTER,
                                (SELECT KOLEGIUM FROM SR01_RMN_KOLEGIUM_MS WHERE KOLEGIUM_ID=(SELECT KOLEGIUM_ID FROM SR01_RMN_DOKTER_MS WHERE DOKTER_ID=A.DOKTER_ID))KOLEGIUM,
                                TO_CHAR(A.TGL_MASUK,'MM') AS BULAN
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'O'
                            AND A.STATUS_EPISODE <> '99'
                            AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                            AND A.POLI_ID NOT IN (
                                'APS',
                                'APS L0000000001',
                                'APS R0000000001',
                                'UGD01',
                                'UGD02',
                                'RUJUKANLUAR'
                            )
                            AND (
                                (
                                    A.POLI_ID IN (
                                        'POLIFISIO',
                                        'POLIFISOKUP',
                                        'POLIFISWICARA',
                                        'HEMOD0000000000',
                                        'CAPD0000000001'
                                    )
                                    OR EXISTS (
                                        SELECT 1
                                        FROM SR01_MED_PRWT_TR T
                                        WHERE T.LOKASI_ID   = '001'
                                            AND T.AKTIF       = '1'
                                            AND T.DONE_STATUS = '01'
                                            AND T.STATUS      = '1'
                                            AND T.PASIEN_ID   = A.PASIEN_ID
                                            AND T.EPISODE_ID  = A.EPISODE_ID
                                    )
                                )
                            )
                        )
                        PIVOT (
                            COUNT(*) FOR BULAN IN (
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
                        ORDER BY NAMADOKTER
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganrjprovider($periode){
            $query = "
                        SELECT *
                        FROM (
                            SELECT A.REKANAN_ID,
                                (SELECT NAMA
                                FROM SR01_KEU_REKANAN_MS P
                                WHERE P.REKANAN_ID = A.REKANAN_ID) AS PROVIDER,
                                TO_CHAR(A.TGL_MASUK,'MM') AS BULAN
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'O'
                            AND A.STATUS_EPISODE <> '99'
                            AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                            AND A.POLI_ID NOT IN (
                                'APS',
                                'APS L0000000001',
                                'APS R0000000001',
                                'UGD01',
                                'UGD02',
                                'RUJUKANLUAR'
                            )
                            AND (
                                (
                                    A.POLI_ID IN (
                                        'POLIFISIO',
                                        'POLIFISOKUP',
                                        'POLIFISWICARA',
                                        'HEMOD0000000000',
                                        'CAPD0000000001'
                                    )
                                    OR EXISTS (
                                        SELECT 1
                                        FROM SR01_MED_PRWT_TR T
                                        WHERE T.LOKASI_ID   = '001'
                                            AND T.AKTIF       = '1'
                                            AND T.DONE_STATUS = '01'
                                            AND T.STATUS      = '1'
                                            AND T.PASIEN_ID   = A.PASIEN_ID
                                            AND T.EPISODE_ID  = A.EPISODE_ID
                                    )
                                )
                            )
                        )
                        PIVOT (
                            COUNT(*) FOR BULAN IN (
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
                        ORDER BY PROVIDER
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganmcu($periode){
            $query = "
                        SELECT TO_CHAR(A.TGL_MASUK,'MM') AS BULAN, COUNT(*)JML
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'
                        AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                        AND A.POLI_ID='MEDIC0000000000'
                        AND A.POLI_ID NOT IN (
                            'APS',
                            'APS L0000000001',
                            'APS R0000000001',
                            'UGD01',
                            'UGD02',
                            'RUJUKANLUAR'
                        )
                        AND (
                            (
                                A.POLI_ID IN (
                                    'POLIFISIO',
                                    'POLIFISOKUP',
                                    'POLIFISWICARA',
                                    'HEMOD0000000000',
                                    'CAPD0000000001'
                                )
                                OR EXISTS (
                                    SELECT 1
                                    FROM SR01_MED_PRWT_TR T
                                    WHERE T.LOKASI_ID   = '001'
                                        AND T.AKTIF       = '1'
                                        AND T.DONE_STATUS = '01'
                                        AND T.STATUS      = '1'
                                        AND T.PASIEN_ID   = A.PASIEN_ID
                                        AND T.EPISODE_ID  = A.EPISODE_ID
                                )
                            )
                        )
                        GROUP BY TO_CHAR(A.TGL_MASUK, 'MM')
                        ORDER BY BULAN ASC
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganri($periode){
            $query = "
                        SELECT TO_CHAR(A.TGL_MASUK,'MM') AS BULAN, COUNT(*)JML
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'I'
                        AND A.STATUS_EPISODE <> '99'
                        AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                        GROUP BY TO_CHAR(A.TGL_MASUK, 'MM')
                        ORDER BY BULAN ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        
    }
?>