<?php
    class Modelauditmedik extends CI_Model{

        function periode(){
            $query =
                    "
                        SELECT 
                            TO_CHAR(dt,'FMMonth YYYY','NLS_DATE_LANGUAGE=INDONESIAN') AS PERIODE,
                            TO_CHAR(dt, 'MM.YYYY') AS PERIODE_KEY
                        FROM (
                            SELECT ADD_MONTHS(DATE '2015-01-01', LEVEL-1) dt
                            FROM DUAL
                            CONNECT BY ADD_MONTHS(DATE '2015-01-01', LEVEL-1) < TRUNC(SYSDATE, 'MM')
                        )
                        ORDER BY dt DESC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        
        function dataauditmedik($periode){
            $query =
                    "
                        WITH
                        CODE AS (
                            SELECT *
                            FROM (
                                SELECT PASIEN_ID,
                                    EPISODE_ID,
                                    CODE_ID,
                                    TO_CHAR(CREATED_DATE,'DD.MM.YYYY HH24:MI:SS')CREATED_DATE,
                                    ROW_NUMBER() OVER(PARTITION BY PASIEN_ID, EPISODE_ID ORDER BY CREATED_DATE) RN
                                FROM SR01_CODE_STROOKE_HD
                                WHERE LOKASI_ID='001'
                                AND AKTIF='1'
                            )
                            WHERE RN=1
                        ),

                        IGD AS (
                            SELECT PASIEN_ID, EPISODE_ID, TO_CHAR(CREATED_DATE,'DD.MM.YYYY HH24:MI:SS') REGISTRASIIGD
                            FROM SR01_PASIEN_IGD
                            WHERE LOKASI_ID='001'
                        ),

                        RAD AS (
                            SELECT PASIEN_ID,
                                EPISODE_ID,
                                COUNT(*) JMLORDERCT,
                                MIN(TRANS_CO) KEEP(DENSE_RANK FIRST ORDER BY CREATED_DATE) TRANSCO_FIRST,
                                MIN(CREATED_DATE) KEEP(DENSE_RANK FIRST ORDER BY CREATED_DATE) ORDER_FIRST,
                                MAX(TRANS_CO) KEEP(DENSE_RANK LAST ORDER BY CREATED_DATE) TRANSCO_LAST,
                                MAX(CREATED_DATE) KEEP(DENSE_RANK LAST ORDER BY CREATED_DATE) ORDER_LAST
                            FROM WEB_CO_RAD_DT
                            WHERE SHOW_ITEM='1'
                            AND TEST_ID='RAD074'
                            GROUP BY PASIEN_ID, EPISODE_ID
                        ),

                        WORKLIST AS (
                            SELECT EPISODE_ID, TRANS_CO, TRANS_RAD
                            FROM SR01_WORKLIST_RAD_DT
                            WHERE LOKASI_ID='001'
                            AND AKTIF='1'
                            AND TEST_ID='RAD074'
                        ),

                        RIS AS (
                            SELECT NO_REGISTER, NO_RONTGEN, RADIOGRAFER_DATETIME_START, RADIOLOG_DATETIME_END
                            FROM RAD_MANAGER.RIS_OUT
                        )

                        SELECT
                            A.PASIEN_ID,
                            A.EPISODE_ID,
                            TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY')TGLMASUK,
                            
                            C.CODE_ID AS CODEID,
                            C.CREATED_DATE AS CODECREATEDATE,
                            
                            I.REGISTRASIIGD,
                            
                            R.JMLORDERCT,
                            R.TRANSCO_FIRST,
                            TO_CHAR(R.ORDER_FIRST,'DD.MM.YYYY HH24:MI:SS')ORDERFIRST,
                            R.TRANSCO_LAST,
                            TO_CHAR(R.ORDER_LAST,'DD.MM.YYYY HH24:MI:SS')ORDERLAST,
                            
                            WF.TRANS_RAD AS TRANSRADFIRST,
                            WL.TRANS_RAD AS TRANSRADLAST,
                            
                            TO_CHAR(RF.RADIOGRAFER_DATETIME_START,'DD.MM.YYYY HH24:MI:SS') AS RADIOGRAFERSTARTFIRST,
                            TO_CHAR(RL.RADIOGRAFER_DATETIME_START,'DD.MM.YYYY HH24:MI:SS') AS RADIOGRAFERSTARTLAST,
                            TO_CHAR(RF.RADIOLOG_DATETIME_END,'DD.MM.YYYY HH24:MI:SS') AS RADIOLOGTFIRST,
                            TO_CHAR(RL.RADIOLOG_DATETIME_END,'DD.MM.YYYY HH24:MI:SS') AS RADIOLOGLAST,
                            
                            GETPIDINT(A.PASIEN_ID)MRPAS,
                            SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                            B.SEX_ID,
                            B.TEMPAT_LAHIR_TXT,
                            TO_CHAR(B.TGL_LAHIR,'DD.MM.YYYY')TGLLAHIR,
                            SR01_HITUNG_UMUR(B.TGL_LAHIR, A.TGL_MASUK)UMUR
                            
                            
                        FROM SR01_KEU_EPISODE A
                        LEFT JOIN SR01_GEN_PASIEN_MS B
                        ON B.PASIEN_ID=A.PASIEN_ID
                        LEFT JOIN CODE C
                        ON C.PASIEN_ID=A.PASIEN_ID
                        AND C.EPISODE_ID=A.EPISODE_ID
                        LEFT JOIN IGD I
                        ON I.PASIEN_ID=A.PASIEN_ID
                        AND I.EPISODE_ID=A.EPISODE_ID
                        LEFT JOIN RAD R
                        ON R.PASIEN_ID=A.PASIEN_ID
                        AND R.EPISODE_ID=A.EPISODE_ID
                        LEFT JOIN WORKLIST WF
                        ON WF.EPISODE_ID=A.EPISODE_ID
                        AND WF.TRANS_CO=R.TRANSCO_FIRST
                        LEFT JOIN WORKLIST WL
                        ON WL.EPISODE_ID=A.EPISODE_ID
                        AND WL.TRANS_CO=R.TRANSCO_LAST
                        LEFT JOIN RIS RF
                        ON RF.NO_REGISTER=A.EPISODE_ID
                        AND RF.NO_RONTGEN=WF.TRANS_RAD
                        LEFT JOIN RIS RL
                        ON RL.NO_REGISTER=A.EPISODE_ID
                        AND RL.NO_RONTGEN=WL.TRANS_RAD
                        
                        WHERE A.LOKASI_ID='001'
                        AND A.AKTIF='1'
                        AND A.STATUS_EPISODE<>'99'
                        AND C.CODE_ID IS NOT NULL
                        AND TO_CHAR(A.TGL_MASUK,'MM.YYYY') = '".$periode."'
                        ORDER BY A.TGL_MASUK ASC
                ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>
