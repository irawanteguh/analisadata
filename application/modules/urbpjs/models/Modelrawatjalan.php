<?php
    class Modelrawatjalan extends CI_Model{

        function periode(){
            $query =
                    "
                        SELECT (2014 + LEVEL) AS PERIODE
                        FROM DUAL
                        CONNECT BY LEVEL < EXTRACT(YEAR FROM SYSDATE) - 2014
                        ORDER BY PERIODE DESC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datarrjdetail($periode){
            $query =
                    "
                        WITH BPJS AS (
                            SELECT
                                PASIEN_ID,
                                EPISODE_ID,
                                MAX(SEP_NOMOR) AS SEP_NOMOR
                            FROM SR01_KEU_BPJS_CVR
                            WHERE AKTIF = '1'
                            AND SEP_JENISLAYAN = '2'
                            AND SEP_NOMOR NOT LIKE 'SEPRSUDPM%'
                            AND SEP_NOMOR NOT IN ('-','000')
                            GROUP BY PASIEN_ID, EPISODE_ID
                        ),

                        CODING AS (
                            SELECT
                                CODING_ID,
                                PASIEN_ID,
                                EPISODE_ID,
                                NOMOR_SEP,
                                BAST,
                                BAHV,
                                CODING_SOURCE,
                                NVL(BILL_TOTAL,0)    AS TARIF_RS,
                                NVL(TARIF_INACBG,0)  AS TARIF_INACBG,
                                NVL(TARIF_ABD,0)     AS TARIF_ABD
                            FROM SR01_BPJS_CODING
                            WHERE AKTIF = '1'
                        ),

                        FARMASI AS (
                            SELECT
                                PASIEN_ID,
                                EPISODE_ID,
                                NO_SEP,
                                SUM(NVL(TARIF_FARMASI,0))    AS TARIF_FARMASI
                            FROM SR01_BPJS_UR_DT
                            WHERE AKTIF = '1'
                            GROUP BY PASIEN_ID, EPISODE_ID, NO_SEP
                        ),

                        PENDAPATAN AS (
                            SELECT
                                A.PASIEN_ID,
                                A.EPISODE_ID,
                                NVL(SR01_PENDAPATAN_RAJAL.REG_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.JDR_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.OBAT_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.LAB_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.RAD_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.RADIOTERAPI_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.TIND_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.AMBU_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) AS TARIF_RS_REAL
                            FROM SR01_KEU_EPISODE A
                        )

                        SELECT
                            TO_CHAR(A.TGL_MASUK,'MM') AS BULAN,
                            RTRIM(TO_CHAR(A.TGL_MASUK,'MONTH','NLS_DATE_LANGUAGE=INDONESIAN')) AS BULAN_LAYANAN,
                            COUNT(*) AS JUMLAH_KUNJUNGAN,

                            SUM(CASE WHEN B.SEP_NOMOR IS NULL THEN 0 ELSE 1 END) AS JUMLAH_SEP,
                            SUM(CASE WHEN B.SEP_NOMOR IS NULL THEN 1 ELSE 0 END) AS JUMLAH_UN_SEP,

                            SUM(CASE WHEN C.CODING_SOURCE='GROUPING' THEN 1 ELSE 0 END) AS JUMLAH_GROUPING,
                            SUM(CASE WHEN C.CODING_SOURCE<>'GROUPING' THEN 1 ELSE 0 END) AS JUMLAH_UN_GROUPING,

                            SUM(CASE WHEN B.SEP_NOMOR IS NULL THEN NVL(P.TARIF_RS_REAL,0) ELSE 0 END) AS NILAI_UN_SEP,
                            SUM(CASE WHEN C.CODING_SOURCE<>'GROUPING' THEN NVL(C.TARIF_RS,0)  ELSE 0 END) AS NILAI_UN_GROUPING,
                            SUM(CASE WHEN C.CODING_ID IS NULL THEN NVL(P.TARIF_RS_REAL,0) ELSE C.TARIF_RS END) AS TOTAL_TARIF_RS,
                            SUM(CASE WHEN C.CODING_SOURCE='GROUPING' THEN NVL(C.TARIF_INACBG,0) ELSE 0 END) AS NILAI_GROUPING,

                            SUM(CASE WHEN C.CODING_SOURCE='GROUPING' AND C.BAHV='N' THEN 1 ELSE 0 END) AS JUMLAH_BAHV_N,
                            SUM(CASE WHEN C.CODING_SOURCE='GROUPING' AND C.BAHV='Y' THEN 1 ELSE 0 END) AS JUMLAH_BAHV_Y,
                            SUM(CASE WHEN C.CODING_SOURCE='GROUPING' AND C.BAHV='T' THEN 1 ELSE 0 END) AS JUMLAH_BAHV_T,

                            SUM(CASE WHEN C.CODING_SOURCE='GROUPING' AND C.BAHV='N' THEN NVL(C.TARIF_INACBG,0) ELSE 0 END) AS NILAI_BAHV_N,
                            SUM(CASE WHEN C.CODING_SOURCE='GROUPING' AND C.BAHV='Y' THEN NVL(C.TARIF_INACBG,0) ELSE 0 END) AS NILAI_BAHV_Y,
                            SUM(CASE WHEN C.CODING_SOURCE='GROUPING' AND C.BAHV='T' THEN NVL(C.TARIF_INACBG,0) ELSE 0 END) AS NILAI_BAHV_T,

                            SUM(NVL(C.TARIF_ABD,0)) AS NILAI_ABD,
                            SUM(NVL(D.TARIF_FARMASI,0)) AS NILAI_FARMASI

                        FROM SR01_KEU_EPISODE A

                        LEFT JOIN BPJS B
                        ON B.PASIEN_ID=A.PASIEN_ID
                        AND B.EPISODE_ID=A.EPISODE_ID

                        LEFT JOIN CODING C
                        ON C.PASIEN_ID=A.PASIEN_ID
                        AND C.EPISODE_ID=A.EPISODE_ID
                        AND C.NOMOR_SEP=B.SEP_NOMOR

                        LEFT JOIN FARMASI D
                        ON D.NO_SEP=B.SEP_NOMOR

                        LEFT JOIN PENDAPATAN P
                        ON P.PASIEN_ID=A.PASIEN_ID
                        AND P.EPISODE_ID=A.EPISODE_ID

                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'
                        AND A.REKANAN_ID = 'BPJS'
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
                                        WHERE T.LOKASI_ID = '001'
                                        AND T.AKTIF = '1'
                                        AND T.DONE_STATUS = '01'
                                        AND T.STATUS = '1'
                                        AND T.PASIEN_ID = A.PASIEN_ID
                                        AND T.EPISODE_ID = A.EPISODE_ID
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

                        GROUP BY
                            TO_CHAR(A.TGL_MASUK,'MM'),
                            RTRIM(TO_CHAR(A.TGL_MASUK,'MONTH','NLS_DATE_LANGUAGE=INDONESIAN'))

                        ORDER BY BULAN
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function quadrantdokter($periode){
            $query =
                    "
                        WITH BPJS AS (
                            SELECT
                                PASIEN_ID,
                                EPISODE_ID,
                                MAX(SEP_NOMOR) AS SEP_NOMOR
                            FROM SR01_KEU_BPJS_CVR
                            WHERE AKTIF = '1'
                            AND SEP_JENISLAYAN = '2'
                            AND SEP_NOMOR NOT LIKE 'SEPRSUDPM%'
                            AND SEP_NOMOR NOT IN ('-','000')
                            GROUP BY PASIEN_ID, EPISODE_ID
                        ),

                        CODING AS (
                            SELECT
                                CODING_ID,
                                PASIEN_ID,
                                EPISODE_ID,
                                NOMOR_SEP,
                                BAST,
                                BAHV,
                                CODING_SOURCE,
                                NVL(BILL_TOTAL,0)   AS TARIF_RS,
                                NVL(TARIF_INACBG,0) AS TARIF_INACBG,
                                NVL(TARIF_ABD,0)    AS TARIF_ABD
                            FROM SR01_BPJS_CODING
                            WHERE AKTIF = '1'
                        ),

                        FARMASI AS (
                            SELECT
                                PASIEN_ID,
                                EPISODE_ID,
                                NO_SEP,
                                SUM(NVL(TARIF_FARMASI,0)) AS TARIF_FARMASI
                            FROM SR01_BPJS_UR_DT
                            WHERE AKTIF = '1'
                            GROUP BY PASIEN_ID, EPISODE_ID, NO_SEP
                        ),

                        PENDAPATAN AS (
                            SELECT
                                A.PASIEN_ID,
                                A.EPISODE_ID,

                                NVL(SR01_PENDAPATAN_RAJAL.REG_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.JDR_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.OBAT_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.LAB_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.RAD_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.RADIOTERAPI_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.TIND_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.AMBU_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0)
                                AS TARIF_RS_REAL

                            FROM SR01_KEU_EPISODE A
                        )

                        SELECT

                            A.DOKTER_ID,

                            (
                                SELECT UPPER(NAMA)
                                FROM SR01_MED_DOKTER_MS
                                WHERE LOKASI_ID='001'
                                AND DOKTER_ID=A.DOKTER_ID
                            ) AS NAMADOKTER,

                            COUNT(*) AS JUMLAH_KUNJUNGAN,

                            SUM(
                                CASE
                                    WHEN C.CODING_ID IS NULL
                                        THEN NVL(P.TARIF_RS_REAL,0)
                                    ELSE
                                        C.TARIF_RS
                                END
                            ) AS TOTAL_TARIF_RS,

                            SUM(
                                CASE
                                    WHEN C.CODING_SOURCE='GROUPING'
                                        THEN NVL(C.TARIF_INACBG,0)
                                    ELSE
                                        0
                                END
                            ) AS NILAI_GROUPING,

                            SUM(NVL(C.TARIF_ABD,0)) AS NILAI_ABD,

                            SUM(NVL(D.TARIF_FARMASI,0)) AS NILAI_FARMASI,

                            (
                                SUM(
                                    CASE
                                        WHEN C.CODING_SOURCE='GROUPING'
                                            THEN NVL(C.TARIF_INACBG,0)
                                        ELSE
                                            0
                                    END
                                )
                                +
                                SUM(NVL(C.TARIF_ABD,0))
                                +
                                SUM(NVL(D.TARIF_FARMASI,0))
                            )
                            -
                            SUM(
                                CASE
                                    WHEN C.CODING_ID IS NULL
                                        THEN NVL(P.TARIF_RS_REAL,0)
                                    ELSE
                                        C.TARIF_RS
                                END
                            ) AS SELISIH,

                            ROUND(
                                (
                                    (
                                        SUM(
                                            CASE
                                                WHEN C.CODING_SOURCE='GROUPING'
                                                    THEN NVL(C.TARIF_INACBG,0)
                                                ELSE
                                                    0
                                            END
                                        )
                                        +
                                        SUM(NVL(C.TARIF_ABD,0))
                                        +
                                        SUM(NVL(D.TARIF_FARMASI,0))
                                    )
                                    /
                                    NULLIF(
                                        SUM(
                                            CASE
                                                WHEN C.CODING_ID IS NULL
                                                    THEN NVL(P.TARIF_RS_REAL,0)
                                                ELSE
                                                    C.TARIF_RS
                                            END
                                        ),
                                        0
                                    )
                                ) * 100,
                                2
                            ) AS CRR

                        FROM SR01_KEU_EPISODE A

                        LEFT JOIN BPJS B
                            ON B.PASIEN_ID = A.PASIEN_ID
                            AND B.EPISODE_ID = A.EPISODE_ID

                        LEFT JOIN CODING C
                            ON C.PASIEN_ID = A.PASIEN_ID
                            AND C.EPISODE_ID = A.EPISODE_ID
                            AND C.NOMOR_SEP = B.SEP_NOMOR

                        LEFT JOIN FARMASI D
                            ON D.NO_SEP = B.SEP_NOMOR

                        LEFT JOIN PENDAPATAN P
                            ON P.PASIEN_ID = A.PASIEN_ID
                            AND P.EPISODE_ID = A.EPISODE_ID

                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'
                        AND A.REKANAN_ID = 'BPJS'
                        AND A.DOKTER_ID<>'DR. F0000000001'
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
                                        WHERE T.LOKASI_ID='001'
                                        AND T.AKTIF='1'
                                        AND T.DONE_STATUS='01'
                                        AND T.STATUS='1'
                                        AND T.PASIEN_ID=A.PASIEN_ID
                                        AND T.EPISODE_ID=A.EPISODE_ID
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

                        GROUP BY A.DOKTER_ID
                        ORDER BY CRR DESC, NAMADOKTER
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function quadrantsmf($periode){
            $query =
                    "
                        WITH BPJS AS (
                            SELECT
                                PASIEN_ID,
                                EPISODE_ID,
                                MAX(SEP_NOMOR) AS SEP_NOMOR
                            FROM SR01_KEU_BPJS_CVR
                            WHERE AKTIF = '1'
                            AND SEP_JENISLAYAN = '2'
                            AND SEP_NOMOR NOT LIKE 'SEPRSUDPM%'
                            AND SEP_NOMOR NOT IN ('-','000')
                            GROUP BY PASIEN_ID, EPISODE_ID
                        ),

                        CODING AS (
                            SELECT
                                CODING_ID,
                                PASIEN_ID,
                                EPISODE_ID,
                                NOMOR_SEP,
                                BAST,
                                BAHV,
                                CODING_SOURCE,
                                NVL(BILL_TOTAL,0)   AS TARIF_RS,
                                NVL(TARIF_INACBG,0) AS TARIF_INACBG,
                                NVL(TARIF_ABD,0)    AS TARIF_ABD
                            FROM SR01_BPJS_CODING
                            WHERE AKTIF = '1'
                        ),

                        FARMASI AS (
                            SELECT
                                PASIEN_ID,
                                EPISODE_ID,
                                NO_SEP,
                                SUM(NVL(TARIF_FARMASI,0)) AS TARIF_FARMASI
                            FROM SR01_BPJS_UR_DT
                            WHERE AKTIF = '1'
                            GROUP BY PASIEN_ID, EPISODE_ID, NO_SEP
                        ),

                        PENDAPATAN AS (
                            SELECT
                                A.PASIEN_ID,
                                A.EPISODE_ID,

                                NVL(SR01_PENDAPATAN_RAJAL.REG_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.JDR_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.OBAT_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.LAB_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.RAD_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.RADIOTERAPI_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.TIND_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) +
                                NVL(SR01_PENDAPATAN_RAJAL.AMBU_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0)
                                AS TARIF_RS_REAL

                            FROM SR01_KEU_EPISODE A
                        )

                        SELECT

                            K.KOLEGIUM_ID,

                            (
                                SELECT UPPER(KOLEGIUM)
                                FROM SR01_RMN_KOLEGIUM_MS
                                WHERE LOKASI_ID='001'
                                AND KOLEGIUM_ID=K.KOLEGIUM_ID
                            ) AS KOLEGIUM,

                            COUNT(*) AS JUMLAH_KUNJUNGAN,

                            SUM(
                                CASE
                                    WHEN C.CODING_ID IS NULL
                                        THEN NVL(P.TARIF_RS_REAL,0)
                                    ELSE
                                        C.TARIF_RS
                                END
                            ) AS TOTAL_TARIF_RS,

                            SUM(
                                CASE
                                    WHEN C.CODING_SOURCE='GROUPING'
                                        THEN NVL(C.TARIF_INACBG,0)
                                    ELSE
                                        0
                                END
                            ) AS NILAI_GROUPING,

                            SUM(NVL(C.TARIF_ABD,0)) AS NILAI_ABD,

                            SUM(NVL(D.TARIF_FARMASI,0)) AS NILAI_FARMASI,

                            (
                                SUM(
                                    CASE
                                        WHEN C.CODING_SOURCE='GROUPING'
                                            THEN NVL(C.TARIF_INACBG,0)
                                        ELSE
                                            0
                                    END
                                )
                                +
                                SUM(NVL(C.TARIF_ABD,0))
                                +
                                SUM(NVL(D.TARIF_FARMASI,0))
                            )
                            -
                            SUM(
                                CASE
                                    WHEN C.CODING_ID IS NULL
                                        THEN NVL(P.TARIF_RS_REAL,0)
                                    ELSE
                                        C.TARIF_RS
                                END
                            ) AS SELISIH,

                            ROUND(
                                (
                                    (
                                        SUM(
                                            CASE
                                                WHEN C.CODING_SOURCE='GROUPING'
                                                    THEN NVL(C.TARIF_INACBG,0)
                                                ELSE
                                                    0
                                            END
                                        )
                                        +
                                        SUM(NVL(C.TARIF_ABD,0))
                                        +
                                        SUM(NVL(D.TARIF_FARMASI,0))
                                    )
                                    /
                                    NULLIF(
                                        SUM(
                                            CASE
                                                WHEN C.CODING_ID IS NULL
                                                    THEN NVL(P.TARIF_RS_REAL,0)
                                                ELSE
                                                    C.TARIF_RS
                                            END
                                        ),
                                        0
                                    )
                                ) * 100,
                                2
                            ) AS CRR

                        FROM SR01_KEU_EPISODE A

                        LEFT JOIN BPJS B
                            ON B.PASIEN_ID = A.PASIEN_ID
                            AND B.EPISODE_ID = A.EPISODE_ID

                        LEFT JOIN CODING C
                            ON C.PASIEN_ID = A.PASIEN_ID
                            AND C.EPISODE_ID = A.EPISODE_ID
                            AND C.NOMOR_SEP = B.SEP_NOMOR

                        LEFT JOIN FARMASI D
                            ON D.NO_SEP = B.SEP_NOMOR

                        LEFT JOIN SR01_RMN_DOKTER_MS K
                            ON K.DOKTER_ID = A.DOKTER_ID

                        LEFT JOIN PENDAPATAN P
                            ON P.PASIEN_ID = A.PASIEN_ID
                            AND P.EPISODE_ID = A.EPISODE_ID
                            
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'
                        AND A.REKANAN_ID = 'BPJS'
                        AND A.DOKTER_ID<>'DR. F0000000001'
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
                                        WHERE T.LOKASI_ID='001'
                                        AND T.AKTIF='1'
                                        AND T.DONE_STATUS='01'
                                        AND T.STATUS='1'
                                        AND T.PASIEN_ID=A.PASIEN_ID
                                        AND T.EPISODE_ID=A.EPISODE_ID
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

                        GROUP BY K.KOLEGIUM_ID
                        ORDER BY CRR DESC, KOLEGIUM
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function quadrantresource($periode){
            $query =
                    "
                        WITH BPJS AS (
                            SELECT
                                PASIEN_ID,
                                EPISODE_ID,
                                MAX(SEP_NOMOR) AS SEP_NOMOR
                            FROM SR01_KEU_BPJS_CVR
                            WHERE AKTIF = '1'
                            AND SEP_JENISLAYAN = '2'
                            AND SEP_NOMOR NOT LIKE 'SEPRSUDPM%'
                            AND SEP_NOMOR NOT IN ('-', '000')
                            GROUP BY
                                PASIEN_ID,
                                EPISODE_ID
                        ),

                        CODING AS (
                            SELECT
                                CODING_ID,
                                PASIEN_ID,
                                EPISODE_ID,
                                NOMOR_SEP,
                                BAST,
                                BAHV,
                                CODING_SOURCE,
                                BILL_REG,
                                BILL_JASA,
                                BILL_OBAT,
                                BILL_LAB,
                                BILL_RAD,
                                BILL_RADIOTERAPI,
                                BILL_TINDAKAN,
                                BILL_AMBULAN
                            FROM SR01_BPJS_CODING
                            WHERE AKTIF = '1'
                        )

                        SELECT
                            A.DOKTER_ID,

                            SUM(CASE WHEN C.CODING_ID IS NULL THEN NVL(SR01_PENDAPATAN_RAJAL.REG_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) ELSE NVL(C.BILL_REG,0) END) AS REGISTRASI,
                            SUM(CASE WHEN C.CODING_ID IS NULL THEN NVL(SR01_PENDAPATAN_RAJAL.JDR_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) ELSE NVL(C.BILL_JASA,0) END) AS JASA_DOKTER,
                            SUM(CASE WHEN C.CODING_ID IS NULL THEN NVL(SR01_PENDAPATAN_RAJAL.OBAT_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) ELSE NVL(C.BILL_OBAT,0) END) AS OBAT,
                            SUM(CASE WHEN C.CODING_ID IS NULL THEN NVL(SR01_PENDAPATAN_RAJAL.LAB_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) ELSE NVL(C.BILL_LAB,0) END) AS LABORATORIUM,
                            SUM(CASE WHEN C.CODING_ID IS NULL THEN NVL(SR01_PENDAPATAN_RAJAL.RAD_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) ELSE NVL(C.BILL_RAD,0) END) AS RADIOLOGI,
                            SUM(CASE WHEN C.CODING_ID IS NULL THEN NVL(SR01_PENDAPATAN_RAJAL.RADIOTERAPI_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) ELSE NVL(C.BILL_RADIOTERAPI,0) END) AS RADIOTERAPI,
                            SUM(CASE WHEN C.CODING_ID IS NULL THEN NVL(SR01_PENDAPATAN_RAJAL.TIND_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) ELSE NVL(C.BILL_TINDAKAN,0) END) AS TINDAKAN,
                            SUM(CASE WHEN C.CODING_ID IS NULL THEN NVL(SR01_PENDAPATAN_RAJAL.AMBU_RAJAL(A.EPISODE_ID,A.PASIEN_ID),0) ELSE NVL(C.BILL_AMBULAN,0) END) AS AMBULAN,

                            (
                                SELECT UPPER(NAMA)
                                FROM SR01_MED_DOKTER_MS
                                WHERE LOKASI_ID = '001'
                                AND DOKTER_ID = A.DOKTER_ID
                            ) AS NAMADOKTER

                        FROM SR01_KEU_EPISODE A

                        LEFT JOIN BPJS B
                            ON B.PASIEN_ID = A.PASIEN_ID
                        AND B.EPISODE_ID = A.EPISODE_ID

                        LEFT JOIN CODING C
                            ON C.PASIEN_ID = A.PASIEN_ID
                        AND C.EPISODE_ID = A.EPISODE_ID
                        AND C.NOMOR_SEP = B.SEP_NOMOR

                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'
                        AND A.REKANAN_ID = 'BPJS'
                        AND A.DOKTER_ID <> 'DR. F0000000001'
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
                                        WHERE T.LOKASI_ID = '001'
                                        AND T.AKTIF = '1'
                                        AND T.DONE_STATUS = '01'
                                        AND T.STATUS = '1'
                                        AND T.PASIEN_ID = A.PASIEN_ID
                                        AND T.EPISODE_ID = A.EPISODE_ID
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

                        GROUP BY
                            A.DOKTER_ID

                        ORDER BY
                            NAMADOKTER
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datadetailtidakadasep($periode){
            $query =
                    "
                        WITH BPJS AS (
                            SELECT
                                PASIEN_ID,
                                EPISODE_ID,
                                MAX(SEP_NOMOR) AS SEP_NOMOR,
                                MAX(LAST_UPDATED_BY) AS LASTUPDATE
                            FROM SR01_KEU_BPJS_CVR
                            WHERE AKTIF = '1'
                            AND   SEP_JENISLAYAN = '2'
                            GROUP BY PASIEN_ID, EPISODE_ID
                        )
                                                
                        SELECT A.PASIEN_ID, A.EPISODE_ID, TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY')TGLMASUK, A.DOKTER_ID, A.POLI_ID,
                            B.SEP_NOMOR, LASTUPDATE,
                            (SELECT UPPER(NAMA) FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001'  AND DOKTER_ID=A.DOKTER_ID)NAMADOKTER,
                            (SELECT KETERANGAN FROM SR01_MED_POLI_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND POLI_ID=A.POLI_ID)POLIKLINIK,
                            SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                            GETPIDINT(A.PASIEN_ID)MRPAS
                            
                        FROM SR01_KEU_EPISODE A

                        LEFT JOIN BPJS B
                        ON B.PASIEN_ID=A.PASIEN_ID
                        AND B.EPISODE_ID=A.EPISODE_ID
                                                
                        WHERE A.LOKASI_ID = '001'
                        AND A.AKTIF = '1'
                        AND A.JENIS_EPISODE = 'O'
                        AND A.STATUS_EPISODE <> '99'
                        AND A.REKANAN_ID = 'BPJS'
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
                                        WHERE T.LOKASI_ID = '001'
                                        AND T.AKTIF = '1'
                                        AND T.DONE_STATUS = '01'
                                        AND T.STATUS = '1'
                                        AND T.PASIEN_ID = A.PASIEN_ID
                                        AND T.EPISODE_ID = A.EPISODE_ID
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
                        AND (
                            B.SEP_NOMOR IS NULL
                            OR B.SEP_NOMOR NOT LIKE '0112R066%'
                        )
                        ORDER BY A.TGL_MASUK ASC, POLIKLINIK ASC, NAMADOKTER ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>