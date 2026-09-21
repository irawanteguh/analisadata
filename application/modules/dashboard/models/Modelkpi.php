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

        function datawaktutunggurawatjalan($periode){
            $query = "
                        SELECT TO_CHAR(A.TGL_MASUK, 'MM') AS BULAN,
                            COUNT(A.EPISODE_ID) AS JML,

                            /* ========================================
                                MULAI PEMERIKSAAN DOKTER
                                ======================================== */
                            COUNT(
                                CASE
                                    WHEN MP.MULAIPERIKSA IS NOT NULL
                                    THEN 1
                                END
                            ) AS JML_SUDAH_PERIKSA,

                            COUNT(
                                CASE
                                    WHEN MP.MULAIPERIKSA IS NOT NULL
                                        AND RH.TGLCHECKIN IS NOT NULL
                                        AND MP.MULAIPERIKSA >= RH.TGLCHECKIN
                                        AND (MP.MULAIPERIKSA - RH.TGLCHECKIN) * 24 * 60 <= 60
                                    THEN 1
                                END
                            ) AS JML_DIBAWAH_60,

                            ROUND(
                                100 *
                                COUNT(
                                    CASE
                                        WHEN MP.MULAIPERIKSA IS NOT NULL
                                            AND RH.TGLCHECKIN IS NOT NULL
                                            AND MP.MULAIPERIKSA >= RH.TGLCHECKIN
                                            AND (MP.MULAIPERIKSA - RH.TGLCHECKIN) * 24 * 60 <= 60
                                        THEN 1
                                    END
                                )
                                /
                                NULLIF(
                                    COUNT(
                                        CASE
                                            WHEN MP.MULAIPERIKSA IS NOT NULL
                                                AND RH.TGLCHECKIN IS NOT NULL
                                                AND MP.MULAIPERIKSA >= RH.TGLCHECKIN
                                            THEN 1
                                        END
                                    ),
                                    0
                                ),
                                2
                            ) AS PERSEN_DIBAWAH_60,

                            ROUND(
                                AVG(
                                    CASE
                                        WHEN MP.MULAIPERIKSA IS NOT NULL
                                            AND RH.TGLCHECKIN IS NOT NULL
                                            AND MP.MULAIPERIKSA >= RH.TGLCHECKIN
                                        THEN (MP.MULAIPERIKSA - RH.TGLCHECKIN) * 24 * 60
                                    END
                                ),
                                2
                            ) AS AVG_WAKTU_TUNGGU,

                            /* ========================================
                                MULAI ANAMNESA
                                ======================================== */
                            COUNT(
                                CASE
                                    WHEN RH.TGL_MULAI_ANAM IS NOT NULL
                                        AND RH.TGLCHECKIN IS NOT NULL
                                        AND RH.TGL_MULAI_ANAM >= RH.TGLCHECKIN
                                        AND (RH.TGL_MULAI_ANAM - RH.TGLCHECKIN) * 24 * 60 <= 20
                                    THEN 1
                                END
                            ) AS JML_DIBAWAH_20_ANAM,

                            ROUND(
                                100 *
                                COUNT(
                                    CASE
                                        WHEN RH.TGL_MULAI_ANAM IS NOT NULL
                                            AND RH.TGLCHECKIN IS NOT NULL
                                            AND RH.TGL_MULAI_ANAM >= RH.TGLCHECKIN
                                            AND (RH.TGL_MULAI_ANAM - RH.TGLCHECKIN) * 24 * 60 <= 20
                                        THEN 1
                                    END
                                )
                                /
                                NULLIF(
                                    COUNT(
                                        CASE
                                            WHEN RH.TGL_MULAI_ANAM IS NOT NULL
                                                AND RH.TGLCHECKIN IS NOT NULL
                                                AND RH.TGL_MULAI_ANAM >= RH.TGLCHECKIN
                                            THEN 1
                                        END
                                    ),
                                    0
                                ),
                                2
                            ) AS PERSEN_DIBAWAH_20_ANAM,


                            ROUND(
                                AVG(
                                    CASE
                                        WHEN RH.TGL_MULAI_ANAM IS NOT NULL
                                            AND RH.TGLCHECKIN IS NOT NULL
                                            AND RH.TGL_MULAI_ANAM >= RH.TGLCHECKIN
                                        THEN
                                            (RH.TGL_MULAI_ANAM - RH.TGLCHECKIN) * 24 * 60
                                    END
                                ),
                                2
                            ) AS AVG_WAKTU_ANAM,

                            /* ========================================
                                SELESAI ANAMNESA
                                ======================================== */
                            COUNT(
                                CASE
                                    WHEN RH.TGL_MULAI_ANAM IS NOT NULL
                                        AND SA.TGL_SELESAI_ANAM IS NOT NULL
                                        AND SA.TGL_SELESAI_ANAM >= RH.TGL_MULAI_ANAM
                                        AND (SA.TGL_SELESAI_ANAM - RH.TGL_MULAI_ANAM) * 24 * 60 <= 10
                                    THEN 1
                                END
                            ) AS JML_ANAM_SELESAI_10,

                            ROUND(
                                100 *
                                COUNT(
                                    CASE
                                        WHEN RH.TGL_MULAI_ANAM IS NOT NULL
                                            AND SA.TGL_SELESAI_ANAM IS NOT NULL
                                            AND SA.TGL_SELESAI_ANAM >= RH.TGL_MULAI_ANAM
                                            AND (SA.TGL_SELESAI_ANAM - RH.TGL_MULAI_ANAM) * 24 * 60 <= 10
                                        THEN 1
                                    END
                                )
                                /
                                NULLIF(
                                    COUNT(
                                        CASE
                                            WHEN RH.TGL_MULAI_ANAM IS NOT NULL
                                                AND SA.TGL_SELESAI_ANAM IS NOT NULL
                                                AND SA.TGL_SELESAI_ANAM >= RH.TGL_MULAI_ANAM
                                            THEN 1
                                        END
                                    ),
                                    0
                                ),
                                2
                            ) AS PERSEN_ANAM_SELESAI_10,

                            ROUND(
                                AVG(
                                    CASE
                                        WHEN RH.TGL_MULAI_ANAM IS NOT NULL
                                            AND SA.TGL_SELESAI_ANAM IS NOT NULL
                                            AND SA.TGL_SELESAI_ANAM >= RH.TGL_MULAI_ANAM
                                        THEN
                                            (SA.TGL_SELESAI_ANAM - RH.TGL_MULAI_ANAM) * 24 * 60
                                    END
                                ),
                                2
                            ) AS AVG_WAKTU_SELESAI_ANAM,
                            
                            /* ========================================
                                SELESAI ANAMNESA → MULAI DOKTER
                                ======================================== */
                            COUNT(
                                CASE
                                    WHEN SA.TGL_SELESAI_ANAM IS NOT NULL
                                        AND MP.MULAIPERIKSA IS NOT NULL
                                        AND MP.MULAIPERIKSA >= SA.TGL_SELESAI_ANAM
                                        AND (MP.MULAIPERIKSA - SA.TGL_SELESAI_ANAM) * 24 * 60 <= 30
                                    THEN 1
                                END
                            ) AS JML_ANAM_DOKTER_30,

                            ROUND(
                                100 *
                                COUNT(
                                    CASE
                                        WHEN SA.TGL_SELESAI_ANAM IS NOT NULL
                                            AND MP.MULAIPERIKSA IS NOT NULL
                                            AND MP.MULAIPERIKSA >= SA.TGL_SELESAI_ANAM
                                            AND (MP.MULAIPERIKSA - SA.TGL_SELESAI_ANAM) * 24 * 60 <= 30
                                        THEN 1
                                    END
                                )
                                /
                                NULLIF(
                                    COUNT(
                                        CASE
                                            WHEN SA.TGL_SELESAI_ANAM IS NOT NULL
                                                AND MP.MULAIPERIKSA IS NOT NULL
                                                AND MP.MULAIPERIKSA >= SA.TGL_SELESAI_ANAM
                                            THEN 1
                                        END
                                    ),
                                    0
                                ),
                                2
                            ) AS PERSEN_ANAM_DOKTER_30,

                            ROUND(
                                AVG(
                                    CASE
                                        WHEN SA.TGL_SELESAI_ANAM IS NOT NULL
                                            AND MP.MULAIPERIKSA IS NOT NULL
                                            AND MP.MULAIPERIKSA >= SA.TGL_SELESAI_ANAM
                                        THEN
                                            (MP.MULAIPERIKSA - SA.TGL_SELESAI_ANAM) * 24 * 60
                                    END
                                ),
                                2
                            ) AS AVG_WAKTU_ANAM_DOKTER

                        FROM SR01_KEU_EPISODE A

                        /* ========================================
                        MULAI PEMERIKSAAN DOKTER
                        ======================================== */
                        LEFT JOIN (
                            SELECT PASIEN_ID,
                                EPISODE_ID,
                                MIN(CREATED_DATE) AS MULAIPERIKSA
                            FROM WEB_CO_MULAI_PERIKSA
                            WHERE SHOW_ITEM = '1'
                            GROUP BY PASIEN_ID,
                                    EPISODE_ID
                        ) MP
                            ON MP.PASIEN_ID = A.PASIEN_ID
                        AND MP.EPISODE_ID = A.EPISODE_ID

                        /* ========================================
                        CHECK-IN + MULAI ANAMNESA
                        ======================================== */
                        LEFT JOIN (
                            SELECT PASIEN_ID,
                                EPISODE_ID,
                                MIN(TGL_HADIR) AS TGLCHECKIN,
                                MIN(TGL_MULAI_ANAM) AS TGL_MULAI_ANAM
                            FROM WEB_CO_REGISTRASI_ONLINE_HD
                            WHERE LOKASI_ID = '001'
                            AND AKTIF = '1'
                            GROUP BY PASIEN_ID,
                                    EPISODE_ID
                        ) RH
                            ON RH.PASIEN_ID = A.PASIEN_ID
                        AND RH.EPISODE_ID = A.EPISODE_ID

                        /* ========================================
                        SELESAI ANAMNESA
                        ======================================== */
                        LEFT JOIN (
                            SELECT PASIEN_ID,
                                EPISODE_ID,
                                MIN(CREATED_DATE) AS TGL_SELESAI_ANAM
                            FROM SR01_MED_ASSESMEN_AWAL
                            WHERE AKTIF = '1'
                            GROUP BY PASIEN_ID,
                                    EPISODE_ID
                        ) SA
                            ON SA.PASIEN_ID = A.PASIEN_ID
                        AND SA.EPISODE_ID = A.EPISODE_ID

                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'

                        AND TO_CHAR(A.TGL_MASUK, 'YYYY') = '".$periode."'

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
                            'POLIFISIO',
                            'POLIFISOKUP',
                            'POLIFISWICARA',
                            'POLI0000000025'
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
                                WHERE T.LOKASI_ID   = '001'
                                    AND T.AKTIF       = '1'
                                    AND T.DONE_STATUS = '01'
                                    AND T.STATUS      = '1'
                                    AND T.PASIEN_ID   = A.PASIEN_ID
                                    AND T.EPISODE_ID  = A.EPISODE_ID
                            )
                        )

                        GROUP BY TO_CHAR(A.TGL_MASUK, 'MM')

                        ORDER BY BULAN
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datajampulangpasienbln($periode){
            $query =
                    "
                        SELECT X.TAHUN,
                            X.BULAN,
                            X.TOTAL,
                            X.SEBELUM_12,
                            X.SESUDAH_12,
                            X.JML_SNACK,
                            X.JML_MAKAN,
                            X.BIAYA_SNACK,
                            X.BIAYA_MAKAN,
                            ROUND(
                                (X.SEBELUM_12 / NULLIF(X.TOTAL, 0)) * 100,
                                2
                            ) AS PERSENTASE,
                            (X.BIAYA_SNACK + X.BIAYA_MAKAN) AS TOTAL_BIAYA,
                            SYSDATE AS LAST_UPDATE
                        FROM (
                            SELECT TO_CHAR(A.TGL_KELUAR, 'YYYY') AS TAHUN,
                                TO_CHAR(A.TGL_KELUAR, 'MM')   AS BULAN,

                                COUNT(*) AS TOTAL,

                                /* Pasien pulang sebelum jam 12 */
                                SUM(
                                    CASE
                                        WHEN TO_NUMBER(TO_CHAR(A.TGL_KELUAR, 'HH24')) < 12
                                        THEN 1
                                        ELSE 0
                                    END
                                ) AS SEBELUM_12,

                                /* Pasien pulang jam 12 atau setelahnya */
                                SUM(
                                    CASE
                                        WHEN TO_NUMBER(TO_CHAR(A.TGL_KELUAR, 'HH24')) >= 12
                                        THEN 1
                                        ELSE 0
                                    END
                                ) AS SESUDAH_12,

                                /* Jumlah snack: jam 12 - 14 */
                                SUM(
                                    CASE
                                        WHEN TO_NUMBER(TO_CHAR(A.TGL_KELUAR, 'HH24')) BETWEEN 12 AND 14
                                        THEN 1
                                        ELSE 0
                                    END
                                ) AS JML_SNACK,

                                /* Jumlah makan: jam 15 ke atas */
                                SUM(
                                    CASE
                                        WHEN TO_NUMBER(TO_CHAR(A.TGL_KELUAR, 'HH24')) >= 15
                                        THEN 1
                                        ELSE 0
                                    END
                                ) AS JML_MAKAN,

                                /* Biaya snack */
                                SUM(
                                    CASE
                                        WHEN TO_NUMBER(TO_CHAR(A.TGL_KELUAR, 'HH24')) >= 12
                                        THEN
                                            CASE
                                                WHEN TRIM(A.KELAS_ID) = 'V' THEN 12183
                                                WHEN TRIM(A.KELAS_ID) = '1' THEN 9021
                                                WHEN TRIM(A.KELAS_ID) = '2' THEN 7963
                                                WHEN TRIM(A.KELAS_ID) = '3' THEN 7832
                                                ELSE 0
                                            END
                                        ELSE 0
                                    END
                                ) AS BIAYA_SNACK,

                                /* Biaya makan */
                                SUM(
                                    CASE
                                        WHEN TO_NUMBER(TO_CHAR(A.TGL_KELUAR, 'HH24')) >= 15
                                        THEN
                                            CASE
                                                WHEN TRIM(A.KELAS_ID) = 'V' THEN 41526
                                                WHEN TRIM(A.KELAS_ID) = '1' THEN 36261
                                                WHEN TRIM(A.KELAS_ID) = '2' THEN 30605
                                                WHEN TRIM(A.KELAS_ID) = '3' THEN 27849
                                                ELSE 0
                                            END
                                        ELSE 0
                                    END
                                ) AS BIAYA_MAKAN

                            FROM SR01_KEU_EPISODE A

                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'I'
                            AND A.PULANG_ID NOT IN ('P02', 'P04', 'P10', 'P11', 'P0X')
                            AND A.KELAS_ID <> 'V'
                            AND A.EPISODE_ID=(SELECT EPISODE_ID FROM SR01_PASIEN_RANAP WHERE PERLU_VISIT='T' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                            AND TO_CHAR(A.TGL_KELUAR, 'YYYY') = '".$periode."'

                            GROUP BY TO_CHAR(A.TGL_KELUAR, 'YYYY'),
                                    TO_CHAR(A.TGL_KELUAR, 'MM')
                        ) X

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datajampulangharian(){
            $query =
                    "
                        SELECT X.PERIODE,
                            X.TOTAL,
                            X.SEBELUM_12,
                            X.SESUDAH_12,
                            X.JML_SNACK,
                            X.JML_MAKAN,
                            X.BIAYA_SNACK,
                            X.BIAYA_MAKAN,
                            ROUND ( (X.SEBELUM_12 / NULLIF (X.TOTAL, 0)) * 100, 2) AS PERSENTASE,
                            (X.BIAYA_SNACK + X.BIAYA_MAKAN) AS TOTAL_BIAYA,
                            SYSDATE AS LAST_UPDATE
                        FROM (  SELECT TO_CHAR (A.TGL_KELUAR, 'DD.MM.YYYY') AS PERIODE,
                                        COUNT ( * ) AS TOTAL,
                                        SUM(CASE
                                                WHEN TO_NUMBER (TO_CHAR (A.TGL_KELUAR, 'HH24')) < 12
                                                THEN
                                                1
                                                ELSE
                                                0
                                            END)
                                            AS SEBELUM_12,
                                        SUM(CASE
                                                WHEN TO_NUMBER (TO_CHAR (A.TGL_KELUAR, 'HH24')) >= 12
                                                THEN
                                                1
                                                ELSE
                                                0
                                            END)
                                            AS SESUDAH_12,
                                        SUM(CASE
                                                WHEN TO_NUMBER (TO_CHAR (A.TGL_KELUAR, 'HH24')) BETWEEN 12
                                                                                                    AND  14
                                                THEN
                                                1
                                                ELSE
                                                0
                                            END)
                                            AS JML_SNACK,
                                        SUM(CASE
                                                WHEN TO_NUMBER (TO_CHAR (A.TGL_KELUAR, 'HH24')) >= 15
                                                THEN
                                                1
                                                ELSE
                                                0
                                            END)
                                            AS JML_MAKAN,
                                        SUM (CASE
                                                WHEN TO_NUMBER (TO_CHAR (A.TGL_KELUAR, 'HH24')) >= 12
                                                THEN
                                                    CASE
                                                    WHEN TRIM (A.KELAS_ID) = 'V' THEN 12183
                                                    WHEN TRIM (A.KELAS_ID) = '1' THEN 9021
                                                    WHEN TRIM (A.KELAS_ID) = '2' THEN 7963
                                                    WHEN TRIM (A.KELAS_ID) = '3' THEN 7832
                                                    ELSE 0
                                                    END
                                                ELSE
                                                    0
                                            END)
                                            AS BIAYA_SNACK,
                                        SUM (CASE
                                                WHEN TO_NUMBER (TO_CHAR (A.TGL_KELUAR, 'HH24')) >= 15
                                                THEN
                                                    CASE
                                                    WHEN TRIM (A.KELAS_ID) = 'V' THEN 41526
                                                    WHEN TRIM (A.KELAS_ID) = '1' THEN 36261
                                                    WHEN TRIM (A.KELAS_ID) = '2' THEN 30605
                                                    WHEN TRIM (A.KELAS_ID) = '3' THEN 27849
                                                    ELSE 0
                                                    END
                                                ELSE
                                                    0
                                            END)
                                            AS BIAYA_MAKAN
                                    FROM SR01_KEU_EPISODE A
                                WHERE     A.LOKASI_ID = '001'
                                        AND A.AKTIF = '1'
                                        AND A.JENIS_EPISODE = 'I'
                                        AND A.PULANG_ID NOT IN ('P02', 'P04', 'P10', 'P11', 'P0X')
                                        AND A.KELAS_ID <> 'V'
                                        AND A.EPISODE_ID=(SELECT EPISODE_ID FROM SR01_PASIEN_RANAP WHERE PERLU_VISIT='T' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                                        AND A.TGL_KELUAR IS NOT NULL
                                        AND A.TGL_KELUAR >= TRUNC (SYSDATE) - 14
                                        AND A.TGL_KELUAR < TRUNC (SYSDATE)
                                GROUP BY TO_CHAR (A.TGL_KELUAR, 'DD.MM.YYYY')) X
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>