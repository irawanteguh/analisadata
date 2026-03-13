<?php
    class Modelwaktutunggu extends CI_Model{

        function waktutungguranap($startdate,$endate){
            $query =
                    "
                        WITH TRANSIT AS (
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
                                AND T.RUANG_ID LIKE 'TRANSIT%'
                            )
                            WHERE RN = 1
                        ),

                        RANAP AS (
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
                        )

                        SELECT 
                            A.PASIEN_ID,
                            A.EPISODE_ID,
                            TO_CHAR(A.CREATED_DATE,'DD.MM.YYYY HH24:MI:SS')IGD_MASUK_JAM,
                            A.CREATED_BY,
                            U.NAMA            AS REGISIGDBY,
                            P.INT_PASIEN_ID AS MRPASIEN,
                            SR01_GET_SUFFIX(A.PASIEN_ID) NAMAPASIEN,

                            TR.TRANS_ID        AS TRANSIDTRANSIT,
                            TR.RUANG_ID        AS RUANGTRANSIT_MASUK,
                            TR.BED_ID          AS BEDTRANSIT_MASUK,
                            TO_CHAR(TR.CREATED_DATE,'DD.MM.YYYY HH24:MI:SS')    AS RUANGTRANSIT_MASUK_JAM,
                            TR.CREATED_BY      AS RUANGTRANSIT_CREATEDBY,
                            U1.NAMA            AS REGISTRANSITBY,

                            RN.RUANG_ID        AS RUANG_MASUK,
                            RN.BED_ID          AS BEDRUANG_MASUK,
                            TO_CHAR(RN.CREATED_DATE,'DD.MM.YYYY HH24:MI:SS')     AS RUANG_MASUK_JAM,
                            RN.CREATED_BY      AS RUANG_MASUK_CREATEDBY,
                            U2.NAMA            AS REGISRANAPTBY,

                            TF.TRANS_ID        AS TRANSIDTRANSFERRUANG,
                            TF.PINDAH_KE       AS TRANSFERRUANG,
                            TO_CHAR(TF.ACC_TGL,'DD.MM.YYYY HH24:MI:SS')         AS TRANSFERRUANG_JAM,
                            TO_CHAR(TF.CREATED_DATE,'DD.MM.YYYY HH24:MI:SS')    AS CREATEDDATETRANSFER,
                            TF.ACC_OLEH        AS TRANSFERRUANG_CREATEDBY,
                            U3.NAMA            AS TRANSFERRUANG_BY,
                            U5.NAMA            AS TRANSFER_BY,
                            
                            SP.TRANS_ID        AS TRANSIDSPRI,
                            SP.POLI_ID         AS POLIASAL,
                            TO_CHAR(SP.CREATED_DATE,'DD.MM.YYYY HH24:MI:SS')    AS SPRITGLJAM,
                            U4.NAMA            AS SPRI_BY
                            
                        FROM SR01_KEU_EPISODE A

                        LEFT JOIN TRANSIT TR 
                            ON TR.PASIEN_ID = A.PASIEN_ID
                            AND TR.EPISODE_ID = A.EPISODE_ID

                        LEFT JOIN RANAP RN 
                            ON RN.PASIEN_ID = A.PASIEN_ID
                            AND RN.EPISODE_ID = A.EPISODE_ID

                        LEFT JOIN TRANSFER TF
                            ON TF.PASIEN_ID = A.PASIEN_ID
                            AND TF.EPISODE_ID = A.EPISODE_ID
                        
                        LEFT JOIN SPRI SP
                            ON SP.PASIEN_ID = A.PASIEN_ID
                            AND SP.EPISODE_ID = A.EPISODE_ID

                        LEFT JOIN SR01_GEN_PASIEN_MS P
                            ON P.PASIEN_ID = A.PASIEN_ID
                            AND P.LOKASI_ID = '001'
                            AND P.AKTIF = '1'

                        -- JOIN USER DATA (INI PENGGANTI SUBQUERY)
                        LEFT JOIN SR01_GEN_USER_DATA U
                            ON U.LOKASI_ID = '001'
                            AND U.AKTIF = '1'
                            AND 'SIRS01_'||UPPER(U.USER_ID) = A.CREATED_BY
                            
                        LEFT JOIN SR01_GEN_USER_DATA U1
                            ON U1.LOKASI_ID = '001'
                            AND U1.AKTIF = '1'
                            AND 'SIRS01_'||UPPER(U1.USER_ID) = TR.CREATED_BY

                        LEFT JOIN SR01_GEN_USER_DATA U2
                            ON U2.LOKASI_ID = '001'
                            AND U2.AKTIF = '1'
                            AND 'SIRS01_'||UPPER(U2.USER_ID) = RN.CREATED_BY

                        LEFT JOIN SR01_GEN_USER_DATA U3
                            ON U3.LOKASI_ID = '001'
                            AND U3.AKTIF = '1'
                            AND 'SIRS01_'||UPPER(U3.USER_ID) = TF.ACC_OLEH
                        
                        LEFT JOIN SR01_MED_DOKTER_MS U4
                            ON U4.LOKASI_ID = '001'
                            AND U4.AKTIF = '1'
                            AND UPPER(U4.DOKTER_ID) = SP.CREATED_BY

                        LEFT JOIN SR01_GEN_USER_DATA U5
                            ON U5.LOKASI_ID = '001'
                            AND U5.AKTIF = '1'
                            AND 'SIRS01_'||UPPER(U5.USER_ID) = TF.CREATED_BY

                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'I'
                        AND A.STATUS_EPISODE <> '99'
                        AND TRUNC(A.TGL_MASUK) BETWEEN TRUNC(TO_DATE('".$startdate."','YYYY-MM-DD')) AND TRUNC(TO_DATE('".$endate."','YYYY-MM-DD'))

                        ORDER BY A.CREATED_DATE DESC

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>