<?php
    class Modelsyncurbpjsrj extends CI_Model{

        function cekdatasep($sep){
            $query =
                    "
                        SELECT A.PASIEN_ID, EPISODE_ID, TGLKUNJUNGAN, NOKARTU, SEP_JENISLAYAN,
                               GETPIDINT(A.PASIEN_ID) AS MRPAS,
                               SR01_GET_SUFFIX(A.PASIEN_ID) AS NAMAPASIEN
                        FROM SR01_KEU_BPJS_CVR A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.LAST_UPDATED_BY<>'BY PASS IGD'
                        AND   A.SEP_NOMOR='".$sep."'

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function cekdatastatusur($nomorsep){
            $query =
                    "
                        SELECT A.NO_SEP
                        FROM SR01_BPJS_UR_STATUS A
                        WHERE A.NO_SEP='".$nomorsep."'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function cekdataeklaim($jenissepisode,$nomorsep){
            $query =
                    "
                        SELECT A.NO_SEP
                        FROM SR01_BPJS_UR_DT A
                        WHERE A.JENIS_EPISODE='".$jenissepisode."'
                        AND   A.JENIS='1'
                        AND   A.NO_SEP='".$nomorsep."'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function cekdatacoding($pasienid,$episodeid){
            $query =
                    "
                        SELECT A.NOMOR_SEP
                        FROM SR01_BPJS_CODING A
                        WHERE A.JENIS_RAWAT='2'
                        AND   A.PASIEN_ID='".$pasienid."'
                        AND   A.EPISODE_ID='".$episodeid."'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function insertstatusur($data){           
            $sql =   $this->db->insert("SR01_BPJS_UR_STATUS",$data);
            return $sql;
        }

        function updatestatusur($nomorsep,$data){           
            $sql =   $this->db->update("SR01_BPJS_UR_STATUS",$data,array("NO_SEP"=>$nomorsep));
            return $sql;
        }

        function inserturbpjs($data){           
            $sql =   $this->db->insert("SR01_BPJS_UR_DT",$data);
            return $sql;
        }

        function updateurbpjs($nomorsep,$data){           
            $sql =   $this->db->update("SR01_BPJS_UR_DT",$data,array("NO_SEP"=>$nomorsep));
            return $sql;
        }
        
        function insertcoding($data){           
            $sql =   $this->db->insert("SR01_BPJS_CODING",$data);
            return $sql;
        }

        function updatecoding($pasienid,$episodeid,$nomorsep,$data){           
            $sql =   $this->db->update("SR01_BPJS_CODING",$data,array("PASIEN_ID"=>$pasienid,"EPISODE_ID"=>$episodeid,"NOMOR_SEP"=>$nomorsep));
            return $sql;
        }

    }
?>