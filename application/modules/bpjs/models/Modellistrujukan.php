<?php
    class Modellistrujukan extends CI_Model{

        function dataperujukinternal($nosep){
            $query =
                    "
                        SELECT A.DOKTER_ID, POLI_ID, RUANGRWT_ID, 
                            (SELECT NAMA       FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID=A.DOKTER_ID)NAMADOKTER,
                            (SELECT KETERANGAN FROM SR01_MED_POLI_MS   WHERE LOKASI_ID='001' AND AKTIF='1' AND POLI_ID=A.POLI_ID)POLITUJUAN
                            
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.STATUS_EPISODE<>'99'
                        AND   A.EPISODE_ID in (SELECT B.EPISODE_ID FROM SR01_KEU_BPJS_CVR B WHERE B.LOKASI_ID='001' AND B.AKTIF='1' AND B.SEP_NOMOR='".$nosep."')
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function cekrujukan($rujukan){
            $query =
                    "
                        SELECT A.*
                        FROM SR01_BPJS_RUJUKAN_DT A
                        WHERE A.NO_RUJUKAN='".$rujukan."'

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function insertlogrujukan($data){
            $sql = "
                INSERT INTO SR01_BPJS_RUJUKAN_DT
                (
                    NO_KARTU, NAMA, POLI, NO_SEP, NO_RUJUKAN,
                    POLI_TUJUAN, ICD_X_CODE, ICD_X_DESC,
                    RS_ID_RUJUKAN, RS_RUJUKAN, TGL_RUJUKAN
                )
                VALUES
                (
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    TO_DATE(?,'YYYY-MM-DD')
                )
            ";

            return $this->db->query($sql, [
                $data['NO_KARTU'],
                $data['NAMA'],
                $data['POLI'],
                $data['NO_SEP'],
                $data['NO_RUJUKAN'],
                $data['POLI_TUJUAN'],
                $data['ICD_X_CODE'],
                $data['ICD_X_DESC'],
                $data['RS_ID_RUJUKAN'],
                $data['RS_RUJUKAN'],
                $data['TGL_RUJUKAN']
            ]);
        }

    }
?>