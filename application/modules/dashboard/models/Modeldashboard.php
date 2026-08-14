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

        function datakunjungandokterigd($periode){
            $query = "
                        SELECT
                            NAMADOKTER,
                            NVL(JAN,0) AS JAN,
                            NVL(FEB,0) AS FEB,
                            NVL(MAR,0) AS MAR,
                            NVL(APR,0) AS APR,
                            NVL(MEI,0) AS MEI,
                            NVL(JUN,0) AS JUN,
                            NVL(JUL,0) AS JUL,
                            NVL(AGU,0) AS AGU,
                            NVL(SEP,0) AS SEP,
                            NVL(OKT,0) AS OKT,
                            NVL(NOV,0) AS NOV,
                            NVL(DES,0) AS DES
                        FROM
                        (
                            SELECT
                                SR01_GET_DOKTER_IGD_AKHIR(A.EPISODE_ID) AS NAMADOKTER,
                                TO_CHAR(A.TGL_MASUK,'MM') AS BULAN
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.STATUS_EPISODE <> '99'
                            AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                            AND (
                                    (
                                        A.JENIS_EPISODE = 'O'
                                        AND A.POLI_ID = 'UGD01'
                                    )
                                    OR
                                    (
                                        A.JENIS_EPISODE = 'I'
                                        AND EXISTS (
                                            SELECT 1
                                            FROM SR01_PASIEN_IGD B
                                            WHERE B.PASIEN_ID = A.PASIEN_ID
                                            AND B.EPISODE_ID = A.EPISODE_ID
                                        )
                                    )
                            )
                        ) SRC
                        PIVOT
                        (
                            COUNT(*)
                            FOR BULAN IN
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
                        ORDER BY NAMADOKTER
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganigdprovider($periode){
            $query = "
                        SELECT *
                        FROM (
                            SELECT A.REKANAN_ID,
                                (SELECT UPPER(NAMA) FROM SR01_KEU_REKANAN_MS P WHERE P.REKANAN_ID = A.REKANAN_ID) AS PROVIDER,
                                TO_CHAR(A.TGL_MASUK,'MM') AS BULAN
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.STATUS_EPISODE <> '99'
                            AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                            AND (
                                (A.JENIS_EPISODE = 'O' AND A.POLI_ID = 'UGD01')
                                OR (A.JENIS_EPISODE ='I' AND A.EPISODE_ID = (SELECT EPISODE_ID FROM SR01_PASIEN_IGD WHERE PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID))
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
                                            WHERE T.LOKASI_ID   = '001'
                                            AND T.AKTIF       = '1'
                                            AND T.DONE_STATUS = '01'
                                            AND T.STATUS      = '1'
                                            AND T.PASIEN_ID   = A.PASIEN_ID
                                            AND T.EPISODE_ID  = A.EPISODE_ID
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
                                            WHERE T.LOKASI_ID   = '001'
                                            AND T.AKTIF       = '1'
                                            AND T.DONE_STATUS = '01'
                                            AND T.STATUS      = '1'
                                            AND T.PASIEN_ID   = A.PASIEN_ID
                                            AND T.EPISODE_ID  = A.EPISODE_ID
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

        function datakunjunganrjdokter($periode){
            $query = "
                        SELECT *
                        FROM (
                            SELECT
                                (SELECT UPPER(NAMA)
                                FROM SR01_MED_DOKTER_MS P
                                WHERE P.DOKTER_ID = A.DOKTER_ID) AS NAMADOKTER,
                                TO_CHAR(A.TGL_MASUK,'MM') AS BULAN
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'O'
                            AND A.STATUS_EPISODE <> '99'
                            AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
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
                                            WHERE T.LOKASI_ID   = '001'
                                            AND T.AKTIF       = '1'
                                            AND T.DONE_STATUS = '01'
                                            AND T.STATUS      = '1'
                                            AND T.PASIEN_ID   = A.PASIEN_ID
                                            AND T.EPISODE_ID  = A.EPISODE_ID
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

        //Non Executive

        function datakunjunganrjnonexecutivepoliklinik($periode){
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
                                            WHERE T.LOKASI_ID   = '001'
                                            AND T.AKTIF       = '1'
                                            AND T.DONE_STATUS = '01'
                                            AND T.STATUS      = '1'
                                            AND T.PASIEN_ID   = A.PASIEN_ID
                                            AND T.EPISODE_ID  = A.EPISODE_ID
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
                            AND EPISODE_ID NOT IN (
                                SELECT DISTINCT EPISODE_ID
                                FROM SR01_KEU_TRANSCTR_IT
                                WHERE LOKASI_ID='001'
                                AND   AKTIF='1'
                                AND   LAYAN_ID IN
                                        (
                                            SELECT NILAI
                                            FROM SR01_SYS_PARAMETER
                                            WHERE PARAM_ID IN (
                                            'ADMEXE001',
                                            'ADMEXE002',
                                            'ADMEXE003',
                                            'ADMEXE004',
                                            'ADMEXE005',
                                            'ADMEXE006',
                                            'ADMEXE007',
                                            'ADMEXE008',
                                            'ADMEXE009',
                                            'ADMEXE010'
                                            )
                                            AND PASIEN_ID=A.PASIEN_ID
                                            AND EPISODE_ID=A.EPISODE_ID
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

        function datakunjunganrjdokternonexecutive($periode){
            $query = "
                        SELECT *
                        FROM (
                            SELECT
                                (SELECT UPPER(NAMA)
                                FROM SR01_MED_DOKTER_MS P
                                WHERE P.DOKTER_ID = A.DOKTER_ID) AS NAMADOKTER,
                                TO_CHAR(A.TGL_MASUK,'MM') AS BULAN
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'O'
                            AND A.STATUS_EPISODE <> '99'
                            AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
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
                                            WHERE T.LOKASI_ID   = '001'
                                            AND T.AKTIF       = '1'
                                            AND T.DONE_STATUS = '01'
                                            AND T.STATUS      = '1'
                                            AND T.PASIEN_ID   = A.PASIEN_ID
                                            AND T.EPISODE_ID  = A.EPISODE_ID
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
                            AND EPISODE_ID NOT IN (
                                SELECT DISTINCT EPISODE_ID
                                FROM SR01_KEU_TRANSCTR_IT
                                WHERE LOKASI_ID='001'
                                AND   AKTIF='1'
                                AND   LAYAN_ID IN
                                        (
                                            SELECT NILAI
                                            FROM SR01_SYS_PARAMETER
                                            WHERE PARAM_ID IN (
                                            'ADMEXE001',
                                            'ADMEXE002',
                                            'ADMEXE003',
                                            'ADMEXE004',
                                            'ADMEXE005',
                                            'ADMEXE006',
                                            'ADMEXE007',
                                            'ADMEXE008',
                                            'ADMEXE009',
                                            'ADMEXE010'
                                            )
                                            AND PASIEN_ID=A.PASIEN_ID
                                            AND EPISODE_ID=A.EPISODE_ID
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

        function datakunjunganrjnonexecutiveprovider($periode){
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
                                            WHERE T.LOKASI_ID   = '001'
                                            AND T.AKTIF       = '1'
                                            AND T.DONE_STATUS = '01'
                                            AND T.STATUS      = '1'
                                            AND T.PASIEN_ID   = A.PASIEN_ID
                                            AND T.EPISODE_ID  = A.EPISODE_ID
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
                            AND EPISODE_ID NOT IN (
                                SELECT DISTINCT EPISODE_ID
                                FROM SR01_KEU_TRANSCTR_IT
                                WHERE LOKASI_ID='001'
                                AND   AKTIF='1'
                                AND   LAYAN_ID IN
                                        (
                                            SELECT NILAI
                                            FROM SR01_SYS_PARAMETER
                                            WHERE PARAM_ID IN (
                                            'ADMEXE001',
                                            'ADMEXE002',
                                            'ADMEXE003',
                                            'ADMEXE004',
                                            'ADMEXE005',
                                            'ADMEXE006',
                                            'ADMEXE007',
                                            'ADMEXE008',
                                            'ADMEXE009',
                                            'ADMEXE010'
                                            )
                                            AND PASIEN_ID=A.PASIEN_ID
                                            AND EPISODE_ID=A.EPISODE_ID
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

        //Executive
        function datakunjunganrjexecutivepoliklinik($periode){
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
                                            WHERE T.LOKASI_ID   = '001'
                                            AND T.AKTIF       = '1'
                                            AND T.DONE_STATUS = '01'
                                            AND T.STATUS      = '1'
                                            AND T.PASIEN_ID   = A.PASIEN_ID
                                            AND T.EPISODE_ID  = A.EPISODE_ID
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
                            AND EPISODE_ID IN (
                                SELECT DISTINCT EPISODE_ID
                                FROM SR01_KEU_TRANSCTR_IT
                                WHERE LOKASI_ID='001'
                                AND   AKTIF='1'
                                AND   LAYAN_ID IN
                                        (
                                            SELECT NILAI
                                            FROM SR01_SYS_PARAMETER
                                            WHERE PARAM_ID IN (
                                            'ADMEXE001',
                                            'ADMEXE002',
                                            'ADMEXE003',
                                            'ADMEXE004',
                                            'ADMEXE005',
                                            'ADMEXE006',
                                            'ADMEXE007',
                                            'ADMEXE008',
                                            'ADMEXE009',
                                            'ADMEXE010'
                                            )
                                            AND PASIEN_ID=A.PASIEN_ID
                                            AND EPISODE_ID=A.EPISODE_ID
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

        function datakunjunganrjdokterexecutive($periode){
            $query = "
                        SELECT *
                        FROM (
                            SELECT
                                (SELECT UPPER(NAMA)
                                FROM SR01_MED_DOKTER_MS P
                                WHERE P.DOKTER_ID = A.DOKTER_ID) AS NAMADOKTER,
                                TO_CHAR(A.TGL_MASUK,'MM') AS BULAN
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'O'
                            AND A.STATUS_EPISODE <> '99'
                            AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
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
                                            WHERE T.LOKASI_ID   = '001'
                                            AND T.AKTIF       = '1'
                                            AND T.DONE_STATUS = '01'
                                            AND T.STATUS      = '1'
                                            AND T.PASIEN_ID   = A.PASIEN_ID
                                            AND T.EPISODE_ID  = A.EPISODE_ID
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
                            AND EPISODE_ID IN (
                                SELECT DISTINCT EPISODE_ID
                                FROM SR01_KEU_TRANSCTR_IT
                                WHERE LOKASI_ID='001'
                                AND   AKTIF='1'
                                AND   LAYAN_ID IN
                                        (
                                            SELECT NILAI
                                            FROM SR01_SYS_PARAMETER
                                            WHERE PARAM_ID IN (
                                            'ADMEXE001',
                                            'ADMEXE002',
                                            'ADMEXE003',
                                            'ADMEXE004',
                                            'ADMEXE005',
                                            'ADMEXE006',
                                            'ADMEXE007',
                                            'ADMEXE008',
                                            'ADMEXE009',
                                            'ADMEXE010'
                                            )
                                            AND PASIEN_ID=A.PASIEN_ID
                                            AND EPISODE_ID=A.EPISODE_ID
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

        function datakunjunganrjexecutiveprovider($periode){
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
                                            WHERE T.LOKASI_ID   = '001'
                                            AND T.AKTIF       = '1'
                                            AND T.DONE_STATUS = '01'
                                            AND T.STATUS      = '1'
                                            AND T.PASIEN_ID   = A.PASIEN_ID
                                            AND T.EPISODE_ID  = A.EPISODE_ID
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
                            AND EPISODE_ID IN (
                                SELECT DISTINCT EPISODE_ID
                                FROM SR01_KEU_TRANSCTR_IT
                                WHERE LOKASI_ID='001'
                                AND   AKTIF='1'
                                AND   LAYAN_ID IN
                                        (
                                            SELECT NILAI
                                            FROM SR01_SYS_PARAMETER
                                            WHERE PARAM_ID IN (
                                            'ADMEXE001',
                                            'ADMEXE002',
                                            'ADMEXE003',
                                            'ADMEXE004',
                                            'ADMEXE005',
                                            'ADMEXE006',
                                            'ADMEXE007',
                                            'ADMEXE008',
                                            'ADMEXE009',
                                            'ADMEXE010'
                                            )
                                            AND PASIEN_ID=A.PASIEN_ID
                                            AND EPISODE_ID=A.EPISODE_ID
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

        function datakunjunganriprovider($periode){
            $query = "
                        SELECT *
                        FROM (
                            SELECT A.REKANAN_ID,
                                (SELECT UPPER(NAMA) FROM SR01_KEU_REKANAN_MS P WHERE P.REKANAN_ID = A.REKANAN_ID) AS PROVIDER,
                                TO_CHAR(A.TGL_MASUK,'MM') AS BULAN
                                
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'I'
                            AND A.STATUS_EPISODE <> '99'
                            AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
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

        function datakunjunganridokter($periode){
            $query = "
                        SELECT *
                            FROM (
                                SELECT
                                    (SELECT UPPER(NAMA) FROM SR01_MED_DOKTER_MS P WHERE P.DOKTER_ID = A.DOKTER_ID) AS NAMADOKTER,
                                    TO_CHAR(A.TGL_MASUK,'MM') AS BULAN
                                FROM SR01_KEU_EPISODE A
                                WHERE A.LOKASI_ID = '001'
                                AND A.AKTIF = '1'
                                AND A.JENIS_EPISODE = 'I'
                                AND A.STATUS_EPISODE <> '99'
                                AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
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

        function datapaketmcu($periode){
            $query = "
                        SELECT
                            NAMAPAKET,
                            JAN,
                            FEB,
                            MAR,
                            APR,
                            MEI,
                            JUN,
                            JUL,
                            AGU,
                            SEP,
                            OKT,
                            NOV,
                            DES
                        FROM (
                            SELECT
                                TO_CHAR(A.TGL_MASUK, 'MM') AS BULAN,
                                NVL(
                                    (
                                        SELECT L.NAMA_LAYAN1
                                        FROM SR01_KEU_LAYAN_MS L
                                        WHERE L.LAYAN_ID = (
                                            SELECT M.LAYAN_ID_MCU
                                            FROM SR01_KEU_TRANSCTR_MCU M
                                            WHERE M.LOKASI_ID = '001'
                                            AND M.AKTIF = '1'
                                            AND M.PASIEN_ID = A.PASIEN_ID
                                            AND M.EPISODE_ID = A.EPISODE_ID
                                            AND EXISTS (
                                                SELECT 1
                                                FROM SR01_KEU_LAYAN_MS LM
                                                WHERE LM.LAYAN_ID = M.LAYAN_ID_MCU
                                                    AND LM.KATEGORI_ID = 'JKL-MCU'
                                            )
                                            AND ROWNUM = 1
                                        )
                                    ),
                                    'NON PAKET'
                                ) AS NAMAPAKET
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'O'
                            AND A.STATUS_EPISODE <> '99'
                            AND A.POLI_ID = 'MEDIC0000000000'
                            AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
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
                        ORDER BY
                            CASE
                                WHEN NAMAPAKET = 'NON PAKET' THEN 1
                                ELSE 0
                            END,
                            NAMAPAKET ASC
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganmcuprovider($periode){
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
                            AND A.POLI_ID = 'MEDIC0000000000'
                            AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
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
                                            WHERE T.LOKASI_ID   = '001'
                                            AND T.AKTIF       = '1'
                                            AND T.DONE_STATUS = '01'
                                            AND T.STATUS      = '1'
                                            AND T.PASIEN_ID   = A.PASIEN_ID
                                            AND T.EPISODE_ID  = A.EPISODE_ID
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
        
    }
?>