<?php
    class Modelrujukankeluar extends CI_Model{

        function periode(){
            $query =
                    "
                        SELECT DISTINCT TO_CHAR(A.TGL_MASUK,'YYYY-MM-DD')PERIODE
                        FROM SR01_KEU_EPISODE A
                        WHERE A.TGL_MASUK IS NOT NULL
                        AND   A.STATUS_EPISODE IN ('00','55')
                        AND   TO_CHAR(A.TGL_MASUK,'YYYY-MM-DD') NOT IN (SELECT PERIODE FROM SR01_PERIODE_RUJUKAN )
                        ORDER BY TO_CHAR(A.TGL_MASUK,'YYYY-MM-DD') DESC
                        FETCH FIRST 1 ROW ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function cekrujukan($rujukan){
            $query =
                    "
                        SELECT A.*
                        FROM SR01_BPJS_RUJUKAN_KELUAR_DT A
                        WHERE A.NO_RUJUKAN='".$rujukan."'

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function insertlogrujukan($data){
            $sql = "
                INSERT INTO SR01_BPJS_RUJUKAN_KELUAR_DT
                (
                    NO_RUJUKAN,
                    NO_SEP,
                    NO_KARTU,
                    NAMA,
                    KELAS_RAWAT,
                    KELAMIN,
                    TGL_LAHIR,
                    TGL_SEP,
                    TGL_RUJUKAN,
                    TGL_RENCANA_KUNJUNGAN,
                    PPK_DIRUJUK,
                    NAMA_PPK_DIRUJUK,
                    JNS_PELAYANAN,
                    CATATAN,
                    DIAG_RUJUKAN,
                    NAMA_DIAG_RUJUKAN,
                    TIPE_RUJUKAN,
                    NAMA_TIPE_RUJUKAN,
                    POLI_RUJUKAN,
                    NAMA_POLI_RUJUKAN
                )
                VALUES
                (
                    ?, ?, ?, ?, ?, ?,
                    TO_DATE(?,'YYYY-MM-DD'),
                    TO_DATE(?,'YYYY-MM-DD'),
                    TO_DATE(?,'YYYY-MM-DD'),
                    TO_DATE(?,'YYYY-MM-DD'),
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )
            ";

            return $this->db->query($sql, array_values($data));
        }

        function insertlog($data){           
            $sql =   $this->db->insert("SR01_PERIODE_RUJUKAN",$data);
            return $sql;
        }
    }
?>