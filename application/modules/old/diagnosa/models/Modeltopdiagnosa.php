<?php
    class Modeltopdiagnosa extends CI_Model{

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

        function datarjgeriatri($periode){
            $query = "
                        SELECT Y.*, DIAGFINAL||' '||DIAGDESCFINAL KETERANGAN
                        FROM(
                            SELECT X.*,
                                DECODE(X.ICD10PRIMARY,'Z09.8',(SELECT ICD10_ID FROM SR01_RM_RESUME_ICD10 WHERE LOKASI_ID='001' AND AKTIF='1' AND JENIS='2' AND URUT='1' AND JNS_R='F' AND EPISODE_ID=X.EPISODE_ID),ICD10PRIMARY)DIAGFINAL,
                                DECODE(X.ICD10DESCPRIMARY,'FOLLOW-UP EXAM AFTER OTHER TREATMENT FOR OTHER CONDITIONS',(SELECT DIAGNOSA FROM SR01_RM_RESUME_ICD10 WHERE LOKASI_ID='001' AND AKTIF='1' AND JENIS='2' AND URUT='1' AND JNS_R='F' AND EPISODE_ID=X.EPISODE_ID),ICD10DESCPRIMARY)DIAGDESCFINAL
                            FROM(
                                SELECT A.PASIEN_ID, EPISODE_ID,
                                    (SELECT DISTINCT ICD10_ID FROM SR01_RM_RESUME_ICD10 WHERE LOKASI_ID='001' AND AKTIF='1' AND JENIS='1' AND JNS_R='F' AND EPISODE_ID=A.EPISODE_ID ORDER BY CREATED_DATE DESC FETCH FIRST 1 ROW ONLY)ICD10PRIMARY,
                                    (SELECT DISTINCT DIAGNOSA FROM SR01_RM_RESUME_ICD10 WHERE LOKASI_ID='001' AND AKTIF='1' AND JENIS='1' AND JNS_R='F' AND EPISODE_ID=A.EPISODE_ID ORDER BY CREATED_DATE DESC FETCH FIRST 1 ROW ONLY)ICD10DESCPRIMARY
                                FROM SR01_KEU_EPISODE A
                                WHERE A.LOKASI_ID='001'
                                AND   A.AKTIF='1'
                                AND   A.JENIS_EPISODE='O'
                                AND   A.STATUS_EPISODE='55'
                                AND   A.POLI_ID<>'UGD01'
                                AND   TO_CHAR(A.TGL_MASUK,'YYYY')='".$periode."'
                                AND   EXISTS (SELECT 1 FROM SR01_GEN_PASIEN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND GERIATRI='Y' AND PASIEN_ID=A.PASIEN_ID)
                                AND   EXISTS (SELECT 1 FROM SR01_RM_RESUME_ICD10 WHERE LOKASI_ID='001' AND AKTIF='1' AND ICD10_ID IS NOT NULL AND EPISODE_ID=A.EPISODE_ID)
                            )X
                        )Y
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datarj($periode){
            $query = "
                        SELECT Y.*, DIAGFINAL||' '||DIAGDESCFINAL KETERANGAN
                        FROM(
                            SELECT X.*,
                                DECODE(X.ICD10PRIMARY,'Z09.8',(SELECT ICD10_ID FROM SR01_RM_RESUME_ICD10 WHERE LOKASI_ID='001' AND AKTIF='1' AND JENIS='2' AND URUT='1' AND JNS_R='F' AND EPISODE_ID=X.EPISODE_ID),ICD10PRIMARY)DIAGFINAL,
                                DECODE(X.ICD10DESCPRIMARY,'FOLLOW-UP EXAM AFTER OTHER TREATMENT FOR OTHER CONDITIONS',(SELECT DIAGNOSA FROM SR01_RM_RESUME_ICD10 WHERE LOKASI_ID='001' AND AKTIF='1' AND JENIS='2' AND URUT='1' AND JNS_R='F' AND EPISODE_ID=X.EPISODE_ID),ICD10DESCPRIMARY)DIAGDESCFINAL
                            FROM(
                                SELECT A.PASIEN_ID, EPISODE_ID,
                                    (SELECT DISTINCT ICD10_ID FROM SR01_RM_RESUME_ICD10 WHERE LOKASI_ID='001' AND AKTIF='1' AND JENIS='1' AND JNS_R='F' AND EPISODE_ID=A.EPISODE_ID ORDER BY CREATED_DATE DESC FETCH FIRST 1 ROW ONLY)ICD10PRIMARY,
                                    (SELECT DISTINCT DIAGNOSA FROM SR01_RM_RESUME_ICD10 WHERE LOKASI_ID='001' AND AKTIF='1' AND JENIS='1' AND JNS_R='F' AND EPISODE_ID=A.EPISODE_ID ORDER BY CREATED_DATE DESC FETCH FIRST 1 ROW ONLY)ICD10DESCPRIMARY
                                FROM SR01_KEU_EPISODE A
                                WHERE A.LOKASI_ID='001'
                                AND   A.AKTIF='1'
                                AND   A.JENIS_EPISODE='O'
                                AND   A.STATUS_EPISODE='55'
                                AND   A.POLI_ID<>'UGD01'
                                AND   TO_CHAR(A.TGL_MASUK,'YYYY')='".$periode."'
                                AND   EXISTS (SELECT 1 FROM SR01_RM_RESUME_ICD10 WHERE LOKASI_ID='001' AND AKTIF='1' AND ICD10_ID IS NOT NULL AND EPISODE_ID=A.EPISODE_ID)
                            )X
                        )Y
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }



    }
?>