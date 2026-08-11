<?php
    class ModelResumeAI extends CI_Model{

        function listrresume(){
            $query =
                    "
                        SELECT 
                            A.PASIEN_ID,
                            A.EPISODE_ID,
                            TO_CHAR(A.TGL_KELUAR,'DD.MM.YYYY HH24:MI:SS') AS TGLKELUAR,
                            A.DOKTER_ID
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID      = '001'
                        AND   A.AKTIF          = '1'
                        AND   A.JENIS_EPISODE  = 'I'
                        AND   A.STATUS_EPISODE = '55'
                        AND   A.TGL_KELUAR IS NOT NULL
                        AND   A.TGL_KELUAR >= TO_DATE('22.05.2026','DD.MM.YYYY')
                        AND NOT EXISTS (
                            SELECT 1
                            FROM WEB_CO_RESUME_RANAP_AI B
                            WHERE B.EPISODE_ID = A.EPISODE_ID
                        )
                        ORDER BY A.TGL_KELUAR DESC
                        FETCH FIRST 30 ROWS ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function kunjungan($episodeid){
            $query =
                    "
                        SELECT X.*,
                            (SELECT KETERANGAN FROM SR01_MED_POLI_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND POLI_ID=X.POLIID)POLIKLINIK,
                            CASE
                                WHEN X.ASALSPRI = 'A001' THEN
                                'POLI'
                                ELSE
                                CASE
                                    WHEN X.RUANGRWT_ID LIKE 'KBY%' OR (X.RUANGRWT_ID LIKE 'PERINA%' AND X.RUANGIDFIRST LIKE 'KBY%') OR (X.RUANGRWT_ID LIKE 'NICU%' AND X.RUANGIDFIRST LIKE 'KBY%') THEN
                                    'BAYI'
                                    ELSE
                                    CASE
                                        WHEN (X.RUANGIDFIRST LIKE 'NICU%' AND X.RUANGRWT_ID LIKE 'PERINA%') OR (X.RUANGIDFIRST LIKE 'NICU%' AND X.RUANGRWT_ID LIKE 'PICU%') OR (X.RUANGIDFIRST LIKE 'NICU%' AND X.RUANGRWT_ID LIKE 'NICU%') THEN
                                        'NPP'
                                        ELSE
                                        CASE
                                            WHEN X.RUANGRWT_ID LIKE 'PERINA%' AND X.RUANGIDFIRST LIKE 'PERINA%' THEN
                                            'PERINA'
                                            ELSE
                                            'NORMAL'
                                        END
                                    END
                                END
                            END STATUSJENIS
                        FROM(
                            SELECT A.PASIEN_ID, EPISODE_ID, PULANG_ID, DOKTER_ID, STATUS_EPISODE, RUANGRWT_ID,
                                GETPIDINT(A.PASIEN_ID)MRPASIEN,
                                SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPSIEN,
                                TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY')TGLMASUK, TO_CHAR(A.TGL_KELUAR,'DD.MM.YYYY HH24:MI:SS')TGLKELUAR,
                                (SELECT KETERANGAN  FROM SR01_MED_MSKKLR_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND KATEGORI_ID='MP' AND MSKKLR_ID=A.PULANG_ID)CARAPULANG,
                                (SELECT NAMA        FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID=A.DOKTER_ID)NAMADOKTER,
                                (SELECT DEF_POLI_ID FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID=A.DOKTER_ID)POLIID,
                                (SELECT RUANG_ID    FROM SR01_KEU_TRANSKMR_IT WHERE LOKASI_ID='001' AND AKTIF='1' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID ORDER BY CREATED_DATE ASC FETCH FIRST 1 ROW ONLY)RUANGIDFIRST,
                                (SELECT ASAL_POLI   FROM WEB_CO_MINTA_RANAP WHERE LOKASI_ID='001' AND AKTIF='1' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID ORDER BY CREATED_DATE ASC FETCH FIRST 1 ROW ONLY)ASALSPRI,
                                (SELECT POLI_ID     FROM WEB_CO_MINTA_RANAP WHERE LOKASI_ID='001' AND AKTIF='1' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID ORDER BY CREATED_DATE ASC FETCH FIRST 1 ROW ONLY)POLIIDLAST
                                
                            FROM SR01_KEU_EPISODE A
                            WHERE A.LOKASI_ID='001'
                            AND   A.AKTIF='1'
                            AND   A.JENIS_EPISODE='I'
                            AND   A.EPISODE_ID='".$episodeid."'
                        )X
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function diagnosa($episodeid){
            $query =
                    "
                        SELECT A.ICD10, DIAGNOSA
                        FROM WEB_CO_DIAGNOSA_MS A
                        WHERE A.LOKASI_ID='001'
                        AND   A.SHOW_ITEM='1'
                        AND   A.EPISODE_ID='".$episodeid."'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result_array();
            return $recordset;
        }

        function keluhanutama($episodeid){
            $query =
                    "
                        SELECT A.CREATED_DATE, A.S, A.A, A.O, A.S2, A.S3, A.P
                        FROM WEB_CO_DIAGNOSA_DT A
                        WHERE A.EPISODE_ID = '".$episodeid."'
                        AND   A.FLAG_HAPUS = '1'
                        AND   A.SHOW_ITEM = '1'
                        AND   A.POLI_ID='UGD01'
                        AND   A.CREATED_BY LIKE 'DR%'
                        ORDER BY A.CREATED_DATE DESC
                        FETCH FIRST 1 ROW ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function keluhanutamapoli($poliid,$pasienid){
            $query =
                    "
                        SELECT A.CREATED_DATE, A.S, A.A, A.O, A.S2, A.S3, A.P
                        FROM WEB_CO_DIAGNOSA_DT A
                        WHERE A.PASIEN_ID = '".$pasienid."'
                        AND   A.FLAG_HAPUS = '1'
                        AND   A.SHOW_ITEM = '1'
                        AND   A.POLI_ID='".$poliid."'
                        ORDER BY A.CREATED_DATE DESC
                        FETCH FIRST 1 ROW ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function keluhanutamaranappoli($episodeid){
            $query =
                    "
                        SELECT A.CREATED_DATE, A.S, A.A, A.O, A.S2, A.S3, A.P
                        FROM WEB_CO_DIAGNOSA_DT A
                        WHERE A.EPISODE_ID = '".$episodeid."'
                        AND   A.FLAG_HAPUS = '1'
                        AND   A.SHOW_ITEM = '1'
                        AND   A.RUANG_ID IS NOT NULL
                        ORDER BY A.CREATED_DATE ASC
                        FETCH FIRST 1 ROW ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function keluhanutamabayibarulahir($episodeid){
            $query =
                    "
                        SELECT A.CREATED_DATE, A.S, A.A, A.O, A.S2, A.S3, A.P
                        FROM WEB_CO_DIAGNOSA_DT A
                        WHERE A.EPISODE_ID = '".$episodeid."'
                        AND   A.FLAG_HAPUS = '1'
                        AND   A.SHOW_ITEM = '1'
                        AND   A.RUANG_ID='KBY'
                        ORDER BY A.CREATED_DATE ASC
                        FETCH FIRST 1 ROW ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function keluhanutamabayibarulahirdokter($episodeid){
            $query =
                    "
                        SELECT A.CREATED_DATE, A.S, A.A, A.O, A.S2, A.S3, A.P
                        FROM WEB_CO_DIAGNOSA_DT A
                        WHERE A.EPISODE_ID = '".$episodeid."'
                        AND   A.FLAG_HAPUS = '1'
                        AND   A.SHOW_ITEM = '1'
                        AND   A.RUANG_ID='KBY'
                        AND   A.CREATED_BY LIKE 'DR%'
                        ORDER BY A.CREATED_DATE ASC
                        FETCH FIRST 1 ROW ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function keluhanutamabayibarulahirnicu($episodeid){
            $query =
                    "
                        SELECT A.CREATED_DATE, A.S, A.A, A.O, A.S2, A.S3, A.P
                        FROM WEB_CO_DIAGNOSA_DT A
                        WHERE A.EPISODE_ID = '".$episodeid."'
                        AND   A.FLAG_HAPUS = '1'
                        AND   A.SHOW_ITEM = '1'
                        AND   A.RUANG_ID='NICU'
                        ORDER BY A.CREATED_DATE ASC
                        FETCH FIRST 1 ROW ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function keluhanutamabayibarulahirperina($episodeid){
            $query =
                    "
                        SELECT A.CREATED_DATE, A.S, A.A, A.O, A.S2, A.S3, A.P
                        FROM WEB_CO_DIAGNOSA_DT A
                        WHERE A.EPISODE_ID = '".$episodeid."'
                        AND   A.FLAG_HAPUS = '1'
                        AND   A.SHOW_ITEM = '1'
                        AND   A.RUANG_ID LIKE 'PERINA%'
                        ORDER BY A.CREATED_DATE ASC
                        FETCH FIRST 1 ROW ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function keluhanutamabayibarulahirnicudokter($episodeid){
            $query =
                    "
                        SELECT A.CREATED_DATE, A.S, A.A, A.O, A.S2, A.S3, A.P,
                               (SELECT O FROM WEB_CO_DIAGNOSA_DT WHERE EPISODE_ID='".$episodeid."' AND FLAG_HAPUS='1' AND SHOW_ITEM='1' AND RUANG_ID='OKBESAR' AND CREATED_BY LIKE 'DR%' ORDER BY CREATED_DATE ASC FETCH FIRST 1 ROW ONLY)STATUSLOKALISOK
                        FROM WEB_CO_DIAGNOSA_DT A
                        WHERE A.EPISODE_ID = '".$episodeid."'
                        AND   A.FLAG_HAPUS = '1'
                        AND   A.SHOW_ITEM = '1'
                        AND   A.RUANG_ID='NICU'
                        AND   A.CREATED_BY LIKE 'DR%'
                        ORDER BY A.CREATED_DATE ASC
                        FETCH FIRST 1 ROW ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function keluhanutamabayibarulahirnicuperawat($episodeid){
            $query =
                    "
                        SELECT A.CREATED_DATE, A.S, A.A, A.O, A.S2, A.S3, A.P,
                               (SELECT O FROM WEB_CO_DIAGNOSA_DT WHERE EPISODE_ID='".$episodeid."' AND FLAG_HAPUS='1' AND SHOW_ITEM='1' AND RUANG_ID='OKBESAR' AND CREATED_BY LIKE 'DR%' ORDER BY CREATED_DATE ASC FETCH FIRST 1 ROW ONLY)STATUSLOKALISOK
                        FROM WEB_CO_DIAGNOSA_DT A
                        WHERE A.EPISODE_ID = '".$episodeid."'
                        AND   A.FLAG_HAPUS = '1'
                        AND   A.SHOW_ITEM = '1'
                        AND   A.RUANG_ID='NICU'
                        AND   A.CREATED_BY NOT LIKE 'DR%'
                        ORDER BY A.CREATED_DATE ASC
                        FETCH FIRST 1 ROW ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function keluhanutamabayibarulahirperinadokter($episodeid){
            $query =
                    "
                        SELECT A.CREATED_DATE, A.S, A.A, A.O, A.S2, A.S3, A.P
                        FROM WEB_CO_DIAGNOSA_DT A
                        WHERE A.EPISODE_ID = '".$episodeid."'
                        AND   A.FLAG_HAPUS = '1'
                        AND   A.SHOW_ITEM = '1'
                        AND   A.RUANG_ID LIKE 'PERINA%'
                        AND   A.CREATED_BY LIKE 'DR%'
                        ORDER BY A.CREATED_DATE ASC
                        FETCH FIRST 1 ROW ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function obat($episodeid){
            $query =
                    "
                        SELECT DISTINCT A.OBAT_ID, NAMA_OBAT, JENIS_RESEP
                        FROM WEB_CO_RESEP_DT A
                        WHERE A.LOKASI_ID='001'
                        AND   A.SHOW_ITEM='1'
                        AND   A.EPISODE_ID='".$episodeid."'
                        ORDER BY JENIS_RESEP, NAMA_OBAT ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result_array();
            return $recordset;
        }

        function hasilusg($episodeid){
            $query =
                    "
                        SELECT *
                        FROM (
                            SELECT 
                                A.CREATED_DATE,
                                A.O,
                                TO_CHAR(A.CREATED_DATE,'DD.MM.YYYY HH24:MI:SS') AS CREATEDDATE
                            FROM WEB_CO_DIAGNOSA_DT A
                            WHERE A.EPISODE_ID = '".$episodeid."'
                            AND A.FLAG_HAPUS = '1'
                            AND A.SHOW_ITEM = '1'
                            AND A.POLI_ID = 'UGD01'

                            UNION ALL

                            SELECT 
                                A.CREATED_DATE,
                                A.O,
                                TO_CHAR(A.CREATED_DATE,'DD.MM.YYYY HH24:MI:SS') AS CREATEDDATE
                            FROM WEB_CO_DIAGNOSA_DT A
                            WHERE A.EPISODE_ID = '".$episodeid."'
                            AND A.FLAG_HAPUS = '1'
                            AND A.SHOW_ITEM = '1'
                            AND A.RUANG_ID LIKE 'VK%'
                        )
                        ORDER BY CREATED_DATE ASC
                        FETCH FIRST 1 ROW ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function radiologi($episodeid){
            $query =
                    "
                        SELECT 
                            TO_CHAR(A.RADIOLOG_DATETIME_END,'DD.MM.YYYY HH24:MI:SS') AS CREATEDDATE,

                            CASE 
                                -- ? Jika ada KESAN
                                WHEN REGEXP_LIKE(DBMS_LOB.SUBSTR(A.EXPERTISE_TEXT_CONCLUSION, 4000, 1), 'KESAN', 'i') 
                                THEN REGEXP_SUBSTR(
                                        DBMS_LOB.SUBSTR(A.EXPERTISE_TEXT_CONCLUSION, 4000, 1),
                                        'KESAN\s*:?\s*(.*)',
                                        1, 1, 'i', 1
                                    )

                                -- ? Jika tidak ada ? ambil SEMUA bullet
                                ELSE REGEXP_SUBSTR(
                                        DBMS_LOB.SUBSTR(A.EXPERTISE_TEXT_CONCLUSION, 4000, 1),
                                        '(-\s*.*(' || CHR(10) || '-\s*.*)*)',
                                        1, 1
                                    )
                            END AS RESULT,

                            (SELECT NAMA_PEMERIKSAAN 
                            FROM RAD_MANAGER.RIS_IN 
                            WHERE ID = A.ID) AS NAMAPEMERIKSAAN

                        FROM RAD_MANAGER.RIS_OUT A
                        WHERE A.NO_REGISTER = '".$episodeid."'
                        AND   A.KODE_RADIOLOG IS NOT NULL
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result_array();
            return $recordset;
        }

        function laboratoriumhd($episodeid){
            $query =
                    "
                        SELECT A.*
                        FROM(
                            SELECT A.SAMPEL_ID, A.PASIEN_ID, A.REGISTRASI_ID, TO_CHAR(A.CREATED_DATE,'DD.MM.YYYY HH24:MI:SS') CREATEDDATE
                            FROM SAMPEL A
                            WHERE A.REGISTRASI_ID = '".$episodeid."'

                            UNION

                            SELECT A.SAMPEL_ID, A.PASIEN_ID, A.EPISODE_ID REGISTRASI_ID, TO_CHAR(A.ACC_DATE,'DD.MM.YYYY HH24:MI:SS') CREATEDDATE
                            FROM WEB_CO_HASIL_LAB_HD A
                            WHERE A.EPISODE_ID = '".$episodeid."'
                            AND A.AKTIF = '1'
                        )A
                        ORDER BY A.SAMPEL_ID
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result_array();
            return $recordset;
        }

        function laboratoriumdt($sampelid,$norm){
            $query =
                    "
                        SELECT A.*,
                        TO_CHAR(A.CREATED_DATE, 'DD.MM.YYYY') TANGGAL_LAB
                        FROM(
                            SELECT 
                            SUBSTR(LPAD(' ',((ITEM_LEVEL-2)*2))||C.NAME1,1,400) AS NAMA_TES, D.TEST_ID,
                            C.UNITS, C.RESULT_ID, D.HASIL_NOTE_ID,
                            CASE 
                            WHEN C.TEST_ID = 'KRBMU4' THEN 
                            DECODE( (SELECT SUBSTR(REGEXP_REPLACE(E.HASIL_TEKS, '(\{.\})|}|(\\\S+)', ''),3, LENGTH(REGEXP_REPLACE(E.HASIL_TEKS, '(\{.\})|}|(\\\S+)', ''))-5) FROM HASIL_NOTE E WHERE E.HASIL_NOTE_ID = D.HASIL_NOTE_ID),
                                '',
                                E.KETERANGAN,
                                (SELECT SUBSTR(REGEXP_REPLACE(E.HASIL_TEKS, '(\{.\})|}|(\\\S+)', ''),3, LENGTH(REGEXP_REPLACE(E.HASIL_TEKS, '(\{.\})|}|(\\\S+)', ''))-5) FROM HASIL_NOTE E WHERE E.HASIL_NOTE_ID = D.HASIL_NOTE_ID)
                            )
                            ELSE 
                                DECODE(D.HASIL_NOTE_ID,
                                '',
                                DECODE(C.RESULT_ID,
                                                '1',
                                                D.SIGN||D.RESULT_VALUE||' ('||
                                                SUBSTR(
                                                    GET_INTERPRETASI_AMBANG_BATAS(
                                                        D.DEMOGRAPHIC_ID, D.SAMPEL_ID, D.TEST_ID, D.REFERENCE_ID, D.REFERENCE_ID_AGE, D.RESULT_VALUE, D.SIGN
                                                    ),1,15
                                                )||')',
                                                D.SIGN||D.RESULT_VALUE
                                                ),
                                (SELECT SUBSTR(REGEXP_REPLACE(E.HASIL_TEKS, '(\{.*\})|}|(\\\S+)', ''),3,
                                        LENGTH(REGEXP_REPLACE(E.HASIL_TEKS, '(\{.*\})|}|(\\\S+)', ''))-5)
                                FROM HASIL_NOTE E
                                WHERE E.HASIL_NOTE_ID = D.HASIL_NOTE_ID)
                            )
                            END AS RESULT_VALUE,  --khusus Hasil Pembiakan, jika hasil_note kosong lihat dt_hasil_biakan, kalau gak kosong lihat hasil_note
                            D.RESULT_FLAG,
                            D.FLAG,
                            D.DONE_STATUS,
                            D.SAMPEL_ID,
                            D.PASIEN_ID,
                            D.CREATED_DATE,
                            SUBSTR(GETNORMAL_STATUS(C.RESULT_ID,  D.REFERENCE_ID, D.REFERENCE_ID_AGE, D.TEST_ID, D.PASIEN_ID),1,100) AS ANGKA_NORMAL, TO_CHAR(C.SORT_PRIORITY) SORT_PRIORITY,
                            1 URUT
                            FROM MS_TEST_LAB C
                            LEFT JOIN DT_TES_ORDER D ON D.TEST_ID = C.TEST_ID AND D.SAMPEL_ID = '".$sampelid."' AND D.PASIEN_ID = '10-".$norm."' AND TO_NUMBER(D.DONE_STATUS) = 4
                            LEFT JOIN DT_HASIL_BIAKAN B ON B.SAMPEL_ID = D.SAMPEL_ID AND C.TEST_ID = 'KRBMU4'
                            LEFT JOIN DT_KUMAN E ON E.DT_KUMAN_ID = B.DT_KUMAN_ID AND E.MS_KUMAN_ID = B.MS_KUMAN_ID
                            WHERE C.TEST_ID IN ( SELECT DISTINCT(A.TEST_ID)
                                FROM MS_TEST_LAB A 
                                START WITH A.TEST_ID IN (SELECT TEST_ID FROM DT_TES_ORDER WHERE SAMPEL_ID = '".$sampelid."' AND SHOW_ITEM='1' AND TO_NUMBER(DONE_STATUS) = 4)
                                CONNECT BY PRIOR A.OWNER=A.TEST_ID)
                                AND C.SHOW_ITEM='1'             
                                AND C.OWNER <> 'Master Tes'
                            UNION
                            SELECT DECODE(A.TEST_ID, NULL, A.NAMA_TEST, '    '||A.NAMA_TEST) NAMA_TEST, A.TEST_LAB_ID TEST_ID, A.SATUAN UNITS, NULL RESULT_ID, NULL HASIL_NOTE_ID, 
                            CASE
                                WHEN A.TEST_LAB_ID = 'KRBMU4' THEN C.KUMAN
                                ELSE
                                A.HASIL
                            END RESULT_VALUE
                            , A.FLAG RESULT_FLAG, NULL FLAG, NULL DONE_STATUS, A.SAMPEL_ID, NULL PASIEN_ID, B.ACC_DATE CREATED_DATE, A.NILAI_NORMAL ANGKA_NORMAL, DECODE(A.RESERVE4, NULL, '0', A.RESERVE4) SORT_PRIORITY, 2 URUT
                            FROM WEB_CO_HASIL_LAB_DT A
                            LEFT JOIN WEB_CO_HASIL_LAB_ST C ON C.EPISODE_ID = A.EPISODE_ID AND C.SAMPEL_ID = A.SAMPEL_ID --AND A.TEST_ID = 'KRBMU4'
                            , WEB_CO_HASIL_LAB_HD B
                            WHERE A.AKTIF = '1' AND B.AKTIF = '1'
                            AND A.SAMPEL_ID = '".$sampelid."'
                            AND A.EPISODE_ID = B.EPISODE_ID
                            AND A.TRANS_CO = B.TRANS_CO
                            AND A.SAMPEL_ID = B.SAMPEL_ID
                        )A
                        ORDER BY A.URUT, A.SORT_PRIORITY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result_array();
            return $recordset;
        }

        function cekdata($episodeid){
            $query =
                    "
                        SELECT A.EPISODE_ID
                        FROM WEB_CO_RESUME_RANAP_AI A
                        WHERE A.EPISODE_ID='".$episodeid."'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function cekttindakanoperasi($episodeid){
            $query =
                    "
                        SELECT A.PASIEN_ID, EPISODE_ID, LAYAN_ID
                        FROM SR01_KEU_TRANSCTR_IT A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.EPISODE_ID='".$episodeid."'
                        AND   A.LAYAN_ID = (SELECT LAYAN_ID FROM SR01_KEU_LAYAN_MS WHERE KATEGORI_ID='JKL-OPR' AND LAYAN_ID=A.LAYAN_ID)
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function updateresume($episodeid,$data){           
            $sql =   $this->db->update("WEB_CO_RESUME_RANAP_AI",$data,array("EPISODE_ID"=>$episodeid));
            return $sql;
        }

        function insertresume($data){           
            $sql =   $this->db->insert("WEB_CO_RESUME_RANAP_AI",$data);
            return $sql;
        }

    }
?>