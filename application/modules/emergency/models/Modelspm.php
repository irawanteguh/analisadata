<?php
    class Modelspm extends CI_Model{
        
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

        function spmigd($periode){
            $query =
                    "
                        WITH RANAP AS (
                            SELECT *
                            FROM (
                                SELECT 
                                    T.*,
                                    ROW_NUMBER() OVER (
                                        PARTITION BY PASIEN_ID, EPISODE_ID
                                        ORDER BY CREATED_DATE
                                    ) RN
                                FROM SR01_KEU_TRANSKMR_IT T
                                WHERE T.AKTIF = '1'
                                AND T.RUANG_ID NOT LIKE 'TRANSIT%'
                            )
                            WHERE RN = 1
                        ),

                        TRANSFER AS (
                            SELECT *
                            FROM (
                                SELECT 
                                    T.*,
                                    ROW_NUMBER() OVER (
                                        PARTITION BY PASIEN_ID, EPISODE_ID
                                        ORDER BY CREATED_DATE
                                    ) RN
                                FROM SR01_MED_TRNSF_RUANG T
                                WHERE T.AKTIF = '1'
                            )
                            WHERE RN = 1
                        ),

                        SPRI AS (
                            SELECT *
                            FROM (
                                SELECT 
                                    T.*,
                                    ROW_NUMBER() OVER (
                                        PARTITION BY PASIEN_ID, EPISODE_ID
                                        ORDER BY CREATED_DATE
                                    ) RN
                                FROM WEB_CO_MINTA_RANAP T
                                WHERE T.AKTIF = '1'
                            )
                            WHERE RN = 1
                        ),

                        DATA AS (
                            SELECT 
                                A.PASIEN_ID,
                                A.EPISODE_ID,
                                A.TGL_MASUK,
                                A.CREATED_DATE      AS MASUK_IGD,
                                SP.CREATED_DATE     AS BUAT_SPRI,
                                RN.CREATED_DATE     AS MASUK_RANAP,
                                TF.CREATED_DATE     AS BUAT_TRANSFER
                            FROM SR01_KEU_EPISODE A

                            LEFT JOIN SPRI SP
                                ON SP.PASIEN_ID = A.PASIEN_ID
                                AND SP.EPISODE_ID = A.EPISODE_ID

                            LEFT JOIN RANAP RN
                                ON RN.PASIEN_ID = A.PASIEN_ID
                                AND RN.EPISODE_ID = A.EPISODE_ID

                            LEFT JOIN TRANSFER TF
                                ON TF.PASIEN_ID = A.PASIEN_ID
                                AND TF.EPISODE_ID = A.EPISODE_ID

                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'I'
                            AND A.STATUS_EPISODE <> '99'
                            AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                        )

                        SELECT
                            TO_CHAR(TGL_MASUK,'YYYY-MM') AS PERIODE,

                            COUNT(*) AS TOTAL_KASUS,

                            /* ========================= */
                            /* IGD → SPRI (MENIT)        */
                            /* ========================= */

                            ROUND(
                                AVG(
                                    CASE 
                                        WHEN MASUK_IGD IS NOT NULL
                                        AND BUAT_SPRI IS NOT NULL
                                        AND BUAT_SPRI >= MASUK_IGD
                                        THEN (BUAT_SPRI - MASUK_IGD) * 24 * 60
                                    END
                                ), 2
                            ) AS RATA_MENIT_IGD_SPRI,

                            /* ========================= */
                            /* RANAP → TRANSFER (MENIT) */
                            /* ========================= */

                            ROUND(
                                AVG(
                                    CASE 
                                        WHEN MASUK_RANAP IS NOT NULL
                                        AND BUAT_TRANSFER IS NOT NULL
                                        AND BUAT_TRANSFER >= MASUK_RANAP
                                        THEN (BUAT_TRANSFER - MASUK_RANAP) * 24 * 60
                                    END
                                ), 2
                            ) AS RATA_MENIT_RANAP_TRANSFER

                        FROM DATA

                        GROUP BY TO_CHAR(TGL_MASUK,'YYYY-MM')

                        ORDER BY PERIODE

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }


    }
?>