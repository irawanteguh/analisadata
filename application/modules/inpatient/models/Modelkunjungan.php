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


        public function datakunjungan($startdate,$endate){
            $query = "
                WITH DIAG AS (
                    SELECT
                        XX.EPISODE_ID,

                        RTRIM(
                            XMLAGG(
                                XMLELEMENT(E,
                                    '[' || MS.KODE_ICD || '] ' ||
                                    MS.NM_DIAG || '/batasjenis' || XX.JENIS || ';'
                                )
                                ORDER BY XX.JENIS, XX.CREATED_DATE
                            ).EXTRACT('//text()').GETCLOBVAL(),
                        ';') AS DIAG

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
                                XMLELEMENT(E, NAMA_OBAT || ';')
                                ORDER BY NAMA_OBAT
                            ).EXTRACT('//text()').GETCLOBVAL(),
                        ';') AS OBAT

                    FROM (
                        SELECT DISTINCT
                            RESEP.EPISODE_ID,
                            RESEP.PASIEN_ID,
                            RESEP.NAMA_OBAT
                        FROM WEB_CO_RESEP_DT RESEP
                        WHERE RESEP.LOKASI_ID = '001'
                        AND RESEP.SHOW_ITEM = '1'
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

                    ''''||PAS.INT_PASIEN_ID MRPASIEN,
                    PAS.GERIATRI,
                    TO_CHAR(PAS.TGL_LAHIR,'DD.MM.YYYY') TGLLAHIR,
                    PAS.SEX_ID,
                    PAS.TEMPAT_LAHIR_TXT,
                    PAS.NAMA_IBUKANDUNG,

                    DOK.NAMA NAMADOKTER,
                    PROV.NAMA PROVIDER,

                    SR01_GET_SUFFIX(A.PASIEN_ID) NAMAPASIEN,
                    SR01_HITUNG_UMUR(PAS.TGL_LAHIR, TRUNC(SYSDATE)) UMURSAATINI,
                    SR01_HITUNG_UMUR(PAS.TGL_LAHIR, A.TGL_MASUK) UMURSAATPELAYANAN,

                    D.DIAG,
                    E.OBAT

                FROM SR01_KEU_EPISODE A

                LEFT JOIN SR01_GEN_PASIEN_MS PAS
                    ON PAS.PASIEN_ID = A.PASIEN_ID

                LEFT JOIN SR01_MED_DOKTER_MS DOK
                    ON DOK.DOKTER_ID = A.DOKTER_ID

                LEFT JOIN SR01_KEU_REKANAN_MS PROV
                    ON PROV.REKANAN_ID = A.REKANAN_ID

                LEFT JOIN DIAG D
                    ON D.EPISODE_ID = A.EPISODE_ID

                LEFT JOIN OBAT E
                    ON E.PASIEN_ID = A.PASIEN_ID
                    AND E.EPISODE_ID = A.EPISODE_ID

                WHERE A.LOKASI_ID = '001'
                AND A.AKTIF = '1'
                AND A.JENIS_EPISODE = 'I'
                AND A.STATUS_EPISODE = '55'
                AND TRUNC(A.TGL_MASUK) BETWEEN TRUNC(TO_DATE('".$startdate."','YYYY-MM-DD')) AND TRUNC(TO_DATE('".$endate."','YYYY-MM-DD'))

                ORDER BY A.TGL_MASUK DESC
            ";

            $queryExec = $this->db->query($query);
            $rows = $queryExec->result_array();

            foreach ($rows as &$row) {

                // HANDLE CLOB DIAG
                if (isset($row['DIAG']) && is_object($row['DIAG'])) {
                    $row['DIAG'] = $row['DIAG']->load();
                }

                // HANDLE CLOB OBAT
                if (isset($row['OBAT']) && is_object($row['OBAT'])) {
                    $row['OBAT'] = $row['OBAT']->load();
                }

                $row['DIAG'] = $row['DIAG'] ?? '';
                $row['OBAT'] = $row['OBAT'] ?? '';

                // Fix UTF8 (anti JSON error)
                $row['DIAG'] = mb_convert_encoding($row['DIAG'], 'UTF-8', 'UTF-8');
                $row['OBAT'] = mb_convert_encoding($row['OBAT'], 'UTF-8', 'UTF-8');
            }

            return $rows;
        }



    }
?>