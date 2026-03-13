<?php
    class Modelreadmisi extends CI_Model{

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

        function datapasienreadmisi($periode){
            $query =
                    "
                        SELECT Y.*,
                            (SELECT INT_PASIEN_ID FROM SR01_GEN_PASIEN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND PASIEN_ID=Y.PASIEN_ID)MRPASIEN,
                            SR01_GET_SUFFIX(Y.PASIEN_ID)NAMAPASIEN,
                            (SELECT NAMA FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID=Y.DOKTER_ID)NAMADOKTER,
                            (SELECT NAMA FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID=Y.DOKTERIDLAST)NAMADOKTERLAST
                        FROM(
                            SELECT X.*,
                                SR01_HITUNG_UMURDLMHARI(TO_DATE(TGLKELUARLAST,'DD.MM.YYYY'),TO_DATE(TGLMASUK,'DD.MM.YYYY'))JARAKWAKTU
                            FROM(
                                SELECT A.PASIEN_ID, A.EPISODE_ID, A.PREV_EPISODE_ID, TO_CHAR(TGL_MASUK,'DD.MM.YYYY')TGLMASUK, TO_CHAR(TGL_MASUK,'YYYY-MM-DD')PERIODE, TO_CHAR(TGL_KELUAR,'DD.MM.YYYY')TGLKELUAR, A.DOKTER_ID,
                                    (SELECT TO_CHAR(TGL_MASUK,'DD.MM.YYYY') FROM SR01_KEU_EPISODE WHERE LOKASI_ID='001' AND AKTIF='1' AND JENIS_EPISODE='I' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.PREV_EPISODE_ID)TGLMASUKLAST,
                                    (SELECT TO_CHAR(TGL_KELUAR,'DD.MM.YYYY') FROM SR01_KEU_EPISODE WHERE LOKASI_ID='001' AND AKTIF='1' AND JENIS_EPISODE='I' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.PREV_EPISODE_ID)TGLKELUARLAST,
                                    (SELECT DOKTER_ID FROM SR01_KEU_EPISODE WHERE LOKASI_ID='001' AND AKTIF='1' AND JENIS_EPISODE='I' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.PREV_EPISODE_ID)DOKTERIDLAST
                                FROM SR01_KEU_EPISODE A
                                WHERE A.LOKASI_ID='001'
                                AND   A.AKTIF='1'
                                AND   A.JENIS_EPISODE='I'
                                AND   A.STATUS_EPISODE<>'99'
                                AND   A.PREV_EPISODE_ID IS NOT NULL
                                AND   TO_CHAR(A.TGL_MASUK,'YYYY')='".$periode."'
                                ORDER BY TGL_MASUK DESC
                            )X
                        )Y
                        WHERE Y.JARAKWAKTU <= 30
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
    }
?>