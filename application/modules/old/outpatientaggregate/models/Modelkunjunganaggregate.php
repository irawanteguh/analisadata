<?php
    class Modelkunjunganaggregate extends CI_Model{

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

        function datakunjungan($periode){
            $query = "
                SELECT /*+ USE_NL(E R D P) */
                    E.PASIEN_ID,
                    E.EPISODE_ID,
                    E.REKANAN_ID,
                    E.DOKTER_ID,
                    E.POLI_ID,
                    TO_CHAR(E.TGL_MASUK,'YYYY-MM-DD')TGLMASUK,
                    R.NAMA AS PROVIDER,
                    UPPER(D.NAMA) AS NAMADOKTER,
                    P.KETERANGAN AS POLIKLINIK
                FROM SR01_KEU_EPISODE E
                LEFT JOIN SR01_KEU_REKANAN_MS R
                    ON R.REKANAN_ID = E.REKANAN_ID
                LEFT JOIN SR01_MED_DOKTER_MS D
                    ON D.DOKTER_ID = E.DOKTER_ID
                LEFT JOIN SR01_MED_POLI_MS P
                    ON P.POLI_ID = E.POLI_ID
                WHERE E.LOKASI_ID = '001'
                AND E.AKTIF = '1'
                AND E.JENIS_EPISODE = 'O'
                AND E.STATUS_EPISODE IN ('00','55')
                AND TO_CHAR(E.TGL_MASUK,'YYYY') = '".$periode."'
                AND (
                        -- Grup 1: POLI normal harus punya rawat selesai
                        (E.POLI_ID NOT IN ('UGD01','APS R0000000001','POLIFISIO','POLIFISOKUP','POLIFISWICARA','HEMOD0000000000','CAPD0000000001')
                        AND EXISTS (
                            SELECT 1
                            FROM SR01_MED_PRWT_TR T
                            WHERE T.LOKASI_ID = '001'
                            AND T.AKTIF = '1'
                            AND T.DONE_STATUS = '01'
                            AND T.STATUS = '1'
                            AND T.PASIEN_ID = E.PASIEN_ID
                            AND T.EPISODE_ID = E.EPISODE_ID
                        )
                        )
                        -- Grup 2: POLI khusus ambil semua
                        OR (E.POLI_ID IN ('POLIFISIO','POLIFISOKUP','POLIFISWICARA','HEMOD0000000000','CAPD0000000001'))
                    )
                ORDER BY E.TGL_MASUK DESC
            ";

            // Gunakan query streaming / cursor agar tidak kehabisan memory
            $recordset = $this->db->query($query);
            
            // Ambil hasil per row (bukan fetchAll) untuk hemat memory
            $results = [];
            foreach($recordset->result() as $row){
                $results[] = $row;
            }

            return $results;
        }



    }
?>