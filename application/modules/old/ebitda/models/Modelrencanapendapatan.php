<?php
    class Modelrencanapendapatan extends CI_Model{

        function datarencanapendapatan($ppcid){
            $query =
                    "
                        SELECT A.PLAN_ID, LAYAN_ID, QTY, HARGA_SATUAN, QTY*HARGA_SATUAN TOTAL, TO_CHAR(CREATED_DATE,'DD.MM.YYYY HH24:MI:SS')CREATEDDATE,
                            (SELECT NAMA_LAYAN1 FROM SR01_KEU_LAYAN_MS WHERE LAYAN_ID=A.LAYAN_ID)NAMAPELAYANAN,
                            (SELECT NAMA FROM SR01_GEN_USER_DATA WHERE LOKASI_ID='001' AND AKTIF='1' AND 'SIRS01_'||UPPER(USER_ID)=A.CREATED_BY)DIBUATOLEH
                        FROM SR01_EBITDA_PLAN_PENDAPATAN A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.PPC_ID='".$ppcid."'
                        ORDER BY NAMAPELAYANAN ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function daftarlayanan($ppcid){
            $query =
                    "
                        SELECT DISTINCT A.LAYAN_ID, KELAS_ID, HARGA,
                            (SELECT NAMA_LAYAN1 FROM SR01_KEU_LAYAN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND LAYAN_ID=A.LAYAN_ID)NAMAPELAYANAN,
                            (SELECT NAMA_LAYAN2 FROM SR01_KEU_LAYAN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND LAYAN_ID=A.LAYAN_ID)KATEGORI,
                            (SELECT QTY FROM SR01_EBITDA_PLAN_PENDAPATAN WHERE LOKASI_ID='001' AND AKTIF='1' AND PPC_ID='".$ppcid."' AND LAYAN_ID=A.LAYAN_ID AND KELAS_ID=A.KELAS_ID)QTY
                        FROM SR01_KEU_HARGA_DT A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.KELAS_ID='3'
                        ORDER BY QTY ASC, NAMAPELAYANAN ASC, KELAS_ID ASC, KATEGORI ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function cekdataplan($ppcid,$layanid){
            $query =
                    "
                        SELECT A.PLAN_ID
                        FROM SR01_EBITDA_PLAN_PENDAPATAN A
                        WHERE A.LOKASI_ID='001'
                        AND   A.PPC_ID='".$ppcid."'
                        AND   A.LAYAN_ID='".$layanid."'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function insertplan($data){           
            $sql =   $this->db->insert("SR01_EBITDA_PLAN_PENDAPATAN",$data);
            return $sql;
        }

        function updateplan($data, $planid){           
            $sql =   $this->db->update("SR01_EBITDA_PLAN_PENDAPATAN",$data,array("PLAN_ID"=>$planid));
            return $sql;
        }
    }
?>