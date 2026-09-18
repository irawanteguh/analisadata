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