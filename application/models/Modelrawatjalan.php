<?php
    class Modelrawatjalan extends CI_Model{

        function listpasienbooking($templateid){
            $query =
                    "
                        SELECT A.PASIEN_ID, EPISODE_ID, BOOKING_ID, URUT, JAM_MULAI, JAM_SELESAI, TO_CHAR(A.TGL_MASUK,'FMDay, DD FMMonth YYYY','NLS_DATE_LANGUAGE=INDONESIAN') TGL_MASUK, CREATED_DATE, REKANAN_ID,
                            SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                            (SELECT UPPER(NAMA) FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID=A.DOKTER_ID)NAMADOKTER,
                            '+6281288646630' AS NOMORHP
                        FROM WEB_CO_REGISTRASI_ONLINE_HD A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.REKANAN_ID='EXECU0000000001'
                        AND   A.EPISODE_ID NOT IN (SELECT EPISODE_ID FROM SR01_WHATSAPP_BROADCAST_HD WHERE AKTIF='1' AND TEMPLATE_ID='".$templateid."' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        AND   TRUNC(A.CREATED_DATE)=TRUNC(SYSDATE)
                        ORDER BY CREATED_DATE ASC
                        FETCH FIRST 1 ROWS ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function listreminderbooking($templateid){
            $query =
                    "
                        SELECT A.PASIEN_ID, EPISODE_ID, BOOKING_ID, URUT, JAM_MULAI, JAM_SELESAI, TO_CHAR(A.TGL_MASUK,'FMDay, DD FMMonth YYYY','NLS_DATE_LANGUAGE=INDONESIAN') TGL_MASUK,
                            SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                            (SELECT UPPER(NAMA) FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID=A.DOKTER_ID)NAMADOKTER,
                            '+6281288646630' AS NOMORHP
                        FROM WEB_CO_REGISTRASI_ONLINE_HD A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.REKANAN_ID='EXECU0000000001'
                        AND   A.EPISODE_ID NOT IN (SELECT EPISODE_ID FROM SR01_WHATSAPP_BROADCAST_HD WHERE AKTIF='1' AND TEMPLATE_ID='".$templateid."' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        AND   A.TGL_MASUK >= TRUNC(SYSDATE) + 1
                        AND   A.TGL_MASUK <  TRUNC(SYSDATE) + 2
                        AND   TRUNC(A.TGL_MASUK) > TRUNC(CREATED_DATE)
                        ORDER BY CREATED_DATE ASC
                        FETCH FIRST 1 ROWS ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function listpasienbatalbooking($templateid){
            $query =
                    "
                        SELECT A.PASIEN_ID, EPISODE_ID, BOOKING_ID, URUT, JAM_MULAI, JAM_SELESAI, TO_CHAR(A.TGL_MASUK,'FMDay, DD FMMonth YYYY','NLS_DATE_LANGUAGE=INDONESIAN') TGL_MASUK, CREATED_DATE,
                            SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                            (SELECT UPPER(NAMA) FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID=A.DOKTER_ID)NAMADOKTER,
                            '+6281288646630' AS NOMORHP
                        FROM WEB_CO_REGISTRASI_ONLINE_HD A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='0'
                        AND   A.REKANAN_ID='EXECU0000000001'
                        AND   A.EPISODE_ID NOT IN (SELECT EPISODE_ID FROM SR01_WHATSAPP_BROADCAST_HD WHERE AKTIF='1' AND TEMPLATE_ID='".$templateid."' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        AND   A.TGL_MASUK >= TRUNC(SYSDATE) + 1
                        AND   A.TGL_MASUK <  TRUNC(SYSDATE) + 2
                        ORDER BY CREATED_DATE ASC
                        FETCH FIRST 1 ROWS ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }        

        function listpasienadaobat($templateid){
            $query =
                    "
                        SELECT A.PASIEN_ID, EPISODE_ID,
                            SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                            (SELECT UPPER(NAMA) FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID=A.DOKTER_ID)NAMADOKTER,
                            '+6281288646630' AS NOMORHP

                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.JENIS_EPISODE='O'
                        AND   A.STATUS_EPISODE<>'99'
                        AND   A.REKANAN_ID='EXECU0000000001'
                        AND   A.POLI_ID NOT IN ('UGD01','APS')
                        AND   TRUNC(A.TGL_MASUK)=TRUNC(SYSDATE)
                        AND   A.EPISODE_ID NOT IN (SELECT EPISODE_ID FROM SR01_WHATSAPP_BROADCAST_HD WHERE AKTIF='1' AND TEMPLATE_NAME='".$templateid."' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        AND   A.EPISODE_ID IN (SELECT EPISODE_ID FROM WEB_CO_SELESAI_PERIKSA WHERE SHOW_ITEM='1' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        AND   A.EPISODE_ID IN (SELECT EPISODE_ID FROM WEB_CO_RESEP_DT WHERE SHOW_ITEM='1' AND STATUS_VER='0' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        FETCH FIRST 1 ROWS ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function googlereview($templateid){
            $query =
                    "
                        SELECT A.PASIEN_ID, EPISODE_ID,
                            SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                            '+6281288646630' AS NOMORHP

                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.JENIS_EPISODE='O'
                        AND   A.STATUS_EPISODE<>'99'
                        AND   A.REKANAN_ID='BPJS'
                        AND   A.POLI_ID NOT IN ('UGD01','APS')
                        AND   TRUNC(A.TGL_MASUK)=TRUNC(SYSDATE)
                        AND   A.EPISODE_ID NOT IN (SELECT EPISODE_ID FROM SR01_WHATSAPP_BROADCAST_HD WHERE AKTIF='1' AND TEMPLATE_ID='".$templateid."' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        AND   A.EPISODE_ID IN (SELECT EPISODE_ID FROM WEB_CO_SELESAI_PERIKSA WHERE SHOW_ITEM='1' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        FETCH FIRST 1 ROWS ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>