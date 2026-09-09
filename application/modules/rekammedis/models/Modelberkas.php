<?php
    class Modelberkas extends CI_Model{

        function datakunjungan($startdate,$endate){
            $query =
                    "
                        SELECT X.*,
                            SR01_HITUNG_UMURDLMHARI(TO_DATE(TGLKELUAR,'DD.MM.YYYY'), TO_DATE(TGLKEMBALI,'DD.MM.YYYY'))SELISIHHARI
                        FROM(
                            SELECT A.PASIEN_ID, EPISODE_ID, RUANGRWT_ID, TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY')TGLMASUK, TO_CHAR(A.TGL_KELUAR,'DD.MM.YYYY')TGLKELUAR, 
                                (SELECT INT_PASIEN_ID FROM SR01_GEN_PASIEN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND PASIEN_ID=A.PASIEN_ID)MRPASIEN,
                                SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                                (SELECT NAMA FROM SR01_KEU_REKANAN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND REKANAN_ID=A.REKANAN_ID)PROVIDER,
                                (SELECT NAMA FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID=A.DOKTER_ID)NAMADOKTER,
                                (SELECT KETERANGAN FROM SR01_MED_MSKKLR_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND KATEGORI_ID='MP' AND MSKKLR_ID=A.PULANG_ID)CARAPULANG,
                                (SELECT TO_CHAR(TGL_DISTRIBUSI,'DD.MM.YYYY') FROM SR01_RM_TRACER_BERKAS WHERE LOKASI_ID='001' AND AKTIF='1' AND STATUS='I' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID ORDER BY CREATED_DATE DESC FETCH FIRST 1 ROW ONLY)TGLKEMBALI
                                                        
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID='001'
                            AND   A.AKTIF='1'
                            AND   A.JENIS_EPISODE='I'
                            AND   (A.STATUS_EPISODE='55' OR A.TGL_KELUAR IS NOT NULL)
                            AND   TRUNC(A.TGL_KELUAR) BETWEEN TRUNC(TO_DATE('".$startdate."','YYYY-MM-DD')) AND TRUNC(TO_DATE('".$endate."','YYYY-MM-DD'))
                            AND   A.EPISODE_ID IN (SELECT EPISODE_ID FROM SR01_RM_TRACER_BERKAS WHERE LOKASI_ID='001' AND AKTIF='1' AND STATUS='I' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID )
                            ORDER BY TGL_KELUAR DESC, RUANGRWT_ID ASC
                        )X
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>