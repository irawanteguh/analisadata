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

        function kpipasienrawatinappulangagregate($periode){
            $query =
                    "
                        
                        SELECT
                            TO_CHAR(X.TANGGAL, 'DD.MM.YYYY') AS PERIODE,

                            SUM(CASE
                                WHEN TO_CHAR(X.TGL_KELUAR, 'HH24') < '12'
                                THEN 1 ELSE 0
                            END) AS SEBELUM_12,

                            SUM(CASE
                                WHEN TO_CHAR(X.TGL_KELUAR, 'HH24') >= '12'
                                THEN 1 ELSE 0
                            END) AS SESUDAH_12,

                            SUM(X.TUTD) AS TUTD,

                            SUM(CASE
                                WHEN X.TUTD = 1
                                AND TO_CHAR(X.TGL_KELUAR, 'HH24') < '12'
                                THEN 1 ELSE 0
                            END) AS TUTD_SEBELUM_12,

                            SUM(CASE
                                WHEN X.TUTD = 1
                                AND TO_CHAR(X.TGL_KELUAR, 'HH24') >= '12'
                                THEN 1 ELSE 0
                            END) AS TUTD_SESUDAH_12

                        FROM (
                            SELECT
                                TRUNC(A.TGL_KELUAR) AS TANGGAL,
                                A.TGL_KELUAR,
                                CASE
                                    WHEN EXISTS (
                                        SELECT 1
                                        FROM SR01_PASIEN_RANAP B
                                        WHERE B.PERLU_VISIT = 'T'
                                        AND B.PASIEN_ID = A.PASIEN_ID
                                        AND B.EPISODE_ID = A.EPISODE_ID
                                    )
                                    THEN 1
                                    ELSE 0
                                END AS TUTD
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'I'
                            AND A.PULANG_ID NOT IN ('P02', 'P04', 'P10', 'P11', 'P0X')
                            AND A.KELAS_ID <> 'V'
                            AND A.TGL_KELUAR IS NOT NULL
                            AND A.TGL_KELUAR >= TO_DATE('01.10.2026', 'DD.MM.YYYY')
                            AND TO_CHAR(A.TGL_KELUAR,'YYYY')='".$periode."'
                        ) X

                        GROUP BY X.TANGGAL
                        ORDER BY X.TANGGAL DESC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function kpipasienrawatinappulangdetail(){
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
                            AND  TRUNC(A.TGL_KELUAR)=TRUNC(SYSDATE)
                        )X
                        LEFT JOIN SR01_MED_DOKTER_MS D
                        ON D.DOKTER_ID = X.DOKTER_ID
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function pembatalanoperasielektif($periode){
            $query = "
                        SELECT TO_CHAR(TRUNC(A.TGL_TINDAKAN), 'DD.MM.YYYY') AS PERIODE,
                                COUNT (*) AS TOTAL,
                                SUM(CASE WHEN A.STATUS_ID <> '99' THEN 1 ELSE 0 END) AS REGISTER,
                                SUM(CASE WHEN A.STATUS_ID = '99' AND TRUNC (A.TGL_TINDAKAN) = TRUNC (A.BATAL_DATE) THEN 1 ELSE 0 END) AS BATAL
                        FROM SR01_MED_OK_LOG A
                        WHERE A.LOKASI_ID = '001'
                        AND   A.AKTIF = '1'
                        AND   A.CITO = '0'
                        AND   TO_CHAR(A.TGL_TINDAKAN,'YYYY') = '".$periode."'
                        GROUP BY TRUNC(A.TGL_TINDAKAN)
                        ORDER BY TRUNC(A.TGL_TINDAKAN) DESC
                ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
    }
?>