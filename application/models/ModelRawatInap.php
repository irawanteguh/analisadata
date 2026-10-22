<?php
    class ModelRawatInap extends CI_Model{

        function listpasienrencanapulang(){
            $query =
                    "
                        SELECT A.RUANGRWT_ID,
                            GETPIDINT(A.PASIEN_ID)MRPASIEN,
                            SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                            (SELECT RUANG_ID FROM SR01_MED_RUANG_PRWT WHERE PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)RUANGID,
                            (SELECT NAMA_UNIT FROM SR01_GEN_UNIT_MS WHERE UNIT_ID=(SELECT UNIT_ID FROM SR01_MED_RUANG_MS WHERE RUANG_ID=(SELECT RUANG_ID FROM SR01_MED_RUANG_PRWT WHERE PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)))NAMARUANG,
                            (SELECT PERLU_VISIT FROM SR01_PASIEN_RANAP WHERE PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)TUTD 
                                        
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.JENIS_EPISODE='I'
                        AND   A.STATUS_EPISODE='00'
                        AND   A.RUANGRWT_ID NOT LIKE 'CVC%'
                        AND   A.RUANGRWT_ID NOT LIKE 'IC%'
                        AND   A.RUANGRWT_ID NOT LIKE 'KBY%'
                        AND   A.RUANGRWT_ID NOT LIKE 'PER%'
                        AND   A.EPISODE_ID=(SELECT EPISODE_ID FROM SR01_MED_DISCHARGE_TL WHERE TRUNC(TGL_DISCHARGE)<=TRUNC(SYSDATE) AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        AND   A.EPISODE_ID=(SELECT EPISODE_ID FROM SR01_MED_RUANG_PRWT WHERE PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        ORDER BY TUTD ASC, NAMARUANG ASC, RUANGID ASC, NAMAPASIEN ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function listpasientransit(){
            $query =
                    "
                        SELECT A.PASIEN_ID, EPISODE_ID, KELAS_ID,
                            GETPIDINT(A.PASIEN_ID)MRPASIEN,
                            SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                            (TRIM((SELECT DIAGNOSA FROM WEB_CO_MINTA_RANAP WHERE AKTIF='1' AND ASAL_POLI='A002' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID ORDER BY CREATED_DATE DESC FETCH FIRST 1 ROW ONLY))) DIAGNOSA,
                            (SELECT NAMA FROM SR01_MED_DOKTER_MS WHERE DOKTER_ID=(SELECT DPJP FROM WEB_CO_MINTA_RANAP WHERE AKTIF='1' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID ORDER BY CREATED_DATE DESC FETCH FIRST 1 ROW ONLY))DPJP,
                            (SELECT TO_CHAR(TGL_MASUK,'DD.MM.YYYY HH24:MI:SS')  FROM SR01_KEU_TRANSKMR_IT WHERE AKTIF='1' AND RUANG_ID = 'TRANSIT' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)TGLMSASUKTRANSIT,
                            (SELECT FLOOR(SYSDATE - TGL_MASUK) || ' Hari ' || FLOOR(MOD((SYSDATE - TGL_MASUK) * 24, 24)) || ' Jam ' || FLOOR(MOD((SYSDATE - TGL_MASUK) * 1440, 60)) || ' Menit' FROM SR01_KEU_TRANSKMR_IT WHERE AKTIF = '1' AND RUANG_ID = 'TRANSIT' AND PASIEN_ID = A.PASIEN_ID AND EPISODE_ID = A.EPISODE_ID) LAMATRANSIT
                                                                
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.JENIS_EPISODE='I'
                        AND   A.STATUS_EPISODE='00'
                        AND   A.RUANGRWT_ID LIKE 'TRANSIT%'
                        AND   A.EPISODE_ID=(SELECT EPISODE_ID FROM SR01_MED_RUANG_PRWT WHERE PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        AND   A.EPISODE_ID=(SELECT EPISODE_ID FROM WEB_CO_MINTA_RANAP WHERE AKTIF='1' AND ASAL_POLI='A002' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID ORDER BY CREATED_DATE DESC FETCH FIRST 1 ROW ONLY)
                        ORDER BY TO_DATE(TGLMSASUKTRANSIT,'DD.MM.YYYY HH24:MI:SS') DESC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>