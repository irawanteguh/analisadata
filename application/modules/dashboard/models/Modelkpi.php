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
                        
                        SELECT
                            TO_CHAR(X.TGL_MASUK, 'MM') AS BULAN,
                            COUNT(*) AS TOTAL_PASIEN,

                            SUM(
                                CASE
                                    WHEN X.MULAIPERIKSA >= X.TGLMULAIANAM
                                    AND (X.MULAIPERIKSA - X.TGLMULAIANAM) * 1440 <= 60
                                    THEN 1
                                    ELSE 0
                                END
                            ) AS JUMLAH_PASIEN_KURANG_SAMA_60_MENIT,

                            SUM(
                                CASE
                                    WHEN (X.MULAIPERIKSA - X.TGLMULAIANAM) * 1440 > 60
                                    THEN 1
                                    ELSE 0
                                END
                            ) AS JUMLAH_PASIEN_LEBIH_60_MENIT,

                            ROUND(
                                100 * SUM(
                                    CASE
                                        WHEN X.MULAIPERIKSA >= X.TGLMULAIANAM
                                        AND (X.MULAIPERIKSA - X.TGLMULAIANAM) * 1440 <= 60
                                        THEN 1
                                        ELSE 0
                                    END
                                ) / NULLIF(COUNT(*), 0),
                                2
                            ) AS PERSENTASE_PASIEN_KURANG_SAMA_60_MENIT

                        FROM (
                            SELECT
                                A.PASIEN_ID,
                                A.EPISODE_ID,
                                A.TGL_MASUK,

                                (
                                    SELECT DECODE(
                                        H.TGL_MULAI_ANAM,
                                        NULL,
                                        (
                                            SELECT MAX(M.CREATED_DATE)
                                            FROM SR01_MED_ANAMAWAL M
                                            WHERE M.PASIEN_ID = A.PASIEN_ID
                                            AND M.EPISODE_ID = A.EPISODE_ID
                                        ),
                                        H.TGL_MULAI_ANAM
                                    )
                                    FROM WEB_CO_REGISTRASI_ONLINE_HD H
                                    WHERE H.PASIEN_ID = A.PASIEN_ID
                                    AND H.EPISODE_ID = A.EPISODE_ID
                                ) AS TGLMULAIANAM,

                                (
                                    SELECT MIN(P.CREATED_DATE)
                                    FROM WEB_CO_MULAI_PERIKSA P
                                    WHERE P.SHOW_ITEM = '1'
                                    AND P.PASIEN_ID = A.PASIEN_ID
                                    AND P.EPISODE_ID = A.EPISODE_ID
                                ) AS MULAIPERIKSA

                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'O'
                            AND A.STATUS_EPISODE <> '99'
                            AND   TO_CHAR(A.TGL_MASUK,'YYYY')='".$periode."'
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

                            AND (
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
                                    WHERE T.LOKASI_ID = '001'
                                        AND T.AKTIF = '1'
                                        AND T.DONE_STATUS = '01'
                                        AND T.STATUS = '1'
                                        AND T.PASIEN_ID = A.PASIEN_ID
                                        AND T.EPISODE_ID = A.EPISODE_ID
                                )
                            )
                        ) X

                        WHERE X.TGLMULAIANAM IS NOT NULL
                        AND X.MULAIPERIKSA IS NOT NULL

                        GROUP BY TO_CHAR(X.TGL_MASUK, 'MM')
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

        // function pembatalanoperasielektif($periode){
        //     $query = "
        //                 SELECT TO_CHAR(TRUNC(A.TGL_TINDAKAN), 'DD.MM.YYYY') AS PERIODE,
        //                         COUNT (*) AS TOTAL,
        //                         SUM(CASE WHEN A.STATUS_ID <> '99' THEN 1 ELSE 0 END) AS REGISTER,
        //                         SUM(CASE WHEN A.STATUS_ID = '99' AND TRUNC (A.TGL_TINDAKAN) = TRUNC (A.BATAL_DATE) THEN 1 ELSE 0 END) AS BATAL
        //                 FROM SR01_MED_OK_LOG A
        //                 WHERE A.LOKASI_ID = '001'
        //                 AND   A.AKTIF = '1'
        //                 AND   A.CITO = '0'
        //                 AND   TO_CHAR(A.TGL_TINDAKAN,'YYYY') = '".$periode."'
        //                 GROUP BY TRUNC(A.TGL_TINDAKAN)
        //                 ORDER BY TRUNC(A.TGL_TINDAKAN) DESC
        //         ";

        //     $recordset = $this->db->query($query);
        //     $recordset = $recordset->result();
        //     return $recordset;
        // }
    }
?>
