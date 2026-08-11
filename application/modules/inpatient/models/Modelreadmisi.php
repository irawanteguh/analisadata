<?php
    class Modelreadmisi extends CI_Model{

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

        function datapasienreadmisi($periode){
            $query =
                    "
                        SELECT
                            A.PASIEN_ID,
                            A.EPISODE_ID,
                            A.PREV_EPISODE_ID,

                            GP.INT_PASIEN_ID AS MRPASIEN,
                            SR01_GET_SUFFIX(A.PASIEN_ID) AS NAMAPASIEN,

                            TO_CHAR(A.TGL_MASUK, 'DD.MM.YYYY') AS TGLMASUK,
                            TO_CHAR(A.TGL_MASUK, 'YYYY-MM-DD') AS PERIODE,
                            TO_CHAR(A.TGL_KELUAR, 'DD.MM.YYYY') AS TGLKELUAR,

                            A.DOKTER_ID,

                            UPPER(D.NAMA) AS NAMADOKTER,
                            UPPER(DL.NAMA) AS NAMADOKTERLAST,

                            RD.KOLEGIUM_ID AS KOLEGIUMID,
                            RDL.KOLEGIUM_ID AS KOLEGIUMIDLAST,

                            TO_CHAR(E.TGL_MASUK, 'DD.MM.YYYY') AS TGLMASUKLAST,
                            TO_CHAR(E.TGL_KELUAR, 'DD.MM.YYYY') AS TGLKELUARLAST,

                            TRUNC(A.TGL_MASUK) - TRUNC(E.TGL_KELUAR) AS JARAKWAKTU

                        FROM SR01_KEU_EPISODE A

                        INNER JOIN SR01_KEU_EPISODE E
                            ON  E.LOKASI_ID = A.LOKASI_ID
                            AND E.AKTIF = '1'
                            AND E.JENIS_EPISODE = 'I'
                            AND E.PASIEN_ID = A.PASIEN_ID
                            AND E.EPISODE_ID = A.PREV_EPISODE_ID

                        LEFT JOIN SR01_GEN_PASIEN_MS GP
                            ON  GP.LOKASI_ID = '001'
                            AND GP.AKTIF = '1'
                            AND GP.PASIEN_ID = A.PASIEN_ID

                        LEFT JOIN SR01_MED_DOKTER_MS D
                            ON  D.LOKASI_ID = '001'
                            AND D.AKTIF = '1'
                            AND D.DOKTER_ID = A.DOKTER_ID

                        LEFT JOIN SR01_MED_DOKTER_MS DL
                            ON  DL.LOKASI_ID = '001'
                            AND DL.AKTIF = '1'
                            AND DL.DOKTER_ID = E.DOKTER_ID

                        LEFT JOIN SR01_RMN_DOKTER_MS RD
                            ON  RD.LOKASI_ID = '001'
                            AND RD.DOKTER_ID = A.DOKTER_ID

                        LEFT JOIN SR01_RMN_DOKTER_MS RDL
                            ON  RDL.LOKASI_ID = '001'
                            AND RDL.DOKTER_ID = E.DOKTER_ID

                        WHERE A.LOKASI_ID = '001'
                        AND   A.AKTIF = '1'
                        AND   A.JENIS_EPISODE = 'I'
                        AND   A.STATUS_EPISODE <> '99'
                        AND   A.PREV_EPISODE_ID IS NOT NULL

                        AND   TO_CHAR(A.TGL_MASUK,'YYYY')='".$periode."'

                        AND A.TGL_MASUK >= E.TGL_KELUAR
                        AND A.TGL_MASUK <= E.TGL_KELUAR + 30

                        AND RD.KOLEGIUM_ID = RDL.KOLEGIUM_ID

                        ORDER BY A.TGL_MASUK DESC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
    }
?>