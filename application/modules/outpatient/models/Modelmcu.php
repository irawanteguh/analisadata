<?php
    class Modelmcu extends CI_Model{

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

        function datamcudetail($periode){
            $query = "
                        SELECT X.*
                            FROM (
                                SELECT A.PASIEN_ID, EPISODE_ID, A.REKANAN_ID, TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY')TGLMASUK,
                                    (SELECT NAMA FROM SR01_KEU_REKANAN_MS P WHERE P.REKANAN_ID = A.REKANAN_ID) AS PROVIDER,
                                    GETPIDINT(A.PASIEN_ID)MRPAS,
                                    SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                                    NVL(
                                        (
                                            SELECT L.NAMA_LAYAN1
                                            FROM SR01_KEU_LAYAN_MS L
                                            WHERE L.LAYAN_ID = (
                                                SELECT M.LAYAN_ID_MCU
                                                FROM SR01_KEU_TRANSCTR_MCU M
                                                WHERE M.LOKASI_ID = '001'
                                                AND M.AKTIF = '1'
                                                AND M.PASIEN_ID = A.PASIEN_ID
                                                AND M.EPISODE_ID = A.EPISODE_ID
                                                AND EXISTS (
                                                    SELECT 1
                                                    FROM SR01_KEU_LAYAN_MS LM
                                                    WHERE LM.LAYAN_ID = M.LAYAN_ID_MCU
                                                        AND LM.KATEGORI_ID = 'JKL-MCU'
                                                )
                                                AND ROWNUM = 1
                                            )
                                        ),
                                        'NON PAKET'
                                    ) AS NAMAPAKET
                                    
                                FROM SR01_KEU_EPISODE A
                                WHERE A.LOKASI_ID = '001'
                                AND A.AKTIF = '1'
                                AND A.JENIS_EPISODE = 'O'
                                AND A.STATUS_EPISODE <> '99'
                                AND A.POLI_ID = 'MEDIC0000000000'
                                AND TO_CHAR(A.TGL_MASUK,'YYYY') = '".$periode."'
                                AND (
                                        (
                                            A.POLI_ID NOT IN (
                                                'UGD01',
                                                'APS R0000000001',
                                                'POLIFISIO',
                                                'POLIFISOKUP',
                                                'POLIFISWICARA',
                                                'HEMOD0000000000'
                                            )
                                            AND EXISTS (
                                                SELECT 1
                                                FROM SR01_MED_PRWT_TR T
                                                WHERE T.LOKASI_ID   = '001'
                                                AND T.AKTIF       = '1'
                                                AND T.DONE_STATUS = '01'
                                                AND T.STATUS      = '1'
                                                AND T.PASIEN_ID   = A.PASIEN_ID
                                                AND T.EPISODE_ID  = A.EPISODE_ID
                                            )
                                        )
                                        OR A.POLI_ID IN (
                                            'POLIFISIO',
                                            'POLIFISOKUP',
                                            'POLIFISWICARA',
                                            'HEMOD0000000000',
                                            'CAPD0000000001'
                                        )
                                )
                            )X
                            ORDER BY TO_DATE(X.TGLMASUK,'DD.MM.YYYY') DESC
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
        
    }
?>