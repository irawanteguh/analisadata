<?php
    class Modeldashboard extends CI_Model{
        
        function pasientransit(){
            $query =
                    "
                        SELECT A.PASIEN_ID, A.EPISODE_ID,
                                TO_CHAR(KMR.TGL_MASUK,'DD.MM.YYYY HH24:MI:SS') TGLMASUKTRANSIT,
                                PS.INT_PASIEN_ID                  AS MRPAS,
                                PS.SEX_ID                         AS SEXID,
                                SR01_GET_SUFFIX(A.PASIEN_ID)      AS NAMAPASIEN

                        FROM SR01_KEU_EPISODE A
                        LEFT JOIN SR01_GEN_PASIEN_MS PS
                            ON PS.LOKASI_ID = '001'
                            AND PS.AKTIF = '1'
                            AND PS.PASIEN_ID = A.PASIEN_ID
                        LEFT JOIN SR01_KEU_TRANSKMR_IT KMR
                            ON  KMR.LOKASI_ID = '001'
                            AND KMR.AKTIF = '1'
                            AND KMR.RUANG_ID = 'TRANSIT'
                            AND KMR.PASIEN_ID = A.PASIEN_ID
                            AND KMR.EPISODE_ID = A.EPISODE_ID

                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.STATUS_EPISODE='00'
                        AND   A.JENIS_EPISODE='I'
                        AND   A.RUANGRWT_ID LIKE 'TRANSIT%'
                        AND   EXISTS (SELECT 1 FROM SR01_MED_RUANG_PRWT WHERE LOKASI_ID='001' AND AKTIF='1' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)

                        order by TGLMASUKTRANSIT ASC

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        // function pasienmeninggal($startdate,$endate){
        //     $query =
        //             "
        //                 SELECT A.PASIEN_ID, A.EPISODE_ID, TGL_LOGINPLG,
        //                         PS.INT_PASIEN_ID                  AS MRPAS,
        //                         PS.SEX_ID                         AS SEXID,
        //                         SR01_GET_SUFFIX(A.PASIEN_ID)      AS NAMAPASIEN

        //                 FROM SR01_KEU_EPISODE A
        //                 LEFT JOIN SR01_GEN_PASIEN_MS PS
        //                     ON PS.LOKASI_ID = '001'
        //                     AND PS.AKTIF = '1'
        //                     AND PS.PASIEN_ID = A.PASIEN_ID

        //                 WHERE A.LOKASI_ID='001'
        //                 AND   A.AKTIF='1'
        //                 AND   A.STATUS_EPISODE='55'
        //                 AND   A.JENIS_EPISODE='I'
        //                 AND   A.PULANG_ID IN ('P04','P10','P11')
        //                 AND   TRUNC(A.TGL_KELUAR) BETWEEN TRUNC(TO_DATE('".$startdate."','YYYY-MM-DD')) AND TRUNC(TO_DATE('".$endate."','YYYY-MM-DD'))


        //             ";

        //     $recordset = $this->db->query($query);
        //     $recordset = $recordset->result();
        //     return $recordset;
        // }

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

        function demografiumur(){
            $query =
                    "
                        SELECT
                            range_umur,
                            SUM(CASE WHEN SEX_ID = 'L' THEN 1 ELSE 0 END) AS laki_laki,
                            SUM(CASE WHEN SEX_ID = 'P' THEN 1 ELSE 0 END) AS perempuan
                        FROM (
                            SELECT
                                SEX_ID,
                                umur,
                                CASE 
                                    WHEN umur >= 75 THEN '75+'
                                    ELSE FLOOR(umur/5)*5 || '-' || (FLOOR(umur/5)*5 + 4)
                                END AS range_umur
                            FROM (
                                SELECT A.SEX_ID,
                                    FLOOR(MONTHS_BETWEEN(SYSDATE, A.TGL_LAHIR) / 12) AS umur
                                FROM SR01_GEN_PASIEN_MS A
                                WHERE A.TGL_LAHIR IS NOT NULL
                                AND   A.PASIEN_ID NOT IN (SELECT PASIEN_ID FROM SR01_KEU_EPISODE WHERE PULANG_ID IN ('P0X','P04','P10','P11') AND PASIEN_ID=A.PASIEN_ID)
                                AND   A.TGL_LAHIR <= SYSDATE
                            )
                        )
                        GROUP BY range_umur
                        ORDER BY MIN(umur)
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganrj($periode){
            $query = "
                        SELECT
                            TO_CHAR(A.TGL_MASUK,'MM') AS BULAN,

                            COUNT(CASE 
                                WHEN A.REKANAN_ID = 'EXECU0000000001' 
                                THEN 1 
                            END) AS KUNJUNGAN_EXECUTIVE,

                            COUNT(CASE 
                                WHEN A.REKANAN_ID <> 'EXECU0000000001' 
                                    OR A.REKANAN_ID IS NULL
                                THEN 1 
                            END) AS KUNJUNGAN_NON_EXECUTIVE,

                            COUNT(*) AS TOTAL_KUNJUNGAN

                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'
                        AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                        AND (
                                /* Poli biasa harus ada tindakan */
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
                                        WHERE T.LOKASI_ID  = '001'
                                        AND   T.AKTIF      = '1'
                                        AND   T.DONE_STATUS= '01'
                                        AND   T.STATUS     = '1'
                                        AND   T.PASIEN_ID  = A.PASIEN_ID
                                        AND   T.EPISODE_ID = A.EPISODE_ID
                                    )
                                )

                                /* Poli langsung dihitung */
                                OR A.POLI_ID IN (
                                    'POLIFISIO',
                                    'POLIFISOKUP',
                                    'POLIFISWICARA',
                                    'HEMOD0000000000'
                                )
                        )

                        GROUP BY TO_CHAR(A.TGL_MASUK,'MM')
                        ORDER BY BULAN
                     ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganri($periode){
            $query = "
                SELECT
                    TO_CHAR(A.TGL_MASUK,'MM') AS BULAN,
                    COUNT(*) AS TOTAL_KUNJUNGAN
                FROM SR01_KEU_EPISODE A
                WHERE A.LOKASI_ID = '001'
                AND A.AKTIF = '1'
                AND A.JENIS_EPISODE = 'I'
                AND A.STATUS_EPISODE <> '99'
                AND TO_CHAR(A.TGL_MASUK,'YYYY')='".$periode."'
                GROUP BY TO_CHAR(A.TGL_MASUK,'MM')
                ORDER BY BULAN
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganigd($periode){
            $query = "
                        SELECT
                            TO_CHAR(A.TGL_MASUK,'MM') AS BULAN,
                            COUNT(*) AS TOTAL_KUNJUNGAN
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.STATUS_EPISODE <> '99'
                        AND TO_CHAR(A.TGL_MASUK,'YYYY')='".$periode."'
                        AND (
                                (A.JENIS_EPISODE = 'O' AND A.POLI_ID = 'UGD01')
                                OR (A.JENIS_EPISODE ='I' AND A.EPISODE_ID = (SELECT EPISODE_ID FROM SR01_PASIEN_IGD WHERE PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID))
                            )
                        GROUP BY TO_CHAR(A.TGL_MASUK,'MM')
                        ORDER BY BULAN
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganigdprovider($periode){
            $query = "
                        SELECT
                            CASE 
                                WHEN A.REKANAN_ID = 'BPJS' THEN 'BPJS'
                                WHEN A.REKANAN_ID = 'BPJSTK' THEN 'BPJS TENAGA KERJA'
                                WHEN A.REKANAN_ID = 'UMUM' THEN 'PASIEN UMUM'
                                WHEN A.REKANAN_ID = 'JASARAHARJA' THEN 'JASA RAHARJA'
                                ELSE 'LAIN-LAIN'
                            END AS LABEL,
                            COUNT(*) AS TOTAL
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.STATUS_EPISODE <> '99'
                        AND TO_CHAR(A.TGL_MASUK,'YYYY')='".$periode."'
                        AND (
                                (A.JENIS_EPISODE = 'O' AND A.POLI_ID = 'UGD01')
                                OR (A.JENIS_EPISODE ='I' AND A.EPISODE_ID = (
                                    SELECT EPISODE_ID 
                                    FROM SR01_PASIEN_IGD 
                                    WHERE PASIEN_ID=A.PASIEN_ID 
                                    AND EPISODE_ID=A.EPISODE_ID
                                ))
                            )
                        GROUP BY 
                            CASE 
                                WHEN A.REKANAN_ID = 'BPJS' THEN 'BPJS'
                                WHEN A.REKANAN_ID = 'BPJSTK' THEN 'BPJS TENAGA KERJA'
                                WHEN A.REKANAN_ID = 'UMUM' THEN 'PASIEN UMUM'
                                WHEN A.REKANAN_ID = 'JASARAHARJA' THEN 'JASA RAHARJA'
                                ELSE 'LAIN-LAIN'
                            END
                        ORDER BY TOTAL DESC
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganrjprovider($periode){
            $query = "
                        SELECT
                            CASE 
                                WHEN A.REKANAN_ID = 'BPJS' THEN 'BPJS'
                                WHEN A.REKANAN_ID = 'UMUM' THEN 'PASIEN UMUM'
                                WHEN A.REKANAN_ID IN ('KARYA0000000002','MCU K0000000001') THEN 'KARYAWAN'
                                WHEN A.REKANAN_ID = 'EXECU0000000001' THEN 'EXECUTIVE'
                                ELSE 'LAIN-LAIN'
                            END AS LABEL,
                            COUNT(*) AS TOTAL
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'
                        AND TO_CHAR(A.TGL_MASUK,'YYYY')='".$periode."'
                        AND (
                            A.POLI_ID IN (
                                'POLIFISIO','POLIFISOKUP','POLIFISWICARA',
                                'HEMOD0000000000','CAPD0000000001'
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
                        GROUP BY
                            CASE 
                                WHEN A.REKANAN_ID = 'BPJS' THEN 'BPJS'
                                WHEN A.REKANAN_ID = 'UMUM' THEN 'PASIEN UMUM'
                                WHEN A.REKANAN_ID IN ('KARYA0000000002','MCU K0000000001') THEN 'KARYAWAN'
                                WHEN A.REKANAN_ID = 'EXECU0000000001' THEN 'EXECUTIVE'
                                ELSE 'LAIN-LAIN'
                            END
                        ORDER BY TOTAL DESC
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjunganriprovider($periode){
            $query = "
                        SELECT
                            CASE
                                WHEN A.REKANAN_ID = 'BPJS' THEN 'BPJS'
                                WHEN A.REKANAN_ID = 'UMUM' THEN 'PASIEN UMUM'
                                WHEN A.REKANAN_ID = 'JASARAHARJA' THEN 'JASA RAHARJA'
                                WHEN A.REKANAN_ID = 'BPJSTK' THEN 'BPJS TENAGA KERJA'
                                ELSE 'LAIN-LAIN'
                            END AS LABEL,
                            COUNT(*) AS TOTAL
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'I'
                        AND A.STATUS_EPISODE <> '99'
                        AND TO_CHAR(A.TGL_MASUK,'YYYY')='".$periode."'
                        GROUP BY
                            CASE
                                WHEN A.REKANAN_ID = 'BPJS' THEN 'BPJS'
                                WHEN A.REKANAN_ID = 'UMUM' THEN 'PASIEN UMUM'
                                WHEN A.REKANAN_ID = 'JASARAHARJA' THEN 'JASA RAHARJA'
                                WHEN A.REKANAN_ID = 'BPJSTK' THEN 'BPJS TENAGA KERJA'
                                ELSE 'LAIN-LAIN'
                            END
                        ORDER BY TOTAL DESC
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
        
    }
?>