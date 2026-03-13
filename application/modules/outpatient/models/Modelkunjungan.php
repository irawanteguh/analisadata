<?php
    class Modelkunjungan extends CI_Model{

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

        function databooking($startdate,$endate){
            $query =
                    "
                        SELECT 
                            A.BOOKING_ID,
                            A.PASIEN_ID,
                            A.EPISODE_ID,
                            TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY') AS TGLMASUK,
                            A.POLI_ID,
                            A.DOKTER_ID,
                            A.REKANAN_ID,
                            A.JAM_MULAI,
                            A.JAM_SELESAI,
                            A.URUT,

                            ''''||P.INT_PASIEN_ID AS MRPASIEN,
                            P.NO_SELULAR,
                            SR01_GET_SUFFIX(A.PASIEN_ID) AS NAMAPASIEN,
                            D.NAMA AS NAMADOKTER,
                            PL.KETERANGAN AS POLITUJUAN,
                            PR.NAMA AS PROVIDER

                        FROM WEB_CO_REGISTRASI_ONLINE_HD A

                        LEFT JOIN SR01_GEN_PASIEN_MS P 
                            ON P.PASIEN_ID = A.PASIEN_ID
                            AND P.LOKASI_ID = '001'
                            AND P.AKTIF = '1'

                        LEFT JOIN SR01_MED_DOKTER_MS D 
                            ON D.DOKTER_ID = A.DOKTER_ID

                        LEFT JOIN SR01_MED_POLI_MS PL 
                            ON PL.POLI_ID = A.POLI_ID
                        
                        LEFT JOIN SR01_KEU_REKANAN_MS PR 
                            ON PR.REKANAN_ID = A.REKANAN_ID

                        WHERE A.LOKASI_ID = '001'
                        AND   A.AKTIF = '1'
                        AND   TRUNC(A.TGL_MASUK) BETWEEN TRUNC(TO_DATE('".$startdate."','YYYY-MM-DD')) AND TRUNC(TO_DATE('".$endate."','YYYY-MM-DD'))
                        
                        ORDER BY TGL_MASUK ASC, POLITUJUAN, NAMADOKTER, JAM_MULAI
                ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjungan($startdate,$endate){
            $query =
                    "
                        WITH DIAG AS (
                            SELECT
                                XX.EPISODE_ID,

                                RTRIM(
                                    XMLAGG(
                                        XMLELEMENT(E,
                                            '[' || MS.KODE_ICD || '] ' ||
                                            REPLACE(MS.NM_DIAG, CHR(39), '') ||
                                            '/batasjenis' || XX.JENIS || ';'
                                        )
                                        ORDER BY XX.JENIS, XX.CREATED_DATE
                                    ).EXTRACT('//text()').GETCLOBVAL()
                                , ';') AS DIAG

                            FROM SR01_RM_RESUME_ICD10 XX

                            JOIN (
                                SELECT KODE, KODE_ICD, NM_DIAG1 AS NM_DIAG
                                FROM SR01_MED_ICD10_MS
                                UNION ALL
                                SELECT KODE, KODE_ICD, DESCRIPTION AS NM_DIAG
                                FROM SR01_MED_ICD_IDRG
                            ) MS ON MS.KODE = XX.ICD10_ID

                            WHERE XX.LOKASI_ID = '001'
                            AND XX.ICD10_ID <> '-'

                            GROUP BY XX.EPISODE_ID
                        ),


                    OBAT AS (
                        SELECT
                            EPISODE_ID,
                            PASIEN_ID,

                            RTRIM(
                                XMLAGG(
                                    XMLELEMENT(E,
                                        REPLACE(NAMA_OBAT, CHR(39), '') || ';'
                                    )
                                    ORDER BY NAMA_OBAT
                                ).EXTRACT('//text()').GETCLOBVAL()
                            , ';') AS OBAT

                        FROM (
                            SELECT DISTINCT
                                RESEP.EPISODE_ID,
                                RESEP.PASIEN_ID,
                                REPLACE(RESEP.NAMA_OBAT, CHR(39), '') AS NAMA_OBAT
                            FROM WEB_CO_RESEP_DT RESEP
                            WHERE RESEP.LOKASI_ID = '001'
                            AND RESEP.SHOW_ITEM = '1'
                            AND RESEP.NAMA_OBAT IS NOT NULL
                        )

                        GROUP BY EPISODE_ID, PASIEN_ID
                    )


                    SELECT
                        A.PASIEN_ID,
                        A.EPISODE_ID,
                        A.JENIS_EPISODE,
                        TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY') TGLMASUK,
                        TO_CHAR(A.TGL_KELUAR,'DD.MM.YYYY') TGLKELUAR,
                        A.DOKTER_ID,
                        A.POLI_ID,

                        ''''||PAS.INT_PASIEN_ID MRPASIEN,
                        PAS.GERIATRI,
                        TO_CHAR(PAS.TGL_LAHIR,'DD.MM.YYYY') TGLLAHIR,
                        PAS.SEX_ID,
                        PAS.TEMPAT_LAHIR_TXT,
                        PAS.NAMA_IBUKANDUNG,

                        POL.KETERANGAN POLITUJUAN,
                        DOK.NAMA NAMADOKTER,
                        PROV.NAMA PROVIDER,
                        
                        ''''||ANAM.ANT_BB BB, ''''||ANAM.ANT_TB TB, ''''||ANAM.ANT_IMT IMT,

                        SR01_GET_SUFFIX(A.PASIEN_ID) NAMAPASIEN,
                        SR01_HITUNG_UMUR(PAS.TGL_LAHIR, TRUNC(SYSDATE)) UMURSAATINI,
                        SR01_HITUNG_UMUR(PAS.TGL_LAHIR, A.TGL_MASUK) UMURSAATPELAYANAN,

                        D.DIAG,
                        E.OBAT

                    FROM SR01_KEU_EPISODE A
                    LEFT JOIN SR01_GEN_PASIEN_MS PAS   ON PAS.PASIEN_ID = A.PASIEN_ID
                    LEFT JOIN SR01_MED_POLI_MS POL     ON POL.POLI_ID   = A.POLI_ID
                    LEFT JOIN SR01_MED_DOKTER_MS DOK   ON DOK.DOKTER_ID = A.DOKTER_ID
                    LEFT JOIN SR01_KEU_REKANAN_MS PROV ON PROV.REKANAN_ID = A.REKANAN_ID
                    LEFT JOIN SR01_MED_ANAMAWAL ANAM   ON ANAM.PASIEN_ID = A.PASIEN_ID
                                                        AND ANAM.EPISODE_ID = A.EPISODE_ID
                    LEFT JOIN DIAG D                   ON D.EPISODE_ID  = A.EPISODE_ID
                    LEFT JOIN OBAT E                   ON E.PASIEN_ID  = A.PASIEN_ID
                                                        AND E.EPISODE_ID = A.EPISODE_ID

                    WHERE A.LOKASI_ID = '001'
                    AND A.AKTIF = '1'
                    AND A.JENIS_EPISODE = 'O'
                    AND A.STATUS_EPISODE <> '99'
                    AND A.EPISODE_ID NOT LIKE 'M%'
                    AND A.DOKTER_ID='DR. P0000000001'
                    AND   TRUNC(A.TGL_MASUK) BETWEEN TRUNC(TO_DATE('".$startdate."','YYYY-MM-DD')) AND TRUNC(TO_DATE('".$endate."','YYYY-MM-DD'))

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

                    ORDER BY A.TGL_MASUK DESC, POLITUJUAN ASC
                ";

            $recordset = $this->db->query($query);
            $results = [];

            foreach($recordset->result_array() as $row){

                if (isset($row['DIAG']) && is_object($row['DIAG'])) {
                    $row['DIAG'] = $row['DIAG']->load();
                }

                if (isset($row['OBAT']) && is_object($row['OBAT'])) {
                    $row['OBAT'] = $row['OBAT']->load();
                }

                $results[] = $row;
            }

            return $results;
        }


    }
?>