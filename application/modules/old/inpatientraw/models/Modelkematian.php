<?php
    class Modelkematian extends CI_Model{

        function laporankematian($startdate,$endate){
            $query =
                    "
                        SELECT
                            A.EPISODE_ID,
                            A.PASIEN_ID,
                            PAS.INT_PASIEN_ID                   AS REKAM_MEDIS,
                            PAS.NAMA                            AS NAMA_PASIEN,
                            PAS.ALAMAT1                         AS ALAMAT,
                            TO_CHAR(PAS.TGL_LAHIR,'DD.MM.YYYY') AS TGLLAHIR,
                            SR01_GET_SUFFIX(A.PASIEN_ID)        AS NAMAPASIEN,
                                                    
                            SR01_HITUNG_UMUR(PAS.TGL_LAHIR,A.TGL_KELUAR)UMUR,

                            TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY')   AS TGLMASUK,
                            TO_CHAR(A.TGL_KELUAR,'DD.MM.YYYY')  AS TGLKELUAR,

                            /* Tanggal Masuk Inap */
                            (
                                SELECT TO_CHAR(TRUNC(KM.CREATED_DATE),'DD.MM.YYYY')
                                FROM SR01_KEU_TRANSKMR_IT KM
                                WHERE KM.LOKASI_ID  = A.LOKASI_ID
                                AND   KM.EPISODE_ID = A.EPISODE_ID
                                AND   KM.PASIEN_ID  = A.PASIEN_ID
                                AND   KM.AKTIF      = '1'
                                AND   KM.CREATED_DATE = (
                                    SELECT MIN(KM1.CREATED_DATE)
                                    FROM SR01_KEU_TRANSKMR_IT KM1
                                    WHERE KM1.LOKASI_ID  = KM.LOKASI_ID
                                    AND   KM1.EPISODE_ID = KM.EPISODE_ID
                                    AND   KM1.PASIEN_ID  = KM.PASIEN_ID
                                    AND   KM1.AKTIF      = '1'
                                )
                            ) AS TGLMASUKINAP,

                            /* Jenis Kelamin */
                            SEX.KETERANGAN                      AS JENISKELAMIN,

                            /* Ruang Rawat */
                            UN.NAMA_UNIT || ' / ' || RU.NAMA_RUANG AS RUANG_RWT,

                            /* Kelas */
                            KLS.KETERANGAN                      AS KELAS,

                            /* Kelas Tanggungan BPJS */
                            (
                                SELECT DISTINCT
                                    DECODE(E.KELASTANGGUNGAN_KDKELAS,
                                            '3','KELAS 3',
                                            '2','KELAS 2',
                                            '1','KELAS 1')
                                FROM SR01_KEU_BPJS_CVR E
                                WHERE E.LOKASI_ID = A.LOKASI_ID
                                AND   E.EPISODE_ID = A.EPISODE_ID
                                AND   E.PASIEN_ID = A.PASIEN_ID
                                AND   E.AKTIF = '1'
                                AND   E.SEP_JENISLAYAN = '1'
                                AND   E.CREATED_DATE = (
                                    SELECT MAX(B.CREATED_DATE)
                                    FROM SR01_KEU_BPJS_CVR B
                                    WHERE B.LOKASI_ID = E.LOKASI_ID
                                    AND   B.EPISODE_ID = E.EPISODE_ID
                                    AND   B.PASIEN_ID = E.PASIEN_ID
                                    AND   B.AKTIF = '1'
                                    AND   B.SEP_JENISLAYAN = '1'
                                )
                            ) AS KELAS_TANGGUNGAN,

                            /* Cara Pulang */
                            (SELECT KLR.KETERANGAN
                            FROM SR01_MED_MSKKLR_MS KLR
                            WHERE KLR.KODE_ID = A.PULANG_ID
                            AND   KLR.KATEGORI_ID = 'MP'
                            ) AS KETERANGAN_PULANG,

                            /* Dokter */
                            A.DOKTER_ID,
                            DOK.NAMA                            AS NAMA_DOKTER,

                            /* Rekanan / Provider */
                            REK.NAMA                            AS PROVIDER,

                            /* Diagnosa Akhir */
                            DECODE(DIAG.ICD10, '', DIAG.DIAGNOSA, ICD.NM_DIAG1) AS DIAGNOSA_AKHIR,

                            /* Diagnosa Awal (seperti query lama) */
                            DECODE(
                                (SELECT B.ICD10
                                FROM WEB_CO_DIAGNOSA_MS B
                                WHERE B.EPISODE_ID = A.EPISODE_ID
                                AND   B.PASIEN_ID  = A.PASIEN_ID
                                AND   B.POLI_ID IS NOT NULL
                                AND   B.CREATED_DATE = (
                                        SELECT MAX(B1.CREATED_DATE)
                                        FROM WEB_CO_DIAGNOSA_MS B1
                                        WHERE B1.EPISODE_ID = B.EPISODE_ID
                                        AND   B1.PASIEN_ID  = B.PASIEN_ID
                                        AND   B1.POLI_ID IS NOT NULL
                                )),
                                '',
                                (SELECT B.DIAGNOSA
                                FROM WEB_CO_DIAGNOSA_MS B
                                WHERE B.EPISODE_ID = A.EPISODE_ID
                                AND   B.PASIEN_ID  = A.PASIEN_ID
                                AND   B.POLI_ID IS NOT NULL
                                AND   B.CREATED_DATE = (
                                        SELECT MAX(B1.CREATED_DATE)
                                        FROM WEB_CO_DIAGNOSA_MS B1
                                        WHERE B1.EPISODE_ID = B.EPISODE_ID
                                        AND   B1.PASIEN_ID  = B.PASIEN_ID
                                        AND   B1.POLI_ID IS NOT NULL
                                )),
                                (SELECT C.NM_DIAG1
                                FROM WEB_CO_DIAGNOSA_MS B
                                JOIN SR01_MED_ICD10_MS C ON C.KODE = B.ICD10
                                WHERE B.EPISODE_ID = A.EPISODE_ID
                                AND   B.PASIEN_ID  = A.PASIEN_ID
                                AND   B.POLI_ID IS NOT NULL
                                AND   B.CREATED_DATE = (
                                        SELECT MAX(B1.CREATED_DATE)
                                        FROM WEB_CO_DIAGNOSA_MS B1
                                        WHERE B1.EPISODE_ID = B.EPISODE_ID
                                        AND   B1.PASIEN_ID  = B.PASIEN_ID
                                        AND   B1.POLI_ID IS NOT NULL
                                ))
                            ) AS DIAGNOSA_AWAL,

                            (SELECT DISTINCT DIAG.A
                            FROM WEB_CO_DIAGNOSA_DT DIAG
                            WHERE DIAG.SHOW_ITEM='1'
                            AND   DIAG.FLAG_HAPUS='1'
                            AND   DIAG.CREATED_BY LIKE 'DR.%'
                            AND   DIAG.PASIEN_ID=A.PASIEN_ID
                            AND   DIAG.EPISODE_ID=A.EPISODE_ID
                            AND   DIAG.CREATED_DATE = (
                                                        SELECT MIN(CREATED_DATE)
                                                        FROM WEB_CO_DIAGNOSA_DT
                                                        WHERE SHOW_ITEM='1'
                                                        AND   FLAG_HAPUS='1'
                                                        AND   CREATED_BY LIKE 'DR.%'
                                                        AND   PASIEN_ID=DIAG.PASIEN_ID
                                                        AND   EPISODE_ID=DIAG.EPISODE_ID
                                                    )
                            FETCH FIRST 1 ROW ONLY
                            )SOAP_AWAL,

                            (SELECT DISTINCT DIAG.A
                            FROM WEB_CO_DIAGNOSA_DT DIAG
                            WHERE DIAG.SHOW_ITEM='1'
                            AND   DIAG.FLAG_HAPUS='1'
                            AND   DIAG.CREATED_BY LIKE 'DR.%'
                            AND   DIAG.PASIEN_ID=A.PASIEN_ID
                            AND   DIAG.EPISODE_ID=A.EPISODE_ID
                            AND   DIAG.CREATED_DATE = (
                                                        SELECT MAX(CREATED_DATE)
                                                        FROM WEB_CO_DIAGNOSA_DT
                                                        WHERE SHOW_ITEM='1'
                                                        AND   FLAG_HAPUS='1'
                                                        AND   CREATED_BY LIKE 'DR.%'
                                                        AND   PASIEN_ID=DIAG.PASIEN_ID
                                                        AND   EPISODE_ID=DIAG.EPISODE_ID
                                                    )
                            )SOAP_AKHIR,

                            /* Nomor SEP */
                            (
                                SELECT E.SEP_NOMOR
                                FROM SR01_KEU_BPJS_CVR E
                                WHERE E.LOKASI_ID = A.LOKASI_ID
                                AND   E.EPISODE_ID = A.EPISODE_ID
                                AND   E.PASIEN_ID = A.PASIEN_ID
                                AND   E.AKTIF = '1'
                                AND   E.SEP_JENISLAYAN = '1'
                                AND   E.CREATED_DATE = (
                                    SELECT MAX(B.CREATED_DATE)
                                    FROM SR01_KEU_BPJS_CVR B
                                    WHERE B.LOKASI_ID = E.LOKASI_ID
                                    AND   B.EPISODE_ID = E.EPISODE_ID
                                    AND   B.PASIEN_ID = E.PASIEN_ID
                                    AND   B.AKTIF = '1'
                                    AND   B.SEP_JENISLAYAN = '1'
                                )
                            ) AS NO_SEP

                        FROM SR01_KEU_EPISODE A
                        JOIN SR01_GEN_PASIEN_MS PAS
                            ON PAS.LOKASI_ID = A.LOKASI_ID AND PAS.PASIEN_ID = A.PASIEN_ID
                        JOIN SR01_GEN_GLOBAL_MS SEX
                            ON SEX.GLOBAL_ID = PAS.SEX_ID AND SEX.JENIS_ID = 'JSEX'
                        JOIN SR01_MED_RUANG_PRWT PRWT
                            ON PRWT.LOKASI_ID = A.LOKASI_ID AND PRWT.RUANGRWT_ID = A.RUANGRWT_ID
                        JOIN SR01_MED_RUANG_MS RU
                            ON RU.LOKASI_ID = PRWT.LOKASI_ID AND RU.RUANG_ID = PRWT.RUANG_ID
                        JOIN SR01_GEN_UNIT_MS UN
                            ON UN.LOKASI_ID = RU.LOKASI_ID AND UN.UNIT_ID = RU.UNIT_ID
                        JOIN SR01_KEU_KELAS_MS KLS
                            ON KLS.LOKASI_ID = A.LOKASI_ID AND KLS.KELAS_ID = A.KELAS_ID
                        JOIN SR01_KEU_REKANAN_MS REK
                            ON REK.LOKASI_ID = A.LOKASI_ID AND REK.REKANAN_ID = A.REKANAN_ID
                        JOIN SR01_MED_DOKTER_MS DOK
                            ON DOK.LOKASI_ID = A.LOKASI_ID AND DOK.DOKTER_ID = A.DOKTER_ID
                        LEFT JOIN WEB_CO_DIAGNOSA_MS DIAG
                            ON DIAG.EPISODE_ID = A.EPISODE_ID AND DIAG.SHOW_ITEM = '1'
                        LEFT JOIN SR01_MED_ICD10_MS ICD
                            ON ICD.KODE = DIAG.ICD10

                        WHERE A.LOKASI_ID = '001'
                        AND   A.AKTIF = '1'
                        AND   A.JENIS_EPISODE = 'I'
                        AND   A.STATUS_EPISODE = '55'
                        AND   A.PULANG_ID IN ('P03','P04','P10','P11')
                        AND   TRUNC(A.TGL_KELUAR) BETWEEN TRUNC(TO_DATE('".$startdate."','YYYY-MM-DD')) AND TRUNC(TO_DATE('".$endate."','YYYY-MM-DD'))

                        ORDER BY A.TGL_KELUAR, A.EPISODE_ID
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function chartews(){
            $query =
                    "
                        SELECT ROWNUM AS NOMOR, Z.*
                        FROM (
                                SELECT  A.TRANS_ID,
                                        A.PASIEN_ID,
                                        A.EPISODE_ID,
                                        A.RESPIRATORY,
                                        A.SATURASI,
                                        DECODE(A.SUPLEMENTASI, '0', 'No', '2', 'Yes') AS SUPLEMENTASI,
                                        A.TEMPERATURE,
                                        A.SISTOLIK,
                                        A.DIASTOLIK,
                                        A.HEARTRATE,
                                        DECODE(A.KESADARAN, '0', 'Alert', '3', 'Verbal/Pain/Unrespon') AS KESADARAN,
                                        C.SKOR, C.KE,
                                        TO_CHAR(A.CREATED_DATE, 'DD.MM.YYYY HH24:MI:SS') AS CREATED_DATE,
                                        A.CREATED_BY,
                                        B.NAMA AS PERAWAT,
                                        C.RUANG_ID, DECODE(D.KETERANGAN, NULL, E.NAMA_RUANG, D.KETERANGAN) RUANG
                                FROM SR01_MED_DETIL_EWS A
                                LEFT JOIN SR01_GEN_USER_DATA B
                                        ON B.LOKASI_ID = A.LOKASI_ID
                                        AND 'SIRS01_' || B.USER_ID = A.CREATED_BY
                                LEFT JOIN (
                                        SELECT DISTINCT TRANS_ID, EPISODE_ID, PASIEN_ID, RUANG_ID, SKOR, KE
                                        FROM SR01_MED_TRANS_EWS
                                        WHERE AKTIF = '1'
                                )C                
                                        ON C.TRANS_ID  = A.TRANS_ID
                                        AND C.EPISODE_ID = A.EPISODE_ID
                                        AND C.PASIEN_ID  = A.PASIEN_ID
                                LEFT JOIN SR01_MED_POLI_MS D
                                        ON D.POLI_ID = C.RUANG_ID
                                LEFT JOIN SR01_MED_RUANG_MS E
                                        ON E.RUANG_ID = C.RUANG_ID
                                WHERE A.AKTIF = '1'
                                AND A.JENIS = 'EWS'
                                AND A.EPISODE_ID = '125072794445' 
                                AND A.PASIEN_ID = '00116732'
                                ORDER BY A.CREATED_DATE
                                )Z
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }
    }
?>