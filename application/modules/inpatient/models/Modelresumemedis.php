<?php
    class Modelresumemedis extends CI_Model{

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

        function resumemedis($periode){
            $query =
                    "
                        WITH RESUME AS (
                            SELECT
                                PASIEN_ID,
                                EPISODE_ID,
                                TRANS_CO,
                                CREATED_DATE
                            FROM (
                                SELECT
                                    PASIEN_ID,
                                    EPISODE_ID,
                                    TRANS_CO,
                                    CREATED_DATE,
                                    ROW_NUMBER() OVER (
                                        PARTITION BY PASIEN_ID, EPISODE_ID
                                        ORDER BY CREATED_DATE
                                    ) RN
                                FROM WEB_CO_RESUME_RANAP
                                WHERE TRANS_CO IS NOT NULL
                                AND SHOW_ITEM <> '0'
                            )
                            WHERE RN = 1
                        )

                        SELECT  
                                A.PASIEN_ID,
                                A.EPISODE_ID,
                                A.TGL_KELUAR,

                                REPLACE(REPLACE(A.RUANGRWT_ID,'_',' '),'KBY','KBY ') AS RUANGRWT_ID,
                                A.KELAS_ID,

                                TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY')  AS TGLMASUK,
                                TO_CHAR(A.TGL_KELUAR,'DD.MM.YYYY') AS TGLKELUAR,

                                UPPER(DOK.NAMA) AS DPJP,
                                UPPER(SUBSTR(REPLACE(REPLACE(DOK.NAMA,'DR. ',''),'DR ',''),1,1)) AS INISIALDPJP,

                                RS.TRANS_CO AS TRANSCORESUME,
                                TO_CHAR(RS.CREATED_DATE,'DD.MM.YYYY HH24:MI:SS') AS CREATEDDATERESUME,

                                CASE
                                    WHEN RS.TRANS_CO IS NULL THEN
                                        SR01_HITUNG_UMURDLMHARI(A.TGL_KELUAR,TRUNC(SYSDATE))
                                    ELSE
                                        SR01_HITUNG_UMURDLMHARI(A.TGL_KELUAR,TRUNC(RS.CREATED_DATE))
                                END DURASI,

                                PS.INT_PASIEN_ID AS MRPAS,
                                PS.SEX_ID AS SEXID,
                                SR01_GET_SUFFIX(A.PASIEN_ID) AS NAMAPASIEN,

                                RKN.NAMA AS PROVIDER,
                                MSK.KETERANGAN AS CARAPULANG

                        FROM SR01_KEU_EPISODE A

                        LEFT JOIN SR01_GEN_PASIEN_MS PS
                            ON PS.PASIEN_ID = A.PASIEN_ID
                            AND PS.LOKASI_ID = '001'
                            AND PS.AKTIF = '1'

                        LEFT JOIN SR01_MED_DOKTER_MS DOK
                            ON DOK.DOKTER_ID = A.DOKTER_ID
                            AND DOK.LOKASI_ID = '001'
                            AND DOK.AKTIF = '1'

                        LEFT JOIN SR01_MED_MSKKLR_MS MSK
                            ON MSK.MSKKLR_ID = A.PULANG_ID
                            AND MSK.LOKASI_ID = '001'
                            AND MSK.AKTIF = '1'
                            AND MSK.KATEGORI_ID = 'MP'

                        LEFT JOIN SR01_KEU_REKANAN_MS RKN
                            ON RKN.REKANAN_ID = A.REKANAN_ID
                            AND RKN.LOKASI_ID = '001'
                            AND RKN.AKTIF = '1'

                        LEFT JOIN RESUME RS
                            ON RS.PASIEN_ID = A.PASIEN_ID
                            AND RS.EPISODE_ID = A.EPISODE_ID

                        WHERE A.LOKASI_ID = '001'
                        AND   A.AKTIF = '1'
                        AND   A.JENIS_EPISODE = 'I'
                        AND   A.STATUS_EPISODE = '55'

                        AND A.TGL_KELUAR >= TO_DATE('01-01-" . $periode . "','DD-MM-YYYY')
                        AND A.TGL_KELUAR <  TO_DATE('01-01-" . ($periode+1) . "','DD-MM-YYYY')

                        ORDER BY
                                RS.TRANS_CO DESC,
                                A.TGL_KELUAR ASC,
                                DOK.NAMA ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>