<?php
    class Modelrencanamatrix extends CI_Model{

        function matrixms(){
            $query =
                    "
                        SELECT
                            X.*,
                            0 HARGA,
                            0 HARGADETAIL,
                            1 VOL,
                            CASE
                                WHEN X.JENIS = 'DT' AND X.TYPEID = 'P' THEN 'Orang'
                                ELSE ''
                            END SATUAN,
                            '' NOTE
                        FROM (
                            /* =========================
                            MASTER STRUCTURE (MS)
                            ========================= */
                            SELECT
                                'MS' JENIS,
                                A.MATRIX_ID,
                                A.MATRIX_ID_HEADER,
                                A.KODE,
                                DECODE(A.CATEGORY,'V','Variable Cost','F','Fixed Cost','') CATEGORY,
                                A.TYPE TYPEID,
                                DECODE(A.TYPE,'P','Beban Pegawai','B','Barang dan Jasa','Un Classification') TYPE,
                                A.COMPONENT
                            FROM SR01_EBITDA_MATRIX_MS A
                            WHERE A.LOKASI_ID = '001'
                            AND   A.AKTIF     = '1'

                            UNION ALL

                            /* =========================
                            DETAIL PEGAWAI (DT)
                            ========================= */
                            SELECT
                                'DT' JENIS,
                                '' MATRIX_ID,
                                B.MATRIX_ID MATRIX_ID_HEADER,

                                /* 🔥 KODE TURUNAN OTOMATIS */
                                B.KODE || '.1' KODE,

                                '' CATEGORY,
                                'P' TYPEID,
                                '' TYPE,
                                A.NIK || ' ' || A.NAMA COMPONENT

                            FROM HRD_KARYAWAN_MS A
                            JOIN SR01_EBITDA_MATRIX_MS B
                                ON B.SOURCE_DATA IN ('P_PNS','P_PNS_D','P_NONPNS','P_NONPNS_D')
                            AND B.AKTIF      = '1'
                            AND B.LOKASI_ID  = '001'

                            WHERE A.LOKASI_ID = '001'
                            AND   A.SHOW_ITEM = '1'
                            AND   A.NIK IN (
                                SELECT NIK
                                FROM SR01_EBITDA_PEGAWAI_DT
                                WHERE LOKASI_ID = '001'
                                AND   AKTIF     = '1'
                            )

                            /* =========================
                            FILTER LOGIKA PEGAWAI
                            ========================= */
                            AND (
                                /* STAT_PEK = T */
                                (
                                    B.SOURCE_DATA ='P_PNS'
                                    AND A.STAT_PEK = 'T'
                                )
                                OR (
                                    B.SOURCE_DATA ='P_NONPNS'
                                    AND A.STAT_PEK IN ('P','PTP')
                                )
                                /* TENAGA KHUSUS */
                                OR (
                                    B.SOURCE_DATA ='P_PNS_D'
                                    AND A.STAT_PEK = 'T' AND A.TENAGA_ID = 'NKS01'
                                )
                                OR (
                                    B.SOURCE_DATA ='P_NONPNS_D'
                                    AND A.STAT_PEK IN ('P','PTP') AND A.TENAGA_ID = 'NKS01'
                                )
                            )
                        ) X
                        ORDER BY KODE ASC, COMPONENT ASC




                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function insertmatrix($data){           
            $sql =   $this->db->insert("SR01_EBITDA_MATRIX_MS",$data);
            return $sql;
        }
    }
?>