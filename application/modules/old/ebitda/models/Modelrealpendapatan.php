<?php
    class Modelrealpendapatan extends CI_Model{

        function dataactual($startdate,$endate){
            $query =
                    "
                        SELECT X.*,
                            (SELECT NAMA_LAYAN1 FROM SR01_KEU_LAYAN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND LAYAN_ID=X.LAYAN_ID)NAMAPELAYANAN,
                            (SELECT INT_PASIEN_ID FROM SR01_GEN_PASIEN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND PASIEN_ID=X.PASIEN_ID)MRPASIEN,
                            SR01_GET_SUFFIX(X.PASIEN_ID)NAMAPASIEN
                        FROM(
                            SELECT A.PASIEN_ID, EPISODE_ID, LAYAN_ID, QTY, HARGA_SATUAN, HARGA_TOTAL, TO_CHAR(CREATED_DATE,'DD.MM.YYYY HH24:MI:SS')CREATEDDATE
                            FROM SR01_KEU_TRANSCTR_IT A
                            WHERE A.LOKASI_ID='001'
                            AND   A.AKTIF='1'
                            AND   TRUNC(A.CREATED_DATE) BETWEEN TRUNC(TO_DATE('".$startdate."','YYYY-MM-DD')) AND TRUNC(TO_DATE('".$endate."','YYYY-MM-DD'))
                            AND   EXISTS (SELECT LAYAN_ID FROM SR01_KEU_LAYAN_MS WHERE KATEGORI_ID='JKL-RAD' AND LAYAN_ID=A.LAYAN_ID)
                            AND   EXISTS (SELECT EPISODE_ID FROM SR01_KEU_EPISODE WHERE LOKASI_ID='001' AND AKTIF='1' AND STATUS_EPISODE<>'99' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        )X
                        ORDER BY NAMAPELAYANAN, NAMAPASIEN
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>