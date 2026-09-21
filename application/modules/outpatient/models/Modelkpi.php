<?php
    class Modelkpi extends CI_Model{

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

        function detailwwaktutunggu(){
            $query = "
                        SELECT X.*,
                            TO_CHAR(X.TGLCHECKIN, 'DD.MM.YYYY HH24:MI:SS') AS JAM_CHECKIN,
                            TO_CHAR(X.TGL_MULAI_ANAM, 'DD.MM.YYYY HH24:MI:SS') AS JAM_MULAI_ANAM,
                            TO_CHAR(X.TGL_SELESAI_ANAM, 'DD.MM.YYYY HH24:MI:SS') AS JAM_SELESAI_ANAM,
                            TO_CHAR(X.MULAIPERIKSA, 'DD.MM.YYYY HH24:MI:SS') AS JAM_PERIKSA_DOKTER,

                            ROUND((X.TGL_MULAI_ANAM - X.TGLCHECKIN) * 24 * 60) AS WAKTU_TUNGGU_ANAM_MENIT,

                            ROUND((X.TGL_SELESAI_ANAM - X.TGL_MULAI_ANAM) * 24 * 60) AS WAKTU_ANAM_MENIT,

                            ROUND((X.MULAIPERIKSA - X.TGL_SELESAI_ANAM) * 24 * 60) AS WAKTU_TUNGGU_DOKTER_MENIT,

                            ROUND((X.MULAIPERIKSA - X.TGLCHECKIN) * 24 * 60) AS WAKTU_TUNGGU_MENIT,

                            TRUNC((X.MULAIPERIKSA - X.TGLCHECKIN) * 24) || ':' ||
                            LPAD(
                                MOD(
                                    TRUNC((X.MULAIPERIKSA - X.TGLCHECKIN) * 24 * 60),
                                    60
                                ),
                                2,
                                '0'
                            ) || ':' ||
                            LPAD(
                                MOD(
                                    TRUNC((X.MULAIPERIKSA - X.TGLCHECKIN) * 24 * 60 * 60),
                                    60
                                ),
                                2,
                                '0'
                            ) AS WAKTU_TUNGGU

                        FROM (
                            SELECT A.PASIEN_ID,
                                A.EPISODE_ID,
                                A.POLI_ID,
                                A.DOKTER_ID,
                                TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY') TGLMASUK,
                                GETPIDINT(A.PASIEN_ID) MRPAS,
                                SR01_GET_SUFFIX(A.PASIEN_ID) NAMAPASIEN,

                                (SELECT NAMA
                                    FROM SR01_MED_DOKTER_MS
                                    WHERE LOKASI_ID = '001'
                                    AND AKTIF = '1'
                                    AND DOKTER_ID = A.DOKTER_ID) NAMADOKTER,

                                (SELECT KETERANGAN
                                    FROM SR01_MED_POLI_MS
                                    WHERE LOKASI_ID = '001'
                                    AND AKTIF = '1'
                                    AND POLI_ID = A.POLI_ID) POLIKLINIK,

                                (SELECT BOOKING_ID
                                    FROM WEB_CO_REGISTRASI_ONLINE_HD
                                    WHERE LOKASI_ID = '001'
                                    AND AKTIF = '1'
                                    AND PASIEN_ID = A.PASIEN_ID
                                    AND EPISODE_ID = A.EPISODE_ID) BOOKINGID,

                                (SELECT TGL_HADIR
                                    FROM WEB_CO_REGISTRASI_ONLINE_HD
                                    WHERE LOKASI_ID = '001'
                                    AND AKTIF = '1'
                                    AND PASIEN_ID = A.PASIEN_ID
                                    AND EPISODE_ID = A.EPISODE_ID) TGLCHECKIN,

                                (SELECT MIN(TGL_MULAI_ANAM)
                                    FROM WEB_CO_REGISTRASI_ONLINE_HD
                                    WHERE LOKASI_ID = '001'
                                    AND AKTIF = '1'
                                    AND PASIEN_ID = A.PASIEN_ID
                                    AND EPISODE_ID = A.EPISODE_ID) TGL_MULAI_ANAM,

                                (SELECT MIN(CREATED_DATE)
                                    FROM SR01_MED_ASSESMEN_AWAL
                                    WHERE AKTIF = '1'
                                    AND PASIEN_ID = A.PASIEN_ID
                                    AND EPISODE_ID = A.EPISODE_ID) TGL_SELESAI_ANAM,

                                (SELECT MIN(CREATED_DATE)
                                    FROM WEB_CO_MULAI_PERIKSA
                                    WHERE LOKASI_ID = '001'
                                    AND AKTIF = '1'
                                    AND PASIEN_ID = A.PASIEN_ID
                                    AND EPISODE_ID = A.EPISODE_ID) MULAIPERIKSA

                            FROM SR01_KEU_EPISODE A

                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.JENIS_EPISODE = 'O'
                            AND A.STATUS_EPISODE <> '99'

                            AND TO_CHAR(A.TGL_MASUK, 'DD.MM.YYYY') =
                                TO_CHAR(TRUNC(SYSDATE), 'DD.MM.YYYY')

                            AND A.POLI_ID NOT IN (
                                'APS',
                                'APS L0000000001',
                                'APS R0000000001',
                                'UGD01',
                                'UGD02',
                                'RUJUKANLUAR',
                                'MEDIC0000000000',
                                'POLI0000000000',
                                'POLI0000000043',
                                'POLI0000000042',
                                'POLI0000000041',
                                'POLI0000000044',
                                'POLI0000000052',
                                'POLI0000000031',
                                'HEMOD0000000000'
                            )

                            AND (
                                A.POLI_ID IN (
                                    'POLIFISIO',
                                    'POLIFISOKUP',
                                    'POLIFISWICARA',
                                    'HEMOD0000000000',
                                    'CAPD0000000001'
                                )
                                OR EXISTS (
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
                        ) X

                        ORDER BY X.TGLCHECKIN
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        
    }
?>