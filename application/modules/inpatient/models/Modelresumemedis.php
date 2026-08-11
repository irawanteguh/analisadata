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
                        WITH RESUMEMEDIS AS (
                            SELECT
                                EPISODE_ID,
                                TRANS_ID TRANSCO,
                                CREATED_DATE
                            FROM (
                                SELECT
                                    EPISODE_ID,
                                    TRANS_ID,
                                    CREATED_DATE,
                                    ROW_NUMBER() OVER (
                                        PARTITION BY EPISODE_ID
                                        ORDER BY CREATED_DATE ASC
                                    ) RN
                                FROM SR01_RESUME_MEDIS
                                WHERE AKTIF <> '0'
                            )
                            WHERE RN = 1
                        )
                                                    
                        SELECT A.PASIEN_ID, A.EPISODE_ID, A.TGL_KELUAR, A.KELAS_ID,
                            TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY')  AS TGLMASUK,
                            TO_CHAR(A.TGL_KELUAR,'DD.MM.YYYY') AS TGLKELUAR,
                            REPLACE(REPLACE(A.RUANGRWT_ID,'_',' '),'KBY','KBY ') AS RUANGRWT_ID,
                            
                            GETPIDINT(A.PASIEN_ID)MRPAS,
                            SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                            PS.SEX_ID AS SEXID,
                            
                            (SELECT UPPER(NAMA) FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID=A.DOKTER_ID)DPJP,
                            
                            RS.TRANSCO AS TRANSCORESUME,
                            TO_CHAR(RS.CREATED_DATE,'DD.MM.YYYY HH24:MI:SS') AS CREATEDDATERESUME,
                            
                            SR01_HITUNG_UMURDLMHARI(TRUNC(A.TGL_KELUAR),TRUNC(RS.CREATED_DATE)) AS DURASI,
                            
                            CASE
                                WHEN RS.CREATED_DATE IS NULL THEN
                                CASE
                                    WHEN SR01_HITUNG_UMURDLMHARI(TRUNC(A.TGL_KELUAR),TRUNC(SYSDATE)) > 2 THEN
                                    '>48'
                                    ELSE
                                    '<48'
                                END
                                ELSE
                                'N'
                            END PENDINGRESUMELEBIH48,

                            CASE
                                WHEN RS.CREATED_DATE IS NOT NULL THEN
                                CASE
                                    WHEN SR01_HITUNG_UMURDLMHARI(TRUNC(A.TGL_KELUAR),TRUNC(RS.CREATED_DATE)) > 2 THEN
                                    0
                                    ELSE
                                    1
                                END
                                ELSE
                                0
                            END STATUSKURANG,
                            
                            CASE
                                WHEN RS.CREATED_DATE IS NOT NULL THEN
                                CASE
                                    WHEN SR01_HITUNG_UMURDLMHARI(TRUNC(A.TGL_KELUAR),TRUNC(RS.CREATED_DATE)) > 2 THEN
                                    1
                                    ELSE
                                    0
                                END
                                ELSE
                                0
                            END STATUSLEBIH,

                            CASE
                                WHEN RS.CREATED_DATE IS NULL THEN
                                1
                                ELSE
                                0
                            END STATUSBELUMBUAT,
                            
                            RKN.NAMA AS PROVIDER,
                            MSK.KETERANGAN AS CARAPULANG
                            
                        FROM SR01_KEU_EPISODE A

                        LEFT JOIN SR01_GEN_PASIEN_MS PS
                            ON PS.PASIEN_ID = A.PASIEN_ID
                            AND PS.LOKASI_ID = '001'
                            AND PS.AKTIF = '1'
                                    
                        LEFT JOIN SR01_KEU_REKANAN_MS RKN
                            ON RKN.REKANAN_ID = A.REKANAN_ID
                            AND RKN.LOKASI_ID = '001'
                            AND RKN.AKTIF = '1'
                        
                        LEFT JOIN SR01_MED_MSKKLR_MS MSK
                            ON MSK.MSKKLR_ID = A.PULANG_ID
                            AND MSK.LOKASI_ID = '001'
                            AND MSK.AKTIF = '1'
                            AND MSK.KATEGORI_ID = 'MP'
                                                                                                        
                        LEFT JOIN RESUMEMEDIS RS
                            ON RS.EPISODE_ID = A.EPISODE_ID
                            
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'I'
                        AND A.STATUS_EPISODE = '55'
                        AND   TO_CHAR(A.TGL_KELUAR,'YYYY')='".$periode."'

                        ORDER BY A.TGL_KELUAR DESC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>