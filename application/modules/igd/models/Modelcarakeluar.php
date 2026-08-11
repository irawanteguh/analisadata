<?php
    class Modelcarakeluar extends CI_Model{

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

        function datacarakeluar($periode){
            $query =
                    "
                        SELECT  X.*,
                                TL.KETERANGAN AS TINDAKLANJUT,
                                DECODE(X.TLID,'TLANJUT1','DIRAWAT DI RUANGAN',PL.KETERANGAN) AS CARAKELUAR
                        FROM (
                            SELECT  A.PASIEN_ID,
                                    A.EPISODE_ID,
                                    A.TGL_MASUK,
                                    A.JENIS_EPISODE,
                                    A.POLI_ID,
                                    A.RUANGRWT_ID,
                                    TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY') TGLMASUK,
                                    TO_CHAR(A.TGL_MASUK,'MM') PERIODE,
                                    PS.INT_PASIEN_ID AS MRPAS,
                                    PS.SEX_ID AS SEXID,
                                    SR01_GET_SUFFIX(A.PASIEN_ID) AS NAMAPASIEN,
                                    HS.TINDAK_LANJUT AS TLID,
                                    HS.PULANG AS PULANGID
                            FROM SR01_KEU_EPISODE A

                            LEFT JOIN SR01_GEN_PASIEN_MS PS
                                ON PS.LOKASI_ID = '001'
                            AND PS.AKTIF = '1'
                            AND PS.PASIEN_ID = A.PASIEN_ID

                            LEFT JOIN SR01_MED_PRWT_TR TR
                                ON TR.LOKASI_ID = '001'
                            AND TR.AKTIF = '1'
                            AND TR.PASIEN_ID = A.PASIEN_ID
                            AND TR.EPISODE_ID = A.EPISODE_ID

                            LEFT JOIN SR01_MED_UGD_HSLAKHIR HS
                            ON HS.AKTIF = '1'
                            AND HS.TRANS_ID = TR.TRANS_ID

                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.STATUS_EPISODE <> '99'
                            AND EXTRACT(YEAR FROM A.TGL_MASUK) = '".$periode."'
                            AND (
                                    (A.JENIS_EPISODE = 'O' AND A.POLI_ID = 'UGD01')
                                OR (
                                    A.JENIS_EPISODE = 'I'
                                    AND EXISTS (
                                            SELECT 1
                                            FROM SR01_PASIEN_IGD I
                                            WHERE I.PASIEN_ID = A.PASIEN_ID
                                            AND I.EPISODE_ID = A.EPISODE_ID
                                        )
                                    )
                            )
                        ) X
                        LEFT JOIN SR01_GEN_GLOBAL_MS TL
                            ON TL.JENIS_ID = 'TLANJUT'
                        AND TL.GLOBAL_ID = X.TLID
                        LEFT JOIN SR01_GEN_GLOBAL_MS PL
                            ON PL.GLOBAL_ID = X.PULANGID
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
        
    }
?>