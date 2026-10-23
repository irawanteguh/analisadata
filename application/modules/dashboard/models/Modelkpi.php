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

        function radiologi($periode){
            $query =
                    "
                        
                        SELECT
                            TO_CHAR(A.RADIOGRAFER_DATETIME_START, 'MM') AS BULAN,
                            COUNT(*) AS JML_PEMERIKSAAN,
                            SUM(CASE WHEN (A.RADIOLOG_DATETIME_END - A.RADIOGRAFER_DATETIME_START) * 1440 <= 60 THEN 1 ELSE 0 END) AS JML_TERCAPAI,
                            SUM(CASE WHEN (A.RADIOLOG_DATETIME_END - A.RADIOGRAFER_DATETIME_START) * 1440 > 60 THEN 1 ELSE 0 END) AS JML_TIDAK_TERCAPAI,
                            ROUND(SUM(CASE WHEN (A.RADIOLOG_DATETIME_END - A.RADIOGRAFER_DATETIME_START) * 1440 <= 60 THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) AS PERSEN_TERCAPAI,
                            ROUND(SUM(CASE WHEN (A.RADIOLOG_DATETIME_END - A.RADIOGRAFER_DATETIME_START) * 1440 > 60 THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) AS PERSEN_TIDAK_TERCAPAI
                        FROM RAD_MANAGER.RIS_OUT A
                        WHERE EXISTS (
                            SELECT 1
                            FROM RAD_MANAGER.RIS_IN B
                            WHERE B.KODE_PEMERIKSAAN = 'RAD010'
                            AND B.NO_RONTGEN = A.NO_RONTGEN
                        )
                        AND   A.RADIOGRAFER_DATETIME_START IS NOT NULL
                        AND   A.RADIOLOG_DATETIME_END IS NOT NULL
                        AND   TO_CHAR(A.RADIOGRAFER_DATETIME_START,'YYYY')='".$periode."'
                        GROUP BY TO_CHAR(A.RADIOGRAFER_DATETIME_START, 'MM')
                        ORDER BY BULAN
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function rawatinap($periode){
            $query =
                    "
                        
                        SELECT X.*,
                            NVL(ROUND((SEBELUM12ALL / NULLIF(JUMLAHALL, 0)) * 100, 2), 0) AS PRESENTASIALL,
                            NVL(ROUND((SEBELUM12TUTD / NULLIF(JMLTUTD, 0)) * 100, 2), 0) AS PRESENTASITUTD,
                            NVL(ROUND(((SEBELUM12ALL - SEBELUM12TUTD) / NULLIF((JUMLAHALL - JMLTUTD), 0)) * 100, 2), 0) AS PRESENTASINONTUTD,
                            
                            JUMLAHALL-JMLTUTD JMLNONTUTD,
                            SEBELUM12ALL-SEBELUM12TUTD SEBELUM12NONTUTD,
                            SESUDAH12ALL-SESUDAH12TUTD SESUDAH12NONTUTD
                        FROM(
                            SELECT TO_CHAR(A.TGL_KELUAR, 'MM') AS BULAN,
                                COUNT(*) AS JUMLAHALL,
                                SUM(CASE WHEN TO_CHAR(A.TGL_KELUAR, 'HH24') < '12' THEN 1 ELSE 0 END) AS SEBELUM12ALL,
                                SUM(CASE WHEN TO_CHAR(A.TGL_KELUAR, 'HH24') >= '12' THEN 1 ELSE 0 END) AS SESUDAH12ALL,
                                SUM(CASE WHEN A.EPISODE_ID = (SELECT EPISODE_ID FROM SR01_PASIEN_RANAP B WHERE B.PERLU_VISIT = 'T' AND B.PASIEN_ID = A.PASIEN_ID AND B.EPISODE_ID = A.EPISODE_ID) THEN 1 ELSE 0 END) AS JMLTUTD,
                                SUM(CASE WHEN TO_CHAR(A.TGL_KELUAR, 'HH24') < '12' AND A.EPISODE_ID = (SELECT EPISODE_ID FROM SR01_PASIEN_RANAP B WHERE B.PERLU_VISIT = 'T' AND B.PASIEN_ID = A.PASIEN_ID AND B.EPISODE_ID = A.EPISODE_ID) THEN 1 ELSE 0 END) AS SEBELUM12TUTD,
                                SUM(CASE WHEN TO_CHAR(A.TGL_KELUAR, 'HH24') >= '12' AND A.EPISODE_ID = (SELECT EPISODE_ID FROM SR01_PASIEN_RANAP B WHERE B.PERLU_VISIT = 'T' AND B.PASIEN_ID = A.PASIEN_ID AND B.EPISODE_ID = A.EPISODE_ID) THEN 1 ELSE 0 END) AS SESUDAH12TUTD
                                
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND   A.AKTIF = '1'
                            AND   A.JENIS_EPISODE = 'I'
                            AND   A.PULANG_ID NOT IN ('P02', 'P04', 'P10', 'P11', 'P0X')
                            AND   A.KELAS_ID <> 'V'
                            AND   A.RUANGRWT_ID NOT LIKE 'CVC%'
                            AND   A.RUANGRWT_ID NOT LIKE 'IC%'
                            AND   A.RUANGRWT_ID NOT LIKE 'KBY%'
                            AND   A.RUANGRWT_ID NOT LIKE 'PER%'
                            AND   A.TGL_KELUAR IS NOT NULL
                            AND   TO_CHAR(A.TGL_KELUAR,'YYYY')='".$periode."'
                            GROUP BY TO_CHAR(A.TGL_KELUAR, 'MM')
                            ORDER BY BULAN
                        )X
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function rawatjalanwaktutunggu($periode){
            $query =
                    "
                        
                        WITH W_EPISODE AS (
                        SELECT
                            W.PASIEN_ID,
                            W.EPISODE_ID,
                            W.POLI_ID,
                            W.DOKTER_ID,
                            MIN(W.CREATED_DATE) AS MULAIPERIKSA
                        FROM WEB_CO_MULAI_PERIKSA W
                        WHERE W.SHOW_ITEM = '1'
                        AND   TO_CHAR(W.CREATED_DATE,'YYYY')='".$periode."'
                        GROUP BY
                            W.PASIEN_ID,
                            W.EPISODE_ID,
                            W.POLI_ID,
                            W.DOKTER_ID
                    ),
                    W_PREV AS (
                        SELECT
                            W.PASIEN_ID,
                            W.EPISODE_ID,
                            W.POLI_ID,
                            W.DOKTER_ID,
                            W.MULAIPERIKSA,
                            LAG(W.EPISODE_ID) OVER (
                                PARTITION BY
                                    W.POLI_ID,
                                    W.DOKTER_ID,
                                    TRUNC(W.MULAIPERIKSA)
                                ORDER BY
                                    W.MULAIPERIKSA,
                                    W.EPISODE_ID
                            ) AS EPISODE_SEBELUMNYA
                        FROM W_EPISODE W
                    ),
                    DATA_AWAL AS (
                        SELECT
                            A.PASIEN_ID,
                            A.EPISODE_ID,
                            A.POLI_ID,
                            A.DOKTER_ID,
                            A.TGL_MASUK,
                            (
                                SELECT MIN(AN.CREATED_DATE)
                                FROM SR01_MED_ANAMAWAL AN
                                WHERE AN.PASIEN_ID = A.PASIEN_ID
                                AND AN.EPISODE_ID = A.EPISODE_ID
                            ) AS TGLJAMSEP,
                            W.MULAIPERIKSA,
                            W.EPISODE_SEBELUMNYA
                        FROM SR01_KEU_EPISODE A
                        LEFT JOIN W_PREV W
                            ON W.PASIEN_ID = A.PASIEN_ID
                        AND W.EPISODE_ID = A.EPISODE_ID
                        AND W.POLI_ID = A.POLI_ID
                        AND W.DOKTER_ID = A.DOKTER_ID
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'
                        AND TO_CHAR(A.TGL_MASUK,'YYYY')='".$periode."'
                        AND A.POLI_ID NOT IN (
                            'APS',
                            'APS L0000000001',
                            'APS R0000000001',
                            'UGD01',
                            'UGD02',
                            'RUJUKANLUAR',
                            'MEDIC0000000000',
                            'POLI0000000000',
                            'POLI0000000043',
                            'POLI0000000042',
                            'POLI0000000041',
                            'POLI0000000044',
                            'POLI0000000052',
                            'POLI0000000031',
                            'HEMOD0000000000',
                            'POLI0000000025',
                            'POLIFISIO',
                            'POLIFISWICARA',
                            'POLIFISOKUP',
                            'CAPD0000000001'
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
                        AND NOT EXISTS (
                            SELECT 1
                            FROM SR01_KEU_EPISODE B
                            WHERE B.LOKASI_ID = A.LOKASI_ID
                                AND B.PASIEN_ID = A.PASIEN_ID
                                AND B.AKTIF = '1'
                                AND B.JENIS_EPISODE = 'O'
                                AND B.STATUS_EPISODE <> '99'
                                AND TO_CHAR(B.TGL_MASUK,'YYYY')='".$periode."'
                                AND B.TGL_MASUK = A.TGL_MASUK
                                AND B.POLI_ID <> A.POLI_ID
                                AND B.POLI_ID NOT IN (
                                    'APS',
                                    'APS L0000000001',
                                    'APS R0000000001',
                                    'UGD01',
                                    'UGD02',
                                    'RUJUKANLUAR',
                                    'MEDIC0000000000',
                                    'POLI0000000000',
                                    'POLI0000000043',
                                    'POLI0000000042',
                                    'POLI0000000041',
                                    'POLI0000000044',
                                    'POLI0000000052',
                                    'POLI0000000031',
                                    'HEMOD0000000000',
                                    'POLI0000000025',
                                    'POLIFISIO',
                                    'POLIFISWICARA',
                                    'POLIFISOKUP',
                                    'CAPD0000000001'
                                )
                                AND EXISTS (
                                    SELECT 1
                                    FROM SR01_MED_PRWT_TR T
                                    WHERE T.LOKASI_ID = '001'
                                    AND T.AKTIF = '1'
                                    AND T.DONE_STATUS = '01'
                                    AND T.STATUS = '1'
                                    AND T.PASIEN_ID = B.PASIEN_ID
                                    AND T.EPISODE_ID = B.EPISODE_ID
                                )
                        )
                    ),
                    DATA_VALID AS (
                        SELECT Q.*
                        FROM DATA_AWAL Q
                        WHERE NOT EXISTS (
                            SELECT 1
                            FROM WEB_CO_ALKES_DT K
                            WHERE K.EPISODE_ID = Q.EPISODE_SEBELUMNYA
                        )
                    ),
                    DATA_HITUNG AS (
                        SELECT
                            TO_CHAR(TGL_MASUK, 'MM') AS BULAN,
                            PASIEN_ID,
                            EPISODE_ID,
                            TGLJAMSEP,
                            MULAIPERIKSA,
                            CASE
                                WHEN TGLJAMSEP IS NOT NULL
                                AND MULAIPERIKSA IS NOT NULL
                                THEN (MULAIPERIKSA - TGLJAMSEP) * 1440
                            END AS SELISIH_MENIT
                        FROM DATA_VALID
                    )
                    SELECT
                        BULAN,
                        COUNT(*) AS TOTAL_PASIEN,
                        SUM(
                            CASE
                                WHEN SELISIH_MENIT <= 60 THEN 1
                                ELSE 0
                            END
                        ) AS JUMLAH_PASIEN_KURANG_SAMA_60_MENIT,
                        SUM(
                            CASE
                                WHEN SELISIH_MENIT > 60 THEN 1
                                ELSE 0
                            END
                        ) AS JUMLAH_PASIEN_LEBIH_60_MENIT,
                        ROUND(
                            100 * SUM(
                                CASE
                                    WHEN SELISIH_MENIT <= 60 THEN 1
                                    ELSE 0
                                END
                            ) / NULLIF(
                                SUM(
                                    CASE
                                        WHEN SELISIH_MENIT IS NOT NULL THEN 1
                                        ELSE 0
                                    END
                                ),
                                0
                            ),
                            2
                        ) AS PERSENTASE_PASIEN_KURANG_SAMA_60_MENIT
                    FROM DATA_HITUNG
                    GROUP BY BULAN
                    ORDER BY BULAN
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function rawatjalanlamapelayanan($periode){
            $query =
                    "
                        
                        WITH W_EPISODE AS (
                            SELECT
                                W.PASIEN_ID,
                                W.EPISODE_ID,
                                W.POLI_ID,
                                W.DOKTER_ID,
                                MIN(W.CREATED_DATE) AS MULAIPERIKSA
                            FROM WEB_CO_MULAI_PERIKSA W
                            WHERE W.SHOW_ITEM = '1'
                            AND   TO_CHAR(W.CREATED_DATE,'YYYY')='".$periode."'
                            GROUP BY
                                W.PASIEN_ID,
                                W.EPISODE_ID,
                                W.POLI_ID,
                                W.DOKTER_ID
                        ),
                        W_PREV AS (
                            SELECT
                                W.PASIEN_ID,
                                W.EPISODE_ID,
                                W.POLI_ID,
                                W.DOKTER_ID,
                                W.MULAIPERIKSA,
                                LAG(W.EPISODE_ID) OVER (
                                    PARTITION BY
                                        W.POLI_ID,
                                        W.DOKTER_ID,
                                        TRUNC(W.MULAIPERIKSA)
                                    ORDER BY
                                        W.MULAIPERIKSA,
                                        W.EPISODE_ID
                                ) AS EPISODE_SEBELUMNYA
                            FROM W_EPISODE W
                        ),
                        DATA_AWAL AS (
                            SELECT
                                A.PASIEN_ID,
                                A.EPISODE_ID,
                                A.POLI_ID,
                                A.DOKTER_ID,
                                A.TGL_MASUK,
                                CASE
                                    WHEN NOT EXISTS (
                                        SELECT 1
                                        FROM WEB_CO_RESEP_DT R
                                        WHERE R.PASIEN_ID = A.PASIEN_ID
                                        AND R.EPISODE_ID = A.EPISODE_ID
                                    ) THEN 'TIDAK ADA RESEP'

                                    WHEN EXISTS (
                                        SELECT 1
                                        FROM WEB_CO_RESEP_DT R
                                        WHERE R.PASIEN_ID = A.PASIEN_ID
                                        AND R.EPISODE_ID = A.EPISODE_ID
                                        AND R.TYPE <> '00'
                                    ) THEN 'RACIKAN'

                                    ELSE 'NON RACIKAN'
                                END AS STATUS_RESEP,
                                (
                                    SELECT MIN(AN.CREATED_DATE)
                                    FROM SR01_MED_ANAMAWAL AN
                                    WHERE AN.PASIEN_ID = A.PASIEN_ID
                                    AND AN.EPISODE_ID = A.EPISODE_ID
                                ) AS TGLJAMSEP,
                                (
                                    SELECT MIN(F.TGL_SELESAI)
                                    FROM SR01_WORKLIST_FRM F
                                    WHERE F.PASIEN_ID = A.PASIEN_ID
                                    AND F.EPISODE_ID = A.EPISODE_ID
                                ) AS TGL_SELESAI,
                                W.MULAIPERIKSA,
                                W.EPISODE_SEBELUMNYA
                            FROM SR01_KEU_EPISODE A
                            LEFT JOIN W_PREV W
                                ON W.PASIEN_ID = A.PASIEN_ID
                            AND W.EPISODE_ID = A.EPISODE_ID
                            AND W.POLI_ID = A.POLI_ID
                            AND W.DOKTER_ID = A.DOKTER_ID
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'O'
                            AND A.STATUS_EPISODE <> '99'
                            AND TO_CHAR(A.TGL_MASUK,'YYYY')='".$periode."'
                            AND A.POLI_ID NOT IN (
                                'APS',
                                'APS L0000000001',
                                'APS R0000000001',
                                'UGD01',
                                'UGD02',
                                'RUJUKANLUAR',
                                'MEDIC0000000000',
                                'POLI0000000000',
                                'POLI0000000043',
                                'POLI0000000042',
                                'POLI0000000041',
                                'POLI0000000044',
                                'POLI0000000052',
                                'POLI0000000031',
                                'HEMOD0000000000',
                                'POLI0000000025',
                                'POLIFISIO',
                                'POLIFISWICARA',
                                'POLIFISOKUP',
                                'CAPD0000000001'
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
                            AND NOT EXISTS (
                                SELECT 1
                                FROM SR01_KEU_EPISODE B
                                WHERE B.LOKASI_ID = A.LOKASI_ID
                                    AND B.PASIEN_ID = A.PASIEN_ID
                                    AND B.AKTIF = '1'
                                    AND B.JENIS_EPISODE = 'O'
                                    AND B.STATUS_EPISODE <> '99'
                                    AND TO_CHAR(B.TGL_MASUK,'YYYY')='".$periode."'
                                    AND B.TGL_MASUK = A.TGL_MASUK
                                    AND B.POLI_ID <> A.POLI_ID
                                    AND B.POLI_ID NOT IN (
                                        'APS',
                                        'APS L0000000001',
                                        'APS R0000000001',
                                        'UGD01',
                                        'UGD02',
                                        'RUJUKANLUAR',
                                        'MEDIC0000000000',
                                        'POLI0000000000',
                                        'POLI0000000043',
                                        'POLI0000000042',
                                        'POLI0000000041',
                                        'POLI0000000044',
                                        'POLI0000000052',
                                        'POLI0000000031',
                                        'HEMOD0000000000',
                                        'POLI0000000025',
                                        'POLIFISIO',
                                        'POLIFISWICARA',
                                        'POLIFISOKUP',
                                        'CAPD0000000001'
                                    )
                                    AND EXISTS (
                                        SELECT 1
                                        FROM SR01_MED_PRWT_TR T
                                        WHERE T.LOKASI_ID = '001'
                                        AND T.AKTIF = '1'
                                        AND T.DONE_STATUS = '01'
                                        AND T.STATUS = '1'
                                        AND T.PASIEN_ID = B.PASIEN_ID
                                        AND T.EPISODE_ID = B.EPISODE_ID
                                    )
                            )
                        ),
                        DATA_FILTER AS (
                            SELECT Q.*
                            FROM DATA_AWAL Q
                            WHERE Q.STATUS_RESEP <> 'RACIKAN'
                            AND (
                                Q.STATUS_RESEP = 'TIDAK ADA RESEP'
                                OR (
                                    Q.STATUS_RESEP = 'NON RACIKAN'
                                    AND Q.TGL_SELESAI IS NOT NULL
                                )
                            )
                            AND NOT EXISTS (
                                SELECT 1
                                FROM WEB_CO_ALKES_DT K
                                WHERE K.EPISODE_ID = Q.EPISODE_SEBELUMNYA
                            )
                            AND NOT EXISTS (
                                SELECT 1
                                FROM WEB_CO_LAB_DT L
                                WHERE L.EPISODE_ID = Q.EPISODE_ID
                                AND L.PASIEN_ID = Q.PASIEN_ID
                                AND L.SHOW_ITEM = '1'
                            )
                            AND NOT EXISTS (
                                SELECT 1
                                FROM WEB_CO_RAD_DT R
                                WHERE R.EPISODE_ID = Q.EPISODE_ID
                                AND R.PASIEN_ID = Q.PASIEN_ID
                                AND R.SHOW_ITEM = '1'
                            )   
                                
                        ),
                        DATA_DURASI AS (
                            SELECT
                                Q.*,
                                CASE
                                    WHEN Q.STATUS_RESEP = 'TIDAK ADA RESEP'
                                        THEN (Q.MULAIPERIKSA - Q.TGLJAMSEP) * 1440
                                    WHEN Q.STATUS_RESEP = 'NON RACIKAN'
                                        THEN (Q.TGL_SELESAI - Q.TGLJAMSEP) * 1440
                                END AS DURASI_MENIT
                            FROM DATA_FILTER Q
                        )
                        SELECT
                            TO_CHAR(TGL_MASUK, 'MM') AS BULAN,
                            COUNT(*) AS TOTAL_PASIEN,
                            SUM(
                                CASE
                                    WHEN DURASI_MENIT >= 0
                                    AND DURASI_MENIT <= 120
                                    THEN 1
                                    ELSE 0
                                END
                            ) AS JUMLAH_PASIEN_KURANG_SAMA_120_MENIT,
                            SUM(
                                CASE
                                    WHEN DURASI_MENIT > 120
                                    THEN 1
                                    ELSE 0
                                END
                            ) AS JUMLAH_PASIEN_LEBIH_120_MENIT,
                            ROUND(
                                SUM(
                                    CASE
                                        WHEN DURASI_MENIT >= 0
                                        AND DURASI_MENIT <= 120
                                        THEN 1
                                        ELSE 0
                                    END
                                ) * 100 / NULLIF(COUNT(*), 0),
                                2
                            ) AS PERSENTASE_PASIEN_KURANG_SAMA_120_MENIT
                        FROM DATA_DURASI
                        GROUP BY TO_CHAR(TGL_MASUK, 'MM')
                        ORDER BY BULAN
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function rawatinapdaily($periode){
            $query =
                    "
                        SELECT X.*,
                            NVL(ROUND((SEBELUM12ALL / NULLIF(JUMLAHALL, 0)) * 100, 2), 0) AS PRESENTASIALL,
                            NVL(ROUND((SEBELUM12TUTD / NULLIF(JMLTUTD, 0)) * 100, 2), 0) AS PRESENTASITUTD,
                            NVL(ROUND(((SEBELUM12ALL - SEBELUM12TUTD) / NULLIF((JUMLAHALL - JMLTUTD), 0)) * 100, 2), 0) AS PRESENTASINONTUTD,
                                                    
                            JUMLAHALL-JMLTUTD JMLNONTUTD,
                            SEBELUM12ALL-SEBELUM12TUTD SEBELUM12NONTUTD,
                            SESUDAH12ALL-SESUDAH12TUTD SESUDAH12NONTUTD
                        FROM(
                            SELECT TO_CHAR(A.TGL_KELUAR, 'DD.MM.YYYY') AS PERIODE,
                                COUNT(*) AS JUMLAHALL,
                                SUM(CASE WHEN TO_CHAR(A.TGL_KELUAR, 'HH24') < '12' THEN 1 ELSE 0 END) AS SEBELUM12ALL,
                                SUM(CASE WHEN TO_CHAR(A.TGL_KELUAR, 'HH24') >= '12' THEN 1 ELSE 0 END) AS SESUDAH12ALL,
                                SUM(CASE WHEN A.EPISODE_ID = (SELECT EPISODE_ID FROM SR01_PASIEN_RANAP B WHERE B.PERLU_VISIT = 'T' AND B.PASIEN_ID = A.PASIEN_ID AND B.EPISODE_ID = A.EPISODE_ID) THEN 1 ELSE 0 END) AS JMLTUTD,
                                SUM(CASE WHEN TO_CHAR(A.TGL_KELUAR, 'HH24') < '12' AND A.EPISODE_ID = (SELECT EPISODE_ID FROM SR01_PASIEN_RANAP B WHERE B.PERLU_VISIT = 'T' AND B.PASIEN_ID = A.PASIEN_ID AND B.EPISODE_ID = A.EPISODE_ID) THEN 1 ELSE 0 END) AS SEBELUM12TUTD,
                                SUM(CASE WHEN TO_CHAR(A.TGL_KELUAR, 'HH24') >= '12' AND A.EPISODE_ID = (SELECT EPISODE_ID FROM SR01_PASIEN_RANAP B WHERE B.PERLU_VISIT = 'T' AND B.PASIEN_ID = A.PASIEN_ID AND B.EPISODE_ID = A.EPISODE_ID) THEN 1 ELSE 0 END) AS SESUDAH12TUTD
                                                        
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND   A.AKTIF = '1'
                            AND   A.JENIS_EPISODE = 'I'
                            AND   A.PULANG_ID NOT IN ('P02', 'P04', 'P10', 'P11', 'P0X')
                            AND   A.KELAS_ID <> 'V'
                            AND   A.RUANGRWT_ID NOT LIKE 'CVC%'
                            AND   A.RUANGRWT_ID NOT LIKE 'IC%'
                            AND   A.RUANGRWT_ID NOT LIKE 'KBY%'
                            AND   A.RUANGRWT_ID NOT LIKE 'PER%'
                            AND   A.TGL_KELUAR IS NOT NULL
                            AND   TO_CHAR(A.TGL_KELUAR,'YYYY')='2026'
                            GROUP BY TO_CHAR(A.TGL_KELUAR, 'DD.MM.YYYY')
                            ORDER BY TO_DATE(PERIODE,'DD.MM.YYYY') DESC
                            FETCH FIRST 30 ROW ONLY
                        )X
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function rawatinapdailydetail(){
            $query =
                    "
                        
                        SELECT X.*,
                            TO_CHAR(X.TGL_KELUAR, 'DD.MM.YYYY HH24:MI:SS') AS TGLKELUAR,
                            GETPIDINT(X.PASIEN_ID) AS MRPASIEN,
                            SR01_GET_SUFFIX(X.PASIEN_ID) AS NAMAPASIEN,
                            D.NAMA AS NAMADOKTER
                        FROM(
                            SELECT 'TUTD'JENIS, A.PASIEN_ID, EPISODE_ID, KELAS_ID, TGL_KELUAR, DOKTER_ID, RUANGRWT_ID
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'I'
                            AND A.STATUS_EPISODE = '00'
                            AND A.KELAS_ID <> 'V'
                            AND   A.RUANGRWT_ID NOT LIKE 'CVC%'
                            AND   A.RUANGRWT_ID NOT LIKE 'IC%'
                            AND   A.RUANGRWT_ID NOT LIKE 'KBY%'
                            AND   A.RUANGRWT_ID NOT LIKE 'PER%'
                            AND EXISTS (
                                SELECT 1
                                FROM SR01_MED_DISCHARGE_TL M
                                WHERE M.PASIEN_ID = A.PASIEN_ID
                                    AND M.EPISODE_ID = A.EPISODE_ID
                                    AND M.TGL_DISCHARGE >= TRUNC(SYSDATE)
                                    AND M.TGL_DISCHARGE < TRUNC(SYSDATE) + 1
                            )
                            AND EXISTS (
                                SELECT 1
                                FROM SR01_PASIEN_RANAP R
                                WHERE R.PASIEN_ID = A.PASIEN_ID
                                    AND R.EPISODE_ID = A.EPISODE_ID
                                    AND R.PERLU_VISIT = 'T'
                            )

                            UNION ALL

                            SELECT 'NONTUTD'JENIS, A.PASIEN_ID, EPISODE_ID, KELAS_ID, TGL_KELUAR, DOKTER_ID, RUANGRWT_ID
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'I'
                            AND A.STATUS_EPISODE = '00'
                            AND A.KELAS_ID <> 'V'
                            AND   A.RUANGRWT_ID NOT LIKE 'CVC%'
                            AND   A.RUANGRWT_ID NOT LIKE 'IC%'
                            AND   A.RUANGRWT_ID NOT LIKE 'KBY%'
                            AND   A.RUANGRWT_ID NOT LIKE 'PER%'
                            AND EXISTS (
                                SELECT 1
                                FROM SR01_MED_DISCHARGE_TL M
                                WHERE M.PASIEN_ID = A.PASIEN_ID
                                    AND M.EPISODE_ID = A.EPISODE_ID
                                    AND M.TGL_DISCHARGE >= TRUNC(SYSDATE)
                                    AND M.TGL_DISCHARGE < TRUNC(SYSDATE) + 1
                            )
                            AND NOT EXISTS (
                                SELECT 1
                                FROM SR01_PASIEN_RANAP R
                                WHERE R.PASIEN_ID = A.PASIEN_ID
                                    AND R.EPISODE_ID = A.EPISODE_ID
                                    AND R.PERLU_VISIT = 'T'
                            )

                            UNION ALL

                            SELECT 'CLOSSING'JENIS, A.PASIEN_ID, EPISODE_ID, KELAS_ID, TGL_KELUAR, DOKTER_ID, RUANGRWT_ID
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND   A.AKTIF = '1'
                            AND   A.JENIS_EPISODE = 'I'
                            AND   A.RUANGRWT_ID NOT LIKE 'CVC%'
                            AND   A.RUANGRWT_ID NOT LIKE 'IC%'
                            AND   A.RUANGRWT_ID NOT LIKE 'KBY%'
                            AND   A.RUANGRWT_ID NOT LIKE 'PER%'
                            AND  TRUNC(A.TGL_KELUAR)=TRUNC(SYSDATE)
                        )X
                        LEFT JOIN SR01_MED_DOKTER_MS D
                        ON D.DOKTER_ID = X.DOKTER_ID
                        ORDER BY TO_DATE(TGLKELUAR,'DD.MM.YYYY HH24:MI:SS')DESC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function pembatalanoperasielektif($periode){
            $query = "
                        SELECT X.*,
                            ROUND(((BATAL/TOTAL)*100),2) PRESENTASI
                        FROM(
                            SELECT TO_CHAR(A.TGL_TINDAKAN, 'MM') AS BULAN,
                                    COUNT (*) AS TOTAL,
                                    SUM(CASE WHEN A.STATUS_ID <> '99' THEN 1 ELSE 0 END) AS REGISTER,
                                    SUM(CASE WHEN A.STATUS_ID = '99' AND TRUNC (A.TGL_TINDAKAN) = TRUNC (A.BATAL_DATE) THEN 1 ELSE 0 END) AS BATAL
                            FROM SR01_MED_OK_LOG A
                            WHERE A.LOKASI_ID = '001'
                            AND   A.AKTIF = '1'
                            AND   A.CITO = '0'
                            AND   A.EPISODE_ID IS NOT NULL
                            AND   A.TGL_TINDAKAN <= TRUNC(SYSDATE)
                            AND   TO_CHAR(A.TGL_TINDAKAN,'YYYY') = '".$periode."'
                            GROUP BY TO_CHAR(A.TGL_TINDAKAN,'MM')
                            ORDER BY BULAN ASC
                        )X
                ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
    }
?>
