<?php
    class Modelsummary extends CI_Model{

        function datakunjunganrawatjalan($date){
            $query =
                    "
                        SELECT A.POLI_ID, REKANAN_ID, TO_CHAR(TRUNC(A.TGL_MASUK), 'DD.MM.YYYY') AS TANGGAL,
                            (SELECT KETERANGAN FROM SR01_MED_POLI_MS    WHERE LOKASI_ID='001' AND AKTIF='1' AND POLI_ID=A.POLI_ID)POLI,
                            (SELECT NAMA       FROM SR01_KEU_REKANAN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND REKANAN_ID=A.REKANAN_ID)REKANAN,
                            COUNT(*)JUMLAH
                            
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'
                        AND A.TGL_MASUK >= TO_DATE('".$date."','YYYY-MM-DD') - 15
                        AND A.TGL_MASUK < TO_DATE('".$date."','YYYY-MM-DD') + 1
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
                        GROUP BY POLI_ID, REKANAN_ID, TRUNC(A.TGL_MASUK)
                        ORDER BY TRUNC(A.TGL_MASUK) DESC

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function dataantrianhelpdesk(){
            $query =
                    "
                        SELECT
                            TO_CHAR(A.TANGGAL,'DD.MM.YYYY') AS TANGGAL,
                            SUM(CASE WHEN A.KODE = 'A' THEN 1 ELSE 0 END) AS KODE_A,
                            SUM(CASE WHEN A.KODE = 'B' THEN 1 ELSE 0 END) AS KODE_B,
                            SUM(CASE WHEN A.KODE = 'C' THEN 1 ELSE 0 END) AS KODE_C
                        FROM SR01_ANTRIAN_PASIEN A
                        WHERE A.LOKASI_ID = '001'
                        AND A.KODE IN ('A','B','C')
                        AND TRUNC(A.TANGGAL) >= TO_DATE('10.05.2026','DD.MM.YYYY')
                        GROUP BY TO_CHAR(A.TANGGAL,'DD.MM.YYYY')
                        ORDER BY TO_DATE(TO_CHAR(A.TANGGAL,'DD.MM.YYYY'),'DD.MM.YYYY') DESC
                        FETCH FIRST 30 ROWS ONLY

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function dataagamapasienri(){
            $query =
                    "
                        SELECT A.PASIEN_ID, EPISODE_ID, RUANGRWT_ID,
                            GETPIDINT(A.PASIEN_ID)MRPAS,
                            SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                            (SELECT RUANG_ID FROM SR01_MED_RUANG_PRWT WHERE PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)RUANGID,
                            (SELECT KETERANGAN FROM SR01_GEN_GLOBAL_MS WHERE JENIS_ID='SAGM' AND GLOBAL_ID=(SELECT AGAMA_ID FROM SR01_GEN_PASIEN_MS WHERE PASIEN_ID=A.PASIEN_ID))AGAMA
                            
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.JENIS_EPISODE='I'
                        AND   A.STATUS_EPISODE<>'99'
                        AND   A.EPISODE_ID IN (SELECT EPISODE_ID FROM SR01_MED_RUANG_PRWT WHERE PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID)
                        AND   A.PASIEN_ID NOT IN (SELECT PASIEN_ID FROM SR01_GEN_PASIEN_MS WHERE AGAMA_ID='SA1')
                        ORDER BY AGAMA ASC, RUANGID ASC, RUANGRWT_ID ASC, NAMAPASIEN ASC

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
        
    }
?>