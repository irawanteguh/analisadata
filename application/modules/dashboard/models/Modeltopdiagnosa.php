<?php
    class Modeltopdiagnosa extends CI_Model{

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

        function datarjgeriatri($periode){
            $query = "
                        WITH ICD AS (
                            SELECT
                                R.EPISODE_ID,
                                CASE
                                    WHEN R.ICD10_ID = 'Z09.8' THEN (
                                        SELECT R2.ICD10_ID
                                        FROM SR01_RM_RESUME_ICD10 R2
                                        WHERE R2.LOKASI_ID='001'
                                        AND R2.AKTIF='1'
                                        AND R2.JENIS='2'
                                        AND R2.URUT='1'
                                        AND R2.JNS_R='F'
                                        AND R2.ICD10_ID IS NOT NULL
                                        AND R2.EPISODE_ID = R.EPISODE_ID
                                        FETCH FIRST 1 ROW ONLY
                                    )
                                    ELSE R.ICD10_ID
                                END AS ICD10ID,
                                ROW_NUMBER() OVER (
                                    PARTITION BY R.EPISODE_ID
                                    ORDER BY R.CREATED_DATE DESC
                                ) RN
                            FROM SR01_RM_RESUME_ICD10 R
                            WHERE R.LOKASI_ID='001'
                            AND R.AKTIF='1'
                            AND R.JENIS='1'
                            AND R.JNS_R='F'
                            AND R.ICD10_ID IS NOT NULL
                        ),

                        BASE AS (
                            SELECT
                                A.EPISODE_ID,
                                P.SEX_ID,

                                CASE
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 1 AND 3 THEN 1
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 4 AND 6 THEN 2
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 7 AND 9 THEN 3
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 10 AND 12 THEN 4
                                END AS TRIWULAN,

                                CASE
                                    WHEN ICD.ICD10ID LIKE 'D%'
                                        THEN COALESCE(MS.KODE_ICD, ICD.ICD10ID)
                                    ELSE ICD.ICD10ID
                                END AS ICD10PRIMARY,

                                SR01_HITUNG_UMURDLMTHN(
                                    P.TGL_LAHIR,
                                    TRUNC(A.TGL_MASUK)
                                ) AS UMUR

                            FROM SR01_KEU_EPISODE A

                            LEFT JOIN ICD
                                ON ICD.EPISODE_ID = A.EPISODE_ID
                            AND ICD.RN = 1

                            LEFT JOIN SR01_MED_ICD10_MS MS
                                ON MS.KODE = ICD.ICD10ID

                            LEFT JOIN SR01_GEN_PASIEN_MS P
                                ON P.PASIEN_ID = A.PASIEN_ID

                            WHERE A.LOKASI_ID='001'
                            AND A.AKTIF='1'
                            AND A.JENIS_EPISODE='O'
                            AND A.STATUS_EPISODE='55'
                            AND A.POLI_ID NOT IN ('UGD01','UGD02')
                            AND EXTRACT(YEAR FROM A.TGL_MASUK) = ".$periode."
                        ),

                        -- =========================
                        -- FILTER CLEAN Z & R
                        -- =========================
                        BASE_FILTERED AS (
                            SELECT *
                            FROM BASE
                            WHERE ICD10PRIMARY IS NOT NULL
                            AND ICD10PRIMARY NOT LIKE 'Z%'
                            AND ICD10PRIMARY NOT LIKE 'R%'
                        ),

                        -- =========================
                        -- GERIATRI FILTER
                        -- =========================
                        GERIATRI AS (
                            SELECT *
                            FROM BASE_FILTERED
                            WHERE UMUR >= 60
                        ),

                        -- =========================
                        -- REKAP PER TRIWULAN
                        -- =========================
                        REKAP AS (
                            SELECT
                                TRIWULAN,
                                ICD10PRIMARY,
                                I.DESCRIPTION,

                                COUNT(*) AS JUMLAH,
                                SUM(CASE WHEN SEX_ID = 'L' THEN 1 ELSE 0 END) AS LAKI_LAKI,
                                SUM(CASE WHEN SEX_ID = 'P' THEN 1 ELSE 0 END) AS PEREMPUAN

                            FROM GERIATRI G
                            LEFT JOIN SR01_MED_ICD_IDRG I
                                ON I.KODE_ICD = G.ICD10PRIMARY

                            GROUP BY
                                TRIWULAN,
                                ICD10PRIMARY,
                                I.DESCRIPTION
                        ),

                        -- =========================
                        -- RANKING PER TRIWULAN
                        -- =========================
                        RANKING_TW AS (
                            SELECT
                                R.*,
                                ROW_NUMBER() OVER (
                                    PARTITION BY R.TRIWULAN
                                    ORDER BY R.JUMLAH DESC, R.ICD10PRIMARY
                                ) RN
                            FROM REKAP R
                        ),

                        -- =========================
                        -- REKAP TAHUNAN (TRIWULAN 0)
                        -- =========================
                        REKAP_TAHUN AS (
                            SELECT
                                ICD10PRIMARY,
                                DESCRIPTION,
                                SUM(JUMLAH) AS JUMLAH,
                                SUM(LAKI_LAKI) AS LAKI_LAKI,
                                SUM(PEREMPUAN) AS PEREMPUAN,
                                ROW_NUMBER() OVER (
                                    ORDER BY SUM(JUMLAH) DESC, ICD10PRIMARY
                                ) RN
                            FROM REKAP
                            GROUP BY ICD10PRIMARY, DESCRIPTION
                        )

                        -- =========================
                        -- OUTPUT FINAL
                        -- =========================

                        SELECT
                            TRIWULAN,
                            ICD10PRIMARY,
                            DESCRIPTION,
                            JUMLAH,
                            LAKI_LAKI,
                            PEREMPUAN
                        FROM RANKING_TW
                        WHERE RN <= 10

                        UNION ALL

                        SELECT
                            0 AS TRIWULAN,
                            ICD10PRIMARY,
                            DESCRIPTION,
                            JUMLAH,
                            LAKI_LAKI,
                            PEREMPUAN
                        FROM REKAP_TAHUN
                        WHERE RN <= 10

                        ORDER BY TRIWULAN, JUMLAH DESC, ICD10PRIMARY
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datarj($periode){
            $query = "
                        WITH ICD AS (
                            SELECT
                                R.EPISODE_ID,
                                CASE
                                    WHEN R.ICD10_ID = 'Z09.8' THEN (
                                        SELECT R2.ICD10_ID
                                        FROM SR01_RM_RESUME_ICD10 R2
                                        WHERE R2.LOKASI_ID='001'
                                        AND R2.AKTIF='1'
                                        AND R2.JENIS='2'
                                        AND R2.URUT='1'
                                        AND R2.JNS_R='F'
                                        AND R2.ICD10_ID IS NOT NULL
                                        AND R2.EPISODE_ID = R.EPISODE_ID
                                        FETCH FIRST 1 ROW ONLY
                                    )
                                    ELSE R.ICD10_ID
                                END AS ICD10ID,
                                ROW_NUMBER() OVER (
                                    PARTITION BY R.EPISODE_ID
                                    ORDER BY R.CREATED_DATE DESC
                                ) RN
                            FROM SR01_RM_RESUME_ICD10 R
                            WHERE R.LOKASI_ID='001'
                            AND R.AKTIF='1'
                            AND R.JENIS='1'
                            AND R.JNS_R='F'
                            AND R.ICD10_ID IS NOT NULL
                        ),

                        BASE AS (
                            SELECT
                                A.EPISODE_ID,
                                P.SEX_ID,

                                CASE
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 1 AND 3 THEN 1
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 4 AND 6 THEN 2
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 7 AND 9 THEN 3
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 10 AND 12 THEN 4
                                END AS TRIWULAN,

                                CASE
                                    WHEN ICD.ICD10ID LIKE 'D%'
                                        THEN COALESCE(MS.KODE_ICD, ICD.ICD10ID)
                                    ELSE ICD.ICD10ID
                                END AS ICD10PRIMARY

                            FROM SR01_KEU_EPISODE A

                            LEFT JOIN ICD
                                ON ICD.EPISODE_ID = A.EPISODE_ID
                            AND ICD.RN = 1

                            LEFT JOIN SR01_MED_ICD10_MS MS
                                ON MS.KODE = ICD.ICD10ID

                            LEFT JOIN SR01_GEN_PASIEN_MS P
                                ON P.PASIEN_ID = A.PASIEN_ID

                            WHERE A.LOKASI_ID='001'
                            AND A.AKTIF='1'
                            AND A.JENIS_EPISODE='O'
                            AND A.STATUS_EPISODE='55'
                            AND A.POLI_ID NOT IN ('UGD01','UGD02')
                            AND EXTRACT(YEAR FROM A.TGL_MASUK) = ".$periode."
                            AND ICD.ICD10ID IS NOT NULL
                        ),

                        BASE_FILTERED AS (
                            SELECT *
                            FROM BASE
                            WHERE ICD10PRIMARY IS NOT NULL
                            AND ICD10PRIMARY NOT LIKE 'Z%'
                            AND ICD10PRIMARY NOT LIKE 'R%'
                        ),

                        REKAP AS (
                            SELECT
                                TRIWULAN,
                                ICD10PRIMARY,
                                I.DESCRIPTION,

                                COUNT(*) AS JUMLAH,
                                SUM(CASE WHEN SEX_ID = 'L' THEN 1 ELSE 0 END) AS LAKI_LAKI,
                                SUM(CASE WHEN SEX_ID = 'P' THEN 1 ELSE 0 END) AS PEREMPUAN

                            FROM BASE_FILTERED B
                            LEFT JOIN SR01_MED_ICD_IDRG I
                                ON I.KODE_ICD = B.ICD10PRIMARY

                            GROUP BY
                                TRIWULAN,
                                ICD10PRIMARY,
                                I.DESCRIPTION
                        ),

                        RANKING_TW AS (
                            SELECT
                                R.*,
                                ROW_NUMBER() OVER (
                                    PARTITION BY R.TRIWULAN
                                    ORDER BY R.JUMLAH DESC, R.ICD10PRIMARY
                                ) AS RN
                            FROM REKAP R
                        ),

                        REKAP_TAHUN AS (
                            SELECT
                                ICD10PRIMARY,
                                DESCRIPTION,
                                SUM(JUMLAH) AS JUMLAH,
                                SUM(LAKI_LAKI) AS LAKI_LAKI,
                                SUM(PEREMPUAN) AS PEREMPUAN,
                                ROW_NUMBER() OVER (
                                    ORDER BY SUM(JUMLAH) DESC, ICD10PRIMARY
                                ) AS RN
                            FROM REKAP
                            GROUP BY ICD10PRIMARY, DESCRIPTION
                        )

                        SELECT
                            TRIWULAN,
                            ICD10PRIMARY,
                            DESCRIPTION,
                            JUMLAH,
                            LAKI_LAKI,
                            PEREMPUAN
                        FROM RANKING_TW
                        WHERE RN <= 10

                        UNION ALL

                        SELECT
                            0 AS TRIWULAN,
                            ICD10PRIMARY,
                            DESCRIPTION,
                            JUMLAH,
                            LAKI_LAKI,
                            PEREMPUAN
                        FROM REKAP_TAHUN
                        WHERE RN <= 10

                        ORDER BY TRIWULAN, JUMLAH DESC, ICD10PRIMARY
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datarjsmf($periode){
            $query = "
                        WITH ICD AS (
                        SELECT
                            R.EPISODE_ID,
                            CASE
                                WHEN R.ICD10_ID = 'Z09.8' THEN (
                                    SELECT R2.ICD10_ID
                                    FROM SR01_RM_RESUME_ICD10 R2
                                    WHERE R2.LOKASI_ID = '001'
                                    AND R2.AKTIF = '1'
                                    AND R2.JENIS = '2'
                                    AND R2.URUT = '1'
                                    AND R2.JNS_R = 'F'
                                    AND R2.ICD10_ID IS NOT NULL
                                    AND R2.EPISODE_ID = R.EPISODE_ID
                                    FETCH FIRST 1 ROW ONLY
                                )
                                ELSE R.ICD10_ID
                            END AS ICD10ID,

                            ROW_NUMBER() OVER (
                                PARTITION BY R.EPISODE_ID
                                ORDER BY R.CREATED_DATE DESC
                            ) AS RN

                        FROM SR01_RM_RESUME_ICD10 R
                        WHERE R.LOKASI_ID = '001'
                        AND R.AKTIF = '1'
                        AND R.JENIS = '1'
                        AND R.JNS_R = 'F'
                        AND R.ICD10_ID IS NOT NULL
                    ),

                    BASE AS (
                        SELECT
                            A.EPISODE_ID,
                            P.SEX_ID,

                            K.KOLEGIUM,

                            CASE
                                WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 1 AND 3 THEN 1
                                WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 4 AND 6 THEN 2
                                WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 7 AND 9 THEN 3
                                ELSE 4
                            END AS TRIWULAN,

                            CASE
                                WHEN ICD.ICD10ID LIKE 'D%'
                                    THEN COALESCE(MS.KODE_ICD, ICD.ICD10ID)
                                ELSE ICD.ICD10ID
                            END AS ICD10PRIMARY

                        FROM SR01_KEU_EPISODE A

                        LEFT JOIN ICD
                            ON ICD.EPISODE_ID = A.EPISODE_ID
                            AND ICD.RN = 1

                        LEFT JOIN SR01_MED_ICD10_MS MS
                            ON MS.KODE = ICD.ICD10ID

                        LEFT JOIN SR01_GEN_PASIEN_MS P
                            ON P.PASIEN_ID = A.PASIEN_ID

                        LEFT JOIN SR01_RMN_DOKTER_MS D
                            ON D.DOKTER_ID = A.DOKTER_ID

                        LEFT JOIN SR01_RMN_KOLEGIUM_MS K
                            ON K.KOLEGIUM_ID = D.KOLEGIUM_ID

                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE = '55'
                        AND A.POLI_ID NOT IN ('UGD01', 'UGD02')
                        AND EXTRACT(YEAR FROM A.TGL_MASUK) = ".$periode."
                        AND ICD.ICD10ID IS NOT NULL
                    ),

                    BASE_FILTERED AS (
                        SELECT *
                        FROM BASE
                        WHERE ICD10PRIMARY IS NOT NULL
                        AND ICD10PRIMARY NOT LIKE 'Z%'
                        AND ICD10PRIMARY NOT LIKE 'R%'
                    ),

                    REKAP AS (
                        SELECT
                            B.KOLEGIUM,
                            B.TRIWULAN,
                            B.ICD10PRIMARY,
                            I.DESCRIPTION,

                            COUNT(*) AS JUMLAH,

                            SUM(CASE WHEN B.SEX_ID = 'L' THEN 1 ELSE 0 END) AS LAKI_LAKI,
                            SUM(CASE WHEN B.SEX_ID = 'P' THEN 1 ELSE 0 END) AS PEREMPUAN

                        FROM BASE_FILTERED B

                        LEFT JOIN SR01_MED_ICD_IDRG I
                            ON I.KODE_ICD = B.ICD10PRIMARY

                        GROUP BY
                            B.KOLEGIUM,
                            B.TRIWULAN,
                            B.ICD10PRIMARY,
                            I.DESCRIPTION
                    ),

                    RANKING_TW AS (
                        SELECT
                            R.*,
                            ROW_NUMBER() OVER (
                                PARTITION BY R.KOLEGIUM, R.TRIWULAN
                                ORDER BY R.JUMLAH DESC, R.ICD10PRIMARY
                            ) AS RN
                        FROM REKAP R
                    ),

                    REKAP_TAHUN AS (
                        SELECT
                            KOLEGIUM,
                            ICD10PRIMARY,
                            DESCRIPTION,

                            SUM(JUMLAH) AS JUMLAH,
                            SUM(LAKI_LAKI) AS LAKI_LAKI,
                            SUM(PEREMPUAN) AS PEREMPUAN,

                            ROW_NUMBER() OVER (
                                PARTITION BY KOLEGIUM
                                ORDER BY SUM(JUMLAH) DESC, ICD10PRIMARY
                            ) AS RN

                        FROM REKAP

                        GROUP BY
                            KOLEGIUM,
                            ICD10PRIMARY,
                            DESCRIPTION
                    )

                    SELECT
                        KOLEGIUM,
                        TRIWULAN,
                        ICD10PRIMARY,
                        DESCRIPTION,
                        JUMLAH,
                        LAKI_LAKI,
                        PEREMPUAN
                    FROM RANKING_TW
                    WHERE RN <= 10

                    UNION ALL

                    SELECT
                        KOLEGIUM,
                        0 AS TRIWULAN,
                        ICD10PRIMARY,
                        DESCRIPTION,
                        JUMLAH,
                        LAKI_LAKI,
                        PEREMPUAN
                    FROM REKAP_TAHUN
                    WHERE RN <= 10

                    ORDER BY
                        KOLEGIUM,
                        TRIWULAN,
                        JUMLAH DESC,
                        ICD10PRIMARY
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function dataigd($periode){
            $query = "
                        WITH ICD AS (
                            SELECT
                                R.EPISODE_ID,
                                CASE
                                    WHEN R.ICD10_ID = 'Z09.8' THEN (
                                        SELECT R2.ICD10_ID
                                        FROM SR01_RM_RESUME_ICD10 R2
                                        WHERE R2.LOKASI_ID='001'
                                        AND R2.AKTIF='1'
                                        AND R2.JENIS='2'
                                        AND R2.URUT='1'
                                        AND R2.JNS_R='F'
                                        AND R2.ICD10_ID IS NOT NULL
                                        AND R2.EPISODE_ID = R.EPISODE_ID
                                        FETCH FIRST 1 ROW ONLY
                                    )
                                    ELSE R.ICD10_ID
                                END AS ICD10ID,
                                ROW_NUMBER() OVER (
                                    PARTITION BY R.EPISODE_ID
                                    ORDER BY R.CREATED_DATE DESC
                                ) RN
                            FROM SR01_RM_RESUME_ICD10 R
                            WHERE R.LOKASI_ID='001'
                            AND R.AKTIF='1'
                            AND R.JENIS='1'
                            AND R.JNS_R='F'
                            AND R.ICD10_ID IS NOT NULL
                        ),

                        BASE AS (
                            SELECT
                                A.EPISODE_ID,
                                P.SEX_ID,

                                CASE
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 1 AND 3 THEN 1
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 4 AND 6 THEN 2
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 7 AND 9 THEN 3
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 10 AND 12 THEN 4
                                END AS TRIWULAN,

                                CASE
                                    WHEN ICD.ICD10ID LIKE 'D%'
                                        THEN COALESCE(MS.KODE_ICD, ICD.ICD10ID)
                                    ELSE ICD.ICD10ID
                                END AS ICD10PRIMARY

                            FROM SR01_KEU_EPISODE A
                            LEFT JOIN ICD
                                ON ICD.EPISODE_ID = A.EPISODE_ID
                            AND ICD.RN = 1

                            LEFT JOIN SR01_MED_ICD10_MS MS
                                ON MS.KODE = ICD.ICD10ID

                            LEFT JOIN SR01_GEN_PASIEN_MS P
                                ON P.PASIEN_ID = A.PASIEN_ID

                            WHERE A.LOKASI_ID='001'
                            AND A.AKTIF='1'
                            AND A.JENIS_EPISODE='O'
                            AND A.STATUS_EPISODE='55'
                            AND A.POLI_ID IN ('UGD01','UGD02')
                            AND EXTRACT(YEAR FROM A.TGL_MASUK) = ".$periode."
                        ),

                        -- 🔥 FILTER Z & R DI SINI (PALING AMAN)
                        BASE_FILTERED AS (
                            SELECT *
                            FROM BASE
                            WHERE ICD10PRIMARY IS NOT NULL
                            AND ICD10PRIMARY NOT LIKE 'Z%'
                            AND ICD10PRIMARY NOT LIKE 'R%'
                        ),

                        REKAP AS (
                            SELECT
                                TRIWULAN,
                                ICD10PRIMARY,
                                I.DESCRIPTION,

                                COUNT(*) AS JUMLAH,
                                SUM(CASE WHEN SEX_ID = 'L' THEN 1 ELSE 0 END) AS LAKI_LAKI,
                                SUM(CASE WHEN SEX_ID = 'P' THEN 1 ELSE 0 END) AS PEREMPUAN

                            FROM BASE_FILTERED B
                            LEFT JOIN SR01_MED_ICD_IDRG I
                                ON I.KODE_ICD = B.ICD10PRIMARY
                            GROUP BY
                                TRIWULAN,
                                ICD10PRIMARY,
                                I.DESCRIPTION
                        ),

                        RANKING AS (
                            SELECT
                                R.*,
                                ROW_NUMBER() OVER (
                                    PARTITION BY R.TRIWULAN
                                    ORDER BY R.JUMLAH DESC, R.ICD10PRIMARY
                                ) AS RN
                            FROM REKAP R
                        )

                        SELECT
                            TRIWULAN,
                            ICD10PRIMARY,
                            DESCRIPTION,
                            JUMLAH,
                            LAKI_LAKI,
                            PEREMPUAN
                        FROM RANKING
                        WHERE RN <= 10

                        UNION ALL

                        SELECT
                            0 AS TRIWULAN,
                            ICD10PRIMARY,
                            DESCRIPTION,
                            JUMLAH,
                            LAKI_LAKI,
                            PEREMPUAN
                        FROM (
                            SELECT
                                ICD10PRIMARY,
                                DESCRIPTION,
                                SUM(JUMLAH) AS JUMLAH,
                                SUM(LAKI_LAKI) AS LAKI_LAKI,
                                SUM(PEREMPUAN) AS PEREMPUAN,
                                ROW_NUMBER() OVER (
                                    ORDER BY SUM(JUMLAH) DESC, ICD10PRIMARY
                                ) AS RN
                            FROM REKAP
                            GROUP BY ICD10PRIMARY, DESCRIPTION
                        ) X
                        WHERE RN <= 10

                        ORDER BY TRIWULAN, JUMLAH DESC
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datari($periode){
            $query = "
                        WITH ICD AS (
                            SELECT
                                R.EPISODE_ID,
                                CASE
                                    WHEN R.ICD10_ID = 'Z09.8' THEN (
                                        SELECT R2.ICD10_ID
                                        FROM SR01_RM_RESUME_ICD10 R2
                                        WHERE R2.LOKASI_ID = '001'
                                        AND R2.AKTIF = '1'
                                        AND R2.JENIS = '2'
                                        AND R2.URUT = '1'
                                        AND R2.JNS_R = 'F'
                                        AND R2.ICD10_ID IS NOT NULL
                                        AND R2.EPISODE_ID = R.EPISODE_ID
                                        FETCH FIRST 1 ROW ONLY
                                    )
                                    ELSE R.ICD10_ID
                                END AS ICD10ID,
                                ROW_NUMBER() OVER (
                                    PARTITION BY R.EPISODE_ID
                                    ORDER BY R.CREATED_DATE DESC
                                ) RN
                            FROM SR01_RM_RESUME_ICD10 R
                            WHERE R.LOKASI_ID = '001'
                            AND R.AKTIF = '1'
                            AND R.JENIS = '1'
                            AND R.JNS_R = 'F'
                            AND R.ICD10_ID IS NOT NULL
                        ),

                        DATA AS (
                            SELECT
                                A.PASIEN_ID,
                                P.SEX_ID,

                                CASE
                                    WHEN ICD.ICD10ID LIKE 'D%'
                                        THEN COALESCE(MS.KODE_ICD, ICD.ICD10ID)
                                    ELSE ICD.ICD10ID
                                END AS ICD10PRIMARY,

                                CASE
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 1 AND 3 THEN 1
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 4 AND 6 THEN 2
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 7 AND 9 THEN 3
                                    WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 10 AND 12 THEN 4
                                END AS TRIWULAN

                            FROM SR01_KEU_EPISODE A

                            LEFT JOIN ICD
                                ON ICD.EPISODE_ID = A.EPISODE_ID
                            AND ICD.RN = 1

                            LEFT JOIN SR01_MED_ICD10_MS MS
                                ON MS.KODE = ICD.ICD10ID

                            LEFT JOIN SR01_GEN_PASIEN_MS P
                                ON P.PASIEN_ID = A.PASIEN_ID

                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'I'
                            AND A.STATUS_EPISODE = '55'
                            AND EXTRACT(YEAR FROM A.TGL_MASUK) = ".$periode."
                            AND ICD.ICD10ID IS NOT NULL
                        ),

                        -- 🔥 FILTER Z & R
                        DATA_FILTERED AS (
                            SELECT *
                            FROM DATA
                            WHERE ICD10PRIMARY IS NOT NULL
                            AND ICD10PRIMARY NOT LIKE 'Z%'
                            AND ICD10PRIMARY NOT LIKE 'R%'
                        ),

                        -- =========================
                        -- REKAP TRIWULAN
                        -- =========================
                        REKAP AS (
                            SELECT
                                TRIWULAN,
                                ICD10PRIMARY,
                                I.DESCRIPTION,

                                COUNT(*) AS JUMLAH,
                                SUM(CASE WHEN SEX_ID = 'L' THEN 1 ELSE 0 END) AS LAKI_LAKI,
                                SUM(CASE WHEN SEX_ID = 'P' THEN 1 ELSE 0 END) AS PEREMPUAN

                            FROM DATA_FILTERED D
                            LEFT JOIN SR01_MED_ICD_IDRG I
                                ON I.KODE_ICD = D.ICD10PRIMARY

                            GROUP BY
                                TRIWULAN,
                                ICD10PRIMARY,
                                I.DESCRIPTION
                        ),

                        -- =========================
                        -- RANK TRIWULAN
                        -- =========================
                        RANKING_TW AS (
                            SELECT
                                R.*,
                                ROW_NUMBER() OVER (
                                    PARTITION BY R.TRIWULAN
                                    ORDER BY R.JUMLAH DESC, R.ICD10PRIMARY
                                ) RN
                            FROM REKAP R
                        ),

                        -- =========================
                        -- REKAP TAHUNAN
                        -- =========================
                        REKAP_TAHUN AS (
                            SELECT
                                ICD10PRIMARY,
                                DESCRIPTION,
                                SUM(JUMLAH) AS JUMLAH,
                                SUM(LAKI_LAKI) AS LAKI_LAKI,
                                SUM(PEREMPUAN) AS PEREMPUAN,
                                ROW_NUMBER() OVER (
                                    ORDER BY SUM(JUMLAH) DESC, ICD10PRIMARY
                                ) RN
                            FROM REKAP
                            GROUP BY ICD10PRIMARY, DESCRIPTION
                        )

                        -- =========================
                        -- OUTPUT FINAL
                        -- =========================

                        SELECT
                            TRIWULAN,
                            ICD10PRIMARY,
                            DESCRIPTION,
                            JUMLAH,
                            LAKI_LAKI,
                            PEREMPUAN
                        FROM RANKING_TW
                        WHERE RN <= 10

                        UNION ALL

                        SELECT
                            0 AS TRIWULAN,
                            ICD10PRIMARY,
                            DESCRIPTION,
                            JUMLAH,
                            LAKI_LAKI,
                            PEREMPUAN
                        FROM REKAP_TAHUN
                        WHERE RN <= 10

                        ORDER BY TRIWULAN, JUMLAH DESC, ICD10PRIMARY
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datarismf($periode){
            $query = "
                        WITH ICD AS (
                        SELECT
                            R.EPISODE_ID,

                            CASE
                                WHEN R.ICD10_ID = 'Z09.8' THEN (
                                    SELECT R2.ICD10_ID
                                    FROM SR01_RM_RESUME_ICD10 R2
                                    WHERE R2.LOKASI_ID = '001'
                                    AND R2.AKTIF = '1'
                                    AND R2.JENIS = '2'
                                    AND R2.URUT = '1'
                                    AND R2.JNS_R = 'F'
                                    AND R2.ICD10_ID IS NOT NULL
                                    AND R2.EPISODE_ID = R.EPISODE_ID
                                    FETCH FIRST 1 ROW ONLY
                                )
                                ELSE R.ICD10_ID
                            END AS ICD10ID,

                            ROW_NUMBER() OVER (
                                PARTITION BY R.EPISODE_ID
                                ORDER BY R.CREATED_DATE DESC
                            ) RN

                        FROM SR01_RM_RESUME_ICD10 R

                        WHERE R.LOKASI_ID = '001'
                        AND R.AKTIF = '1'
                        AND R.JENIS = '1'
                        AND R.JNS_R = 'F'
                        AND R.ICD10_ID IS NOT NULL
                    ),

                    DATA AS (

                        SELECT
                            A.PASIEN_ID,
                            P.SEX_ID,

                            D.KOLEGIUM_ID,
                            K.KOLEGIUM,

                            CASE
                                WHEN ICD.ICD10ID LIKE 'D%'
                                    THEN COALESCE(MS.KODE_ICD, ICD.ICD10ID)
                                ELSE ICD.ICD10ID
                            END AS ICD10PRIMARY,

                            CASE
                                WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 1 AND 3 THEN 1
                                WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 4 AND 6 THEN 2
                                WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 7 AND 9 THEN 3
                                WHEN EXTRACT(MONTH FROM A.TGL_MASUK) BETWEEN 10 AND 12 THEN 4
                            END AS TRIWULAN

                        FROM SR01_KEU_EPISODE A

                        LEFT JOIN ICD
                            ON ICD.EPISODE_ID = A.EPISODE_ID
                        AND ICD.RN = 1

                        LEFT JOIN SR01_MED_ICD10_MS MS
                            ON MS.KODE = ICD.ICD10ID

                        LEFT JOIN SR01_GEN_PASIEN_MS P
                            ON P.PASIEN_ID = A.PASIEN_ID

                        LEFT JOIN SR01_RMN_DOKTER_MS D
                            ON D.DOKTER_ID = A.DOKTER_ID

                        LEFT JOIN SR01_RMN_KOLEGIUM_MS K
                            ON K.KOLEGIUM_ID = D.KOLEGIUM_ID

                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'I'
                        AND A.STATUS_EPISODE = '55'
                        AND EXTRACT(YEAR FROM A.TGL_MASUK) = ".$periode."
                        AND ICD.ICD10ID IS NOT NULL
                    ),

                    DATA_FILTERED AS (

                        SELECT *
                        FROM DATA

                        WHERE ICD10PRIMARY IS NOT NULL
                        AND ICD10PRIMARY NOT LIKE 'Z%'
                        AND ICD10PRIMARY NOT LIKE 'R%'
                    ),

                    REKAP AS (

                        SELECT
                            KOLEGIUM,
                            TRIWULAN,
                            ICD10PRIMARY,
                            I.DESCRIPTION,

                            COUNT(*) AS JUMLAH,

                            SUM(
                                CASE
                                    WHEN SEX_ID = 'L' THEN 1
                                    ELSE 0
                                END
                            ) AS LAKI_LAKI,

                            SUM(
                                CASE
                                    WHEN SEX_ID = 'P' THEN 1
                                    ELSE 0
                                END
                            ) AS PEREMPUAN

                        FROM DATA_FILTERED D

                        LEFT JOIN SR01_MED_ICD_IDRG I
                            ON I.KODE_ICD = D.ICD10PRIMARY

                        GROUP BY
                            KOLEGIUM,
                            TRIWULAN,
                            ICD10PRIMARY,
                            I.DESCRIPTION
                    ),

                    RANKING_TW AS (

                        SELECT
                            R.*,

                            ROW_NUMBER() OVER (
                                PARTITION BY
                                    R.KOLEGIUM,
                                    R.TRIWULAN
                                ORDER BY
                                    R.JUMLAH DESC,
                                    R.ICD10PRIMARY
                            ) RN

                        FROM REKAP R
                    ),

                    REKAP_TAHUN AS (

                        SELECT
                            KOLEGIUM,
                            ICD10PRIMARY,
                            DESCRIPTION,

                            SUM(JUMLAH) AS JUMLAH,
                            SUM(LAKI_LAKI) AS LAKI_LAKI,
                            SUM(PEREMPUAN) AS PEREMPUAN,

                            ROW_NUMBER() OVER (
                                PARTITION BY KOLEGIUM
                                ORDER BY
                                    SUM(JUMLAH) DESC,
                                    ICD10PRIMARY
                            ) RN

                        FROM REKAP

                        GROUP BY
                            KOLEGIUM,
                            ICD10PRIMARY,
                            DESCRIPTION
                    )

                    SELECT
                        KOLEGIUM,
                        TRIWULAN,
                        ICD10PRIMARY,
                        DESCRIPTION,
                        JUMLAH,
                        LAKI_LAKI,
                        PEREMPUAN

                    FROM RANKING_TW

                    WHERE RN <= 10

                    UNION ALL

                    SELECT
                        KOLEGIUM,
                        0 AS TRIWULAN,
                        ICD10PRIMARY,
                        DESCRIPTION,
                        JUMLAH,
                        LAKI_LAKI,
                        PEREMPUAN

                    FROM REKAP_TAHUN

                    WHERE RN <= 10

                    ORDER BY
                        KOLEGIUM,
                        TRIWULAN,
                        JUMLAH DESC,
                        ICD10PRIMARY
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>